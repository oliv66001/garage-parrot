<?php

namespace App\Controller;

use App\Repository\BusinesshoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TwigController extends AbstractController
{
    public function getBusinesshours(BusinesshoursRepository $businessHoursRepository)
    {
        $businessHours = $businessHoursRepository->findAll();

        return $businessHours;
    }
}
