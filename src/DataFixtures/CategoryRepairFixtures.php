<?php

namespace App\DataFixtures;

use App\Entity\CategoryRepair;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryRepairFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger){}
    private array $category = [
         'Mécanique',
         'Carrosserie',
         'Entretien',
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->category as $key => $categoryName) {
            $category = new CategoryRepair();
            $category->setName($categoryName);
            $manager->persist($category);

            // Store reference for further use
            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();
    }
}
