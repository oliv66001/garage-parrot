<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Repair;
use App\Entity\CategoryRepair;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class RepairFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $repair = new Repair();
            $repair->setName($faker->company);
            
            $repair->setDescription($faker->text);
            
            $repair->setPrice($faker->numberBetween(30, 6000));
            
            $categoryReference = 'category_' . rand(0, 2);
            $repair->setCategory($this->getReference($categoryReference));

            $manager->persist($repair);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryRepairFixtures::class,
        ];
    }
}
