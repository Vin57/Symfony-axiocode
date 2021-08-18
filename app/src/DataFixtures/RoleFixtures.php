<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements FixtureGroupInterface
{
    const REFERENCE_NAME = 'role_';

    public function load(ObjectManager $manager)
    {
        $role_admin = (new Role())->setCode('ROLE_ADMIN')->setLabel('admin');
        $role_user = (new Role())->setCode('ROLE_USER')->setLabel('user');
        $manager->persist($role_admin);
        $manager->persist($role_user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
