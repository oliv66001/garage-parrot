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
            $vehicle->setImage($faker->imageUrl(700, 400, 'vehicle', true));
            $vehicle->setKilometer($faker->numberBetween(0, 200000));
            $vehicle->setYear($faker->dateTimeBetween('01-01-1980', '31-12-2022'));
            $vehicle->setPrice($faker->numberBetween(10000, 50000));
            $vehicle->setDisplayOnHomePage(rand(1, 0));
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
