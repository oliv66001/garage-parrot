<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    private array $categories = [
        'Camion',
        'Voiture',
        'Berline',
        'Moto',
        'voiture sans permis',
        'Scooter',
        'Quad',
        'Remorque',
        'Utilitaire',
        'Caravane',
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->categories as $key => $categoryName) {
            $category = new Categorie();
            $category->setName($categoryName);
            $category->setSlug($categoryName);
            $manager->persist($category);

            // Store reference for further use
            $this->addReference('categorie_' . $key, $category);
        }

        $manager->flush();
    }
}
