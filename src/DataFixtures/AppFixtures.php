<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $password = $this->encoder->encodePassword($user, 'password');
            
            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setPassword($password)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for($i = 1; $i <= 20; $i++) {
            $ad = new Ad();

            $type = $faker->word();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $name = $faker->firstNameMale();
            $city = $faker->city();

            $user = $users[mt_rand(0, count($users) -1)];

            $ad->setType($type)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setAge(mt_rand(1, 20))
                ->setName($name)
                ->setSexe(mt_rand(1, 2))
                ->setCity($city)
                ->setSize(mt_rand(1, 3))
                ->setAuthor($user);;

                for($j = 1; $j <= mt_rand(2, 5); $j++) {
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
