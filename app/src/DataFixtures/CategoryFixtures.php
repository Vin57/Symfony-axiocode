<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerF;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    const DESIRED_NUMBER_OF_CATEGORY = 20;
    const REFERENCE_NAME = 'category_';

    public function load(ObjectManager $manager)
    {
        $faker = FakerF::create();
        for ($i = 0; $i < self::DESIRED_NUMBER_OF_CATEGORY; $i ++) {
            $category = new Category();
            $category->setName(implode(" ", $faker->unique->words(2)));
            $manager->persist($category);
            $this->addReference("category_" . $i, $category);
        }
        $manager->flush();
        // Set parents to some children
        $parents = [];
        for ($i = 0; $i < self::DESIRED_NUMBER_OF_CATEGORY / 2; $i ++) {
            /** @var Category $parent */
            $parent = $this->getReference(self::REFERENCE_NAME . $faker->unique()->numberBetween(0, self::DESIRED_NUMBER_OF_CATEGORY / 2 - 1));
            /** @var Category $child */
            $child = $this->getReference(self::REFERENCE_NAME . $faker->unique()->numberBetween( self::DESIRED_NUMBER_OF_CATEGORY / 2, self::DESIRED_NUMBER_OF_CATEGORY -1));
            $manager->persist($child->setParent($parent));
            $parents[] = $parent;
        }
        $manager->flush();
        // Give to 1/3 parents some parents, so we get parent of parent
        $parents1 = array_slice($parents, 0, count($parents) / 2);
        $grandParents = array_slice($parents, count($parents) / 2);
        /** @var Category $p */
        foreach ($parents1 as $k => $p) {
            $manager->persist($p->setParent($grandParents[$k]));
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
