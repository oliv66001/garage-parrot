<?php

namespace App\Controller;

use App\Repository\BusinessHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profil_index')]
    public function index(BusinessHoursRepository $businessHoursRepository): Response
    {
        $businessHours = $businessHoursRepository->findAll();
        return $this->render('profile/index.html.twig', [
            'business_hours' => $businessHours
        ]);
    }
}
