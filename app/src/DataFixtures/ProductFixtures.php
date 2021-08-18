<?php

namespace App\DataFixtures;

use App\Domain\Product\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerF;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const DESIRED_NUMBER_OF_PRODUCTS = 100;

    public function load(ObjectManager $manager)
    {
        $faker = FakerF::create();

        for ($i = 0; $i < self::DESIRED_NUMBER_OF_PRODUCTS; $i++) {
            $product = new Product();
            $product->setName(implode(" ", $faker->unique->words(2)))
                ->setCategory($this->getReference("category_" . $faker->numberBetween(0, CategoryFixtures::DESIRED_NUMBER_OF_CATEGORY - 1)));
            $manager->persist($product);
            if ($i % 30 === 0) {
                $manager->flush();
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }
}
