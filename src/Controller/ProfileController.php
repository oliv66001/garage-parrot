<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profil_index')]
    public function index(BusinesshoursRepository $businessHoursRepository, UserRepository $userRepository): Response
    {
        $businessHours = $businessHoursRepository->findAll();
        $user = $userRepository->findAll();
        return $this->render('profile/index.html.twig', [
            'business_hours' => $businessHours,
            'user' => $user,
        ]);
    }
}
