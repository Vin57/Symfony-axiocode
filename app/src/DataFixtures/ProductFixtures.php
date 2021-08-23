<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ProductBuilder;
use App\Domain\Category\Repository\CategoryRepository;
use App\Domain\User\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerF;

class ProductFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    const DESIRED_NUMBER_OF_PRODUCTS = 50;
    const NUMBER_OF_PICTURES_BY_PRODUCT = 15;
    private ProductBuilder $productBuilder;
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;

    public function __construct(ProductBuilder $productBuilder, CategoryRepository $categoryRepository, UserRepository $userRepository) {
        $this->productBuilder = $productBuilder;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = FakerF::create();
        $categories = $this->categoryRepository->findAll();
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < self::DESIRED_NUMBER_OF_PRODUCTS; $i++) {
            $manager->persist(
                $this->productBuilder
                    ->withOpinions($faker->numberBetween(0, UserFixtures::DESIRED_NUMBER_OF_USERS / 4 - 1))
                    ->withPictures($faker->numberBetween(0, self::NUMBER_OF_PICTURES_BY_PRODUCT))
                    ->build($categories, $users)
            );
            if ($i % 30 === 0) {
                $manager->flush();
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
