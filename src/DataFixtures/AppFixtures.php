<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Discount;
use App\Entity\GreenSpace;
use App\Entity\Partner;
use App\Entity\Prestation;
use App\Entity\Role;
use App\Entity\CenterType;
use App\Entity\TrainingCenter;
use App\Entity\User;
use App\Entity\UserLambda;
use App\Service\QRCodeGenerator;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    private QRCodeGenerator $qrCodeGenerator;

    public function __construct(UserPasswordEncoderInterface $encoder, QRCodeGenerator $qrCodeGenerator)
    {
        $this->encoder = $encoder;
        $this->qrCodeGenerator = $qrCodeGenerator;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void 
    {
        $faker = Factory::create();
        $users = [];
        $genders = ['male', 'female'];
        $roleNames = ['ROLE_MEMBER', 'ROLE_ADMIN'];

        $roles = [];
        for ($i = 0; $i<count($roleNames); $i++) {
            $role = (new Role())->setName($roleNames[$i]);

            $roles[] = $role;
            $manager->persist($role);
        }

        $adminUser = new UserLambda();
        $adminUser->setFirstName('Gabriel')
            ->setLastName('Notong')
            ->setEmail('gabs@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($roles[1]);
        $manager->persist($adminUser);

        $startDate = new DateTime('now');
        // Manage fake members
        for ($i = 0; $i <= 9; $i++) {
            $user = new UserLambda();

            $gender = $faker->randomElement($genders);

            $hash = $this->encoder->encodePassword($user, 'password');

            $endDate = new DateTime(sprintf(User::ADD_MONTHS,  $faker->numberBetween(6, 12)));
            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->addUserRole($roles[0])
                ->setPhoneNumber($faker->e164PhoneNumber)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
            ;

            $qrCode = $this->qrCodeGenerator->forUserLambda($user);
            $user->setQrCode($qrCode);

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
            $endDate = new DateTime(sprintf(User::ADD_MONTHS,  $faker->numberBetween(1, 4)));
            $tc
                ->setEmail($faker->email)
                ->setHash($this->encoder->encodePassword($tc, 'password'))
                ->setCompanyName($faker->company)
                ->setStreetNumber($faker->numberBetween(1, 255))
                ->setStreetName($faker->streetName)
                ->setZipCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setPhoneNumber($faker->e164PhoneNumber)
                ->setCenterType($tcTypes[$faker->numberBetween(0, 3)])
            ;
            $qrCode = $this->qrCodeGenerator->forTrainingCenter($tc);
            $tc->setQrCode($qrCode);

            $trainingCenters[] = $tc;
            $manager->persist($tc);
        }


        // Manage fake green spaces
        $greenSpaces = [];
        for ($i = 0; $i <= 9; $i++) {
            $greenSpace = (new GreenSpace())
                ->setName($faker->city)
                ->setStreetNumber($faker->numberBetween(1, 255))
                ->setStreetName($faker->streetName)
                ->setZipCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setLatitude($faker->latitude)
                ->setLongitude($faker->longitude)
                ->setTrainingCenter($trainingCenters[$faker->numberBetween(0, 9)])
            ;

            $greenSpaces[] = $greenSpace;
            $manager->persist($greenSpace);
        }

        // Manage fake prestations
        $greenSpaceTypes = ['Maintenance', 'Installation'];
        for ($i = 0; $i <= 9; $i++) {
            $endDate = new DateTime(sprintf(User::ADD_MONTHS,  $faker->numberBetween(1, 4)));
            $prestation = (new Prestation())
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
                ->setUserMember($users[$faker->numberBetween(0, 9)])
                ->setDiscount($discounts[$faker->numberBetween(0, 9)])
                ->setGreenSpace($greenSpaces[$faker->numberBetween(0, 9)])
                ->setType($faker->randomElement($greenSpaceTypes))
            ;

            $manager->persist($prestation);
        }

        $manager->flush();
    }
}
