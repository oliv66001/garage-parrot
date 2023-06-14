<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Testimony;
use App\Form\TestimonyFormType;
use App\Repository\VehicleRepository;
use App\Repository\CategorieRepository;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ImageRepository; // Ajoutez ceci
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    private EntityManagerInterface $em;
    private ImageRepository $imageRepository; 

    public function __construct(EntityManagerInterface $em, ImageRepository $imageRepository)
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository; 
    }

    #[Route('/', name: 'app_main')]
    public function index(BusinessHoursRepository $businessHoursRepository, TestimonyRepository $testimonyRepository, Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository): Response
    {
        $category = $entityManager->getRepository(Categorie::class)->findAll();
        $vehicle = $vehicleRepository->findAll();
        $testimonyEntity = new Testimony();
        $form = $this->createForm(TestimonyFormType::class, $testimonyEntity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $testimonyEntity->setValidation(false);
            $testimonyEntity->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($testimonyEntity);
            $this->em->flush();

            return $this->redirectToRoute('app_main');
        }


        $testimony = $testimonyRepository->findBy(['validation' => true], ['createdAt' => 'DESC'], 6);
        $businessHours = $businessHoursRepository->findAll(); 

        return $this->render('main/index.html.twig', [
            'business_hours' => $businessHours,
            'testimony' => $testimony,
            'vehicle' => $vehicle,
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }
}
