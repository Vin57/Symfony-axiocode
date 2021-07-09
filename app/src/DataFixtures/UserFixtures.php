<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as FakerF;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = FakerF::create();
        for ($i = 0; $i < 30; $i ++) {
            $user = new User();
            $user->setLogin($faker->userName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword($user, $faker->password(9, 30)));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
