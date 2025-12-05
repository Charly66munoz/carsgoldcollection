<?php

namespace App\DataFixtures\Default\Core;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passWordHaser,
    ){}

    public static function getGroups(): array
    {
        return [
            'demo',
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setNombre('Boo');
        $user->setEmail('boo@luxury.com');
        $user->setPassword($this->passWordHaser->hashPassword($user, '123456'));
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }


}