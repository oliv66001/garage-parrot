<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class VehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $vehicle = new Vehicle();
            $vehicle->setBrand($faker->company);
            $vehicle->setSlug($faker->slug);
            $vehicle->setDescription($faker->text);
            $vehicle->setImage($faker->imageUrl());
            $vehicle->setKilometer($faker->numberBetween(0, 200000));
            $vehicle->setPrice($faker->numberBetween(10000, 50000));
            
            // Assign a random categorie
            $vehicle->setCategorie($this->getReference('categorie_' . rand(0, 9)));

            $manager->persist($vehicle);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
        ];
    }
}
