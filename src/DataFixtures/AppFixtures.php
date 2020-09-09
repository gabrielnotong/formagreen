<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Discount;
use App\Entity\GreenSpace;
use App\Entity\Partner;
use App\Entity\Prestation;
use App\Entity\CenterType;
use App\Entity\TrainingCenter;
use App\Entity\User;
use App\Entity\UserLambda;
use App\Service\Geolocation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private Geolocation $geolocation;

    public function __construct(
        Geolocation $geolocation
    ) {
        $this->geolocation = $geolocation;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void 
    {
        $faker = Factory::create();
        $users = [];
        $genders = ['male', 'female'];

        $startDate = new DateTime('now');
        // Manage fake members
        for ($i = 0; $i <= 9; $i++) {
            $user = new UserLambda();

            $gender = $faker->randomElement($genders);

            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPlainTextPassword('password')
                ->setNumberOfMonths(mt_rand(2,5))
                ->setPhoneNumber($faker->e164PhoneNumber)
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        // Manage fake partners
        $partners = [];
        for ($i = 0; $i <= 9; $i++) {
            $partner = (new Partner())
                ->setName($faker->company)
                ->setEmail($faker->companyEmail)
                ->setPhoneNumber($faker->e164PhoneNumber)
                ->setStreetNumber($faker->numberBetween(1, 255))
                ->setStreetName($faker->streetName)
                ->setZipCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
            ;

            $partners[] = $partner;
            $manager->persist($partner);
        }

        // Manage fake discounts
        $discounts = [];
        for ($i = 0; $i <= 9; $i++) {
            $endDate = new DateTime(sprintf(User::ADD_MONTHS,  $faker->numberBetween(1, 4)));
            $discount = (new Discount())
                ->setPercentage($faker->numberBetween(2, 6))
                ->setPartner($partners[$i])
                ->setDescription('Discount on all tools in my shop')
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
            ;

            $discounts[] = $discount;
            $manager->persist($discount);
        }

        // Manage fake training structures type
        $types = ['School', 'University', 'Company', 'Parc'];
        $tcTypes = [];
        for ($i = 0; $i <= 3; $i++) {
            $tcType = (new CenterType())
                ->setName($types[$i])
                ->setDescription($types[$i] . ' ' . $faker->city)
            ;

            $tcTypes[] = $tcType;
            $manager->persist($tcType);
        }

        // Manage fake training structures
        $trainingCenters = [];
        for ($i = 0; $i <= 9; $i++) {
            $tc = new TrainingCenter();
            $tc->setEmail($faker->email)
                ->setPlainTextPassword('password')
                ->setCompanyName($faker->company)
                ->setStreetNumber($faker->numberBetween(1, 255))
                ->setStreetName($faker->streetName)
                ->setZipCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setPhoneNumber($faker->e164PhoneNumber)
                ->setCenterType($tcTypes[$faker->numberBetween(0, 3)])
            ;

            $trainingCenters[] = $tc;
            $manager->persist($tc);
        }

        // Manage fake green spaces
        $greenSpaces = [];
        for ($i = 0; $i <= 99; $i++) {
            $greenSpace = (new GreenSpace())
                ->setName($faker->city)
                ->setStreetNumber($faker->numberBetween(1, 10))
                ->setStreetName($faker->streetName)
                ->setZipCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setTrainingCenter($trainingCenters[$faker->numberBetween(0, 9)])
            ;
            $coordinates = $this->geolocation->generateCoordinates($greenSpace);
            $greenSpace
                ->setLatitude($coordinates['lat'])
                ->setLongitude($coordinates['long']);

            $greenSpaces[] = $greenSpace;
            $manager->persist($greenSpace);
        }

        // Manage fake prestations
        $greenSpaceTypes = ['Maintenance', 'Installation'];
        for ($i = 0; $i <= 99; $i++) {
            $endDate = new DateTime(sprintf(User::ADD_MONTHS,  $faker->numberBetween(1, 4)));
            $prestation = (new Prestation())
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setUserMember($users[$faker->numberBetween(0, 9)])
                ->setDiscount($discounts[$faker->numberBetween(0, 9)])
                ->setGreenSpace($greenSpaces[$faker->numberBetween(0, 99)])
                ->setType($faker->randomElement($greenSpaceTypes))
            ;

            $manager->persist($prestation);
        }

        $manager->flush();
    }
}
