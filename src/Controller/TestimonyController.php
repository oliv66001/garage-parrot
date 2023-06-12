<?php

namespace App\Controller;

use App\Entity\Testimony;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestimonyController extends AbstractController
{
    #[Route('/testimony', name: 'app_testimony', methods: ["GET", "POST"])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $testimony = new Testimony();
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testimony->setCreatedAt(new \DateTimeImmutable('now'));
            $testimony->setValidation(false); // Set validation to false by default
            $em->persist($testimony);
            $em->flush();
        
            return $this->redirectToRoute('testimony_index');
        }
        

        return $this->render('testimony/new.html.twig', [
            'testimony' => $testimony,
            'form' => $form->createView(),
        ]);
    }

 
    #[Route('/all-testimonies', name: 'all_testimonies')]
    public function allTestimonies(TestimonyRepository $testimonyRepository, BusinessHoursRepository $businessHours): Response
    {
        $businessHours = $businessHours->findAll();
        $allTestimonies = $testimonyRepository->findBy(['validation' => true], ['createdAt' => 'DESC']);

        return $this->render('testimony/all_testimonies.html.twig', [
            'all_testimonies' => $allTestimonies,
            'business_hours' => $businessHours,
        ]);
    }
}
