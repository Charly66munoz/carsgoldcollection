<?php
namespace App\DataFixtures\Default\Core;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
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
        $usersData = [
            ['nombre' => 'Boo', 'email' => 'bo@luxury.com'],
            ['nombre' => 'Uuxe', 'email' => 'ux@luxury.com'],
            ['nombre' => 'Bauti', 'email' => 'ba@luxury.com'],
        ];

        foreach ($usersData as $index => $data) {
            $user = new User();
            $user->setNombre($data['nombre']);
            $user->setEmail($data['email']);
            $user->setPassword($this->passWordHaser->hashPassword($user, '123456'));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $this->addReference('user'.$index, $user);
        }

        $manager->flush();
    }
}