<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Testimony;

class TestimonyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create 10 testimonies
        for ($i = 1; $i <= 10; $i++) {
            $testimony = new Testimony();
            $testimony->setName('Test User ' . $i);
            $testimony->setMessage('This is a fake message from user ' . $i . '.');
            $testimony->setCreatedAt(new \DateTimeImmutable('now'));
            $testimony->setValidation(false); // Set validation to false by default

            $manager->persist($testimony);
        }

        $manager->flush();
    }
}
