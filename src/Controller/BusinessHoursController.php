<?php

namespace App\Controller;

use App\Repository\BusinessHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BusinessHoursController extends AbstractController
{
    private $businessHoursRepository;

    public function __construct(BusinessHoursRepository $businessHoursRepository)
    {
        $this->businessHoursRepository = $businessHoursRepository;
    }

    #[Route('/business/hours', name: 'app_business_hours')]
    public function index(): Response
    {
        $businessHours = $this->businessHoursRepository->findAllOrderedByDay();
        return $this->render('business_hours/index.html.twig', [
            'business_hours' => $businessHours,
        ]);
    }
}
