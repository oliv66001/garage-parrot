<?php

namespace App\Controller\Admin;

use App\Entity\BusinessHours;
use App\Form\BusinessHoursType;
use App\Form\BusinessHoursFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route("/admin/business-hours", name: "admin_business_hours_")]
class BusinessHoursController extends AbstractController
{
    private $BusinesshoursRepository;

    private $entityManager;

    public function __construct(BusinesshoursRepository $BusinesshoursRepository, EntityManagerInterface $entityManager)
    {
        $this->BusinesshoursRepository = $BusinesshoursRepository;
        $this->entityManager = $entityManager;
    }

    #[Route("/", name: "index")]
    public function index(): Response
    {
        // Check if user has ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Seuls les administrateurs peuvent accéder à cette page.');
        }

        $day = $this->BusinesshoursRepository->findAllOrderedByDay();
        $hours = $this->BusinesshoursRepository->findAll();
        return $this->render('admin/business_hours/index.html.twig',  [
            'day' => $day,
            'hours' => $hours,
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Request $request, int $id): Response
    {
        // Check if user has ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Only admins can edit business hours.');
        }

        $hours = $this->BusinesshoursRepository->find($id);

        if (!$hours) {
            throw $this->createNotFoundException('Les heures d\'ouverture demandées n\'existent pas.');
        }

        $form = $this->createForm(BusinessHoursFormType::class, $hours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Les heures d\'ouverture ont bien été modifiées.');
            return $this->redirectToRoute('admin_business_hours_index');
        }

        return $this->render('admin/business_hours/edit.html.twig', [
            'hours' => $hours,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/footer-data", name: "footer_data")]
    public function footerData(BusinesshoursRepository $BusinesshoursRepository)
    {
        $openingHours = $BusinesshoursRepository->findAll();

        return $this->render('_partials/_footer.html.twig', [
            'opening_hours' => $openingHours,
        ]);
    }
}
