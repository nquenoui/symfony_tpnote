<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++)
        {
            $product = new Product();

            $product->setName($faker->name())
                    ->setPrice($faker->randomNumber(2))
                    ->setDescription($faker->text(500))
                    ->setCreatedAt($faker->dateTimeThisYear());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
