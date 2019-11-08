<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   

    private $encoder;
    /**
     * Init du encoder
     *
     * @param PasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $genres = ['male', 'female'];
        //Gere les utilisateur
        for ($u = 1; $u < 10; $u++) {
            $user = new User();

            $genre = $faker->randomElement(($genres));
            $picture = 'https://randomuser.me/api/portraits/';
            $pictId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' :  'women/') . $pictId;

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName())
                ->setPicture($picture)
                ->setEmail($faker->email())
                ->setIntro($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($this->encoder->encodePassword($user, 'azerty'));

                $manager->persist($user);
                $users[] = $user;
        }

        //Gere les annonces
        for ($i = 0; $i <= 30; $i++) {
            $ad = new Ad();

            $ad->setTitle($faker->sentence())
                ->setCoverImage($faker->imageUrl(1000, 350))
                ->setIntro($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($users[mt_rand(0, count($users) - 1)]);

                ///ajout image pour chaque annonce
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }
            

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
