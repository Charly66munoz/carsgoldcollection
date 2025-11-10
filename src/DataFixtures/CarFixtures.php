<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Enum\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        for($i = 0; $i < 10 ; $i++){
            $car = new Car();
            $car-> setBrand($faker->randomElement(Brand::class));
            $car-> setModel($faker->word());
            $car-> setDescription($faker->text());
            $car-> setPhoto($car->getBrand()->value.".jpg");
            $car-> setPrice($faker->randomFloat(2,100000,1000000));
            $car->setYear($faker->numberBetween(2000, 2024));


            $manager->persist($car);
        }
        
        $manager->flush();
        
    }
}
