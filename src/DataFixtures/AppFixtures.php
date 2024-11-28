<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

       $this->addWishes($manager);
    }

    public function addWishes(ObjectManager $manager, int $number = 50)
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $number; $i++) {

            $wish = new Wish();
            $wish
                ->setAuthor($faker->name())
                ->setDateCreated($faker->dateTimeBetween('-6 month'))
                ->setTitle($faker->sentence(2))
                ->setDescription($faker->paragraph())
                ->setPublished($faker->boolean(70));

            $manager->persist($wish);
        }

        $manager->flush();
    }


}
