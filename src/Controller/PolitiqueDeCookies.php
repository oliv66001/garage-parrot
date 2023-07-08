<?php

namespace App\Controller;

use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PolitiqueDeCookies extends AbstractController
{
    
      #[Route("/politique-de-cookies", name:"politique_de_cookies")]
     
    public function index(BusinesshoursRepository $businessHoursRepository): Response
    {
        $business_hours = $businessHoursRepository->findAll();
        return $this->render('politique_de_cookies/index.html.twig', [
            'controller_name' => 'PolitiqueDeCookies',
            'business_hours' => $business_hours,
        ]);
    }
}