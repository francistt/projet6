<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for($i = 1; $i <= 20; $i++) {
            $ad = new Ad();

            $type = $faker->word();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $name = $faker->firstNameMale();
            $city = $faker->city();

            $ad->setType($type)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setAge(mt_rand(1, 20))
                ->setName($name)
                ->setSexe(mt_rand(1, 2))
                ->setCity($city)
                ->setSize(mt_rand(1, 3));

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
