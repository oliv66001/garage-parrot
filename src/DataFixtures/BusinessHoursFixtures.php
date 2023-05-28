<?php

namespace App\DataFixtures;

use App\Entity\BusinessHours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BusinessHoursFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Vous pouvez ajuster ces données selon vos besoins
        $openingHoursData = [
            'Lundi' => ['08:30', '12:00', '14:00', '18:00'],
            'Mardi' => ['08:30', '12:00', '14:00', '18:00'],
            'Mercredi' => ['08:30', '12:00', '14:00', '18:00'],
            'Jeudi' => ['08:30', '12:00', '14:00', '18:00'],
            'Vendredi' => ['08:30', '12:00', '14:00', '18:00'],
            'Samedi' => ['08:30', '12:00', null, null],
            'Dimanche' => [null, null, null, null],
        ];

        foreach ($openingHoursData as $day => $times) {
            $businessHours = new BusinessHours();
            $businessHours->setDay($day);
            $businessHours->setOpenTimeMorning($times[0]);
            $businessHours->setClosedTimeMorning($times[1]);
            $businessHours->setOpenTimeAfternoon($times[2]);
            $businessHours->setClosedTimeAfternoon($times[3]);
            $manager->persist($businessHours);
        }

        $manager->flush();
    }
}