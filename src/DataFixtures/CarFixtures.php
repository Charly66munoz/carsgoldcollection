<?php

namespace App\DataFixtures;

use App\Enum\Brand;
use App\Entity\Car;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\DataFixtures\Default\Core\UserFixture;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();             
        $users=[
            $this->getReference('user0', User::class),
            $this->getReference('user1', User::class),
            $this->getReference('user2', User::class),
        ];   
        for($i = 0; $i < 10 ; $i++){
            $car = new Car();
            $car-> setBrand($faker->randomElement(Brand::class));
            $car-> setModel($faker->word());
            $car-> setDescription($faker->text());
            $car-> setPhoto($car->getBrand()->value.".jpg");
            $car-> setPrice($faker->randomFloat(2,100000,1000000));
            $car->setYear($faker->numberBetween(2000, 2024));
            $car->setOwner($faker->randomElement($users));
            
            $manager->persist($car);
        }       
        $manager->flush();
    }
}
