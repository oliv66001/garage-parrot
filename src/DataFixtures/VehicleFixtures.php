<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Vehicle;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class VehicleFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $vehicle = new Vehicle();
            $vehicle->setBrand($faker->company);
            $vehicle->setSlug($this->slugger->slug($vehicle->getBrand())->lower());
            $vehicle->setDescription($faker->text);
            $vehicle->setImage($faker->imageUrl());
            $vehicle->setKilometer($faker->numberBetween(0, 200000));
            $vehicle->setYear($faker->dateTimeBetween('1980-01-01', '2022-12-31'));
            $vehicle->setPrice($faker->numberBetween(10000, 50000));
            $categoryReference = 'categorie_' . rand(0, 9);
            $vehicle->setCategorie($this->getReference($categoryReference));

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
