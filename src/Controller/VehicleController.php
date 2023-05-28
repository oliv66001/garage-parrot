<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/véhicules', name: 'app_vehicle_')]
class VehicleController extends AbstractController
{
    #[Route('/', name: 'app_vehicle')]
    public function index(EntityManagerInterface $entityManager, BusinessHoursRepository $businessHoursRepository): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        $vehicles = $entityManager->getRepository(Vehicle::class)->findAll();
        $businessHours = $businessHoursRepository->findAll();
        return $this->render('vehicle/index.html.twig', [
            'categories' => $categories,
            'vehicles' => $vehicles,
            'business_hours' => $businessHours
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Vehicle $vehicle
    ): Response
    {
        return $this->render('vehicle/details.html.twig', compact('vehicle', 'business_hours'));
    }
}
