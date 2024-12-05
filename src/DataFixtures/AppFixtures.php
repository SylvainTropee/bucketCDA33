<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->addUsers($manager);
        $this->addCategories($manager);
        $this->addWishes($manager);
    }

    public function addUsers(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_USER'])
                ->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setPassword($this->hasher->hashPassword($user, '1234'));

            $manager->persist($user);
        }
        $manager->flush();
    }

    public function addCategories(ObjectManager $manager): void
    {

        $categories = ['Travel & Adventure', 'Sport', 'Entertainment', 'Human Relations', 'Others'];

        foreach ($categories as $cat) {

            $category = new Category();
            $category->setName($cat);
            $manager->persist($category);
        }
        $manager->flush();
    }

    public function addWishes(ObjectManager $manager, int $number = 50): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $number; $i++) {

            $wish = new Wish();
            $wish
                ->setUser($faker->randomElement($users))
                ->setDateCreated($faker->dateTimeBetween('-6 month'))
                ->setTitle($faker->sentence(2))
                ->setDescription($faker->paragraph())
                ->setCategory($faker->randomElement($categories));
//                ->setPublished($faker->boolean(70));

            $manager->persist($wish);
        }

        $manager->flush();
    }


}
