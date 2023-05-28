<?php

namespace App\Controller;

use App\Repository\BusinessHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TwigController extends AbstractController
{
    public function getBusinessHours(BusinessHoursRepository $businessHoursRepository)
    {
        $businessHours = $businessHoursRepository->findAll();

        return $businessHours;
    }
}
