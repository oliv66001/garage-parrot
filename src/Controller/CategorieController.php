<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categorie', name: 'app_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function index(Categorie $categorie, Request $request, EntityManagerInterface $entityManager, BusinessHoursRepository $businessHoursRepository): Response
    {

        $business_hours = $businessHoursRepository->findAll();
        $category = $entityManager->getRepository(Categories::class)->findAll();
        $vehicles = $entityManager->getRepository(Vehicle::class)->findAll();
        return $this->render('categorie/index.html.twig', [
            'category' => $category,
            'vehicles' => $vehicles,
            'business_hours' => $business_hours
        ]);
    }
}
