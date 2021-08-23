<?php

namespace App\DataFixtures;

use App\Domain\User\Repository\RoleRepository;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as FakerF;

class UserFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    const REFERENCE_NAME = 'user_';
    const DESIRED_NUMBER_OF_USERS = 30;

    private string $adminPassword;
    private string $adminEmail;
    private UserPasswordHasherInterface $passwordHasher;
    private RoleRepository $roleRepository;

    public function __construct(
        string $adminPassword,
        string $adminEmail,
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository $roleRepository
    ) {
        $this->adminPassword = $adminPassword;
        $this->adminEmail = $adminEmail;
        $this->passwordHasher = $passwordHasher;
        $this->roleRepository = $roleRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = FakerF::create();
        $roles = $this->roleRepository->findAll();
        $admin = (new User());
        $admin
            ->setLogin('admin')
            ->setPassword($this->passwordHasher->hashPassword($admin, $this->adminPassword))
            ->setEmail($this->adminEmail);
        array_map(function(Role $role) use ($admin) { $admin->addRole($role); }, $roles);
        $manager->persist($admin);

        for ($i = 0; $i < self::DESIRED_NUMBER_OF_USERS; $i ++) {
            $user = new User();
            $user->setLogin($faker->userName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword($user, 'test'));
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
