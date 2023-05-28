<?php

namespace App\Controller;

use App\Repository\BusinessHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(BusinessHoursRepository $businessHoursRepository): Response
    {
        $businessHours = $businessHoursRepository->findAll();

        return $this->render('main/index.html.twig', [
            'business_hours' => $businessHours
        ]);
    }
}
