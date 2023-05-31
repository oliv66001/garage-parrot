<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger){}
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
            $category->setSlug($this->slugger->slug($category->getName())->lower());
            $manager->persist($category);

            // Store reference for further use
            $this->addReference('categorie_' . $key, $category);
        }

        $manager->flush();
    }
}
