<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\AdLike;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Discount;
use App\Entity\GreenSpace;
use App\Entity\Image;
use App\Entity\Partner;
use App\Entity\Prestation;
use App\Entity\Role;
use App\Entity\StructureType;
use App\Entity\TrainingStructure;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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

        $adminUser = new User();
        $adminUser->setFirstName('Gabriel')
            ->setLastName('Notong')
            ->setEmail('gabs@gmail.com')
            ->setPicture('https://randomuser.me/api/portraits/men/53.jpg')
            ->setIntroduction($faker->sentence)
            ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(2)) . '</p>')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($roles[1]);
        $manager->persist($adminUser);

        $startDate = new DateTime('now');
        // Manage fake members
        for ($i = 0; $i <= 9; $i++) {
            $user = new User();

            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($gender == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $endDate = new DateTime('+' .($faker->numberBetween(6, 12)) .' months');
            $user->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence)
                ->setDescription('<p>' . join('<p></p>', $faker->paragraphs(2)) . '</p>')
                ->setPicture($picture)
                ->setHash($hash)
                ->addUserRole($roles[0])
                ->setQrCode($faker->isbn13)
                ->setPhoneNumber($faker->phoneNumber)
                ->setStartsAt($startDate)
                ->setEndsAt($endDate)
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
                ->setAddress($faker->address)
            ;

            $partners[] = $partner;
            $manager->persist($partner);
        }

        // Manage fake discounts
        $discounts = [];
        for ($i = 0; $i <= 9; $i++) {
            $endDate = new DateTime('+' .($faker->numberBetween(1, 3)) .' months');
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
        $types = ['School', 'University', 'Center', 'Parc'];
        $structureTypes = [];
        for ($i = 0; $i <= 3; $i++) {
            $structureType = (new StructureType())
                ->setName($types[$i])
                ->setDescription($types[$i] . ' ' . $faker->city)
            ;

            $structureTypes[] = $structureType;
            $manager->persist($structureType);
        }

        // Manage fake training structures
        $trainingStructures = [];
        for ($i = 0; $i <= 9; $i++) {
            $structure = (new TrainingStructure())
                ->setName($faker->company)
                ->setAddress($faker->address)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setType($structureTypes[$faker->numberBetween(0, 3)])
            ;

            $trainingStructures[] = $structure;
            $manager->persist($structure);
        }


        // Manage fake green spaces
        $greenSpaces = [];
        for ($i = 0; $i <= 9; $i++) {
            $greenSpace = (new GreenSpace())
                ->setName($faker->city)
                ->setLatitude($faker->latitude)
                ->setLongitude($faker->longitude)
                ->setTrainingStructure($trainingStructures[$faker->numberBetween(0, 9)])
            ;

            $greenSpaces[] = $greenSpace;
            $manager->persist($greenSpace);
        }

        // Manage fake prestations
        $greenSpaceTypes = ['Maintenance', 'Installation'];
        for ($i = 0; $i <= 9; $i++) {
            $endDate = new DateTime('+' .($faker->numberBetween(1, 4)) .' weeks');
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


        // Manage Fake Ads
        for ($i = 0; $i < 30; $i++) {
            $ad = new Ad();
            $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';
            $title = $faker->sentence(2);

            $user = $users[mt_rand(0, count($users) - 1)];

            $picture = 'https://picsum.photos/id/';
            $pictureId = $faker->numberBetween(1, 500) . '/600/300.jpg';
            $imageApiUrl = $picture . $pictureId;

            $ad->setTitle($title)
                ->setCoverImage($imageApiUrl)
                ->setIntroduction($faker->paragraph(2))
                ->setContent($content)
                ->setRooms(mt_rand(1, 5))
                ->setPrice(mt_rand(25, 99))
                ->setAuthor($user);

            // manage Ad's Images
            for ($k = 0; $k <= mt_rand(2, 5); $k++) {
                $image = new Image();

                $pictureId = $faker->numberBetween(1, 500) . '/600/300.jpg';
                $imageApiUrl = $picture . $pictureId;

                $image->setUrl($imageApiUrl)
                    ->setCaption($faker->sentence(2))
                    ->setAd($ad);

                $manager->persist($image);
            }

            // manage Ad's Booking
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                // number of nights
                $duration = mt_rand(1, 5);

                // clone is used here in order to not modify startDate. So, a copy is used instead
                $endDate = (clone $startDate)->modify("+{$duration} days");

                $booker = $users[mt_rand(0, count($users) - 1)];

                $amount = number_format(($ad->getPrice() ? $ad->getPrice() : 0) * $duration, 2);

                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setCreatedAt($createdAt)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setAmount((float)$amount)
                    ->setComment($comment);

                $manager->persist($booking);

                // manage comments, some ads may not have a comment
                if (mt_rand(0, 1)) {
                    $comment = new Comment();
                    $comment->setAuthor($booker)
                        ->setRating(mt_rand(0, 5))
                        ->setAd($ad)
                        ->setContent('<p>' . join('<p></p>', $faker->paragraphs(mt_rand(1, 3))) . '</p>');

                    $manager->persist($comment);
                }

                // manage likes
                if (mt_rand(0,1)) {
                    $like = new AdLike();
                    $like->setUser($booker)
                        ->setAd($ad);

                    $manager->persist($like);
                }
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
