<?php

namespace App\Controller\Admin;

use App\Entity\Repair;
use App\Entity\CategoryRepair;
use App\Repository\RepairRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use App\Repository\CategoryRepairRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepairController extends AbstractController
{
    #[Route('/reparation', name: 'repair_index')]
    public function index(RepairRepository $repairRepository, BusinessHoursRepository $businessHoursRepository, CategoryRepairRepository $categoryRepairRepository): Response
    {
        $categories = $categoryRepairRepository->findAll();
        $repairs = $repairRepository->findAll();
        $business_hours = $businessHoursRepository->findAllOrderedByDay();

        return $this->render('repair/index.html.twig', [
            'categories' => $categories,
            'repairs' => $repairs,
            'business_hours' => $business_hours,
        ]);
    }

    #[Route('/reparation/categories/{id}', name: 'repairs_by_category')]
    public function repairsByCategory(CategoryRepair $category, RepairRepository $repairRepository, BusinessHoursRepository $businessHoursRepository): Response
    {
        $business_hours = $businessHoursRepository->findAllOrderedByDay();
        $repairs = $repairRepository->findBy(['category' => $category]);

        return $this->render('repair/by_category.html.twig', [
            'business_hours' => $business_hours,
            'category' => $category,
            'repairs' => $repairs,
        ]);
    }


    #[Route('/admin/reparations/new', name: 'admin_repair_new')]
    #[IsGranted('ROLE_ADMIN')]

    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $repair = new Repair();
        $form = $this->createForm(RepairFormType::class, $repair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($repair);
            $em->flush();

            $this->addFlash('success', 'La nouvelle réparation a été créée avec succès.');

            return new RedirectResponse($this->generateUrl('admin_repair_index'));
        }

        return $this->render('admin/repair/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/reparations/{id}/edit', name: 'admin_repair_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Repair $repair, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RepairFormType::class, $repair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La réparation a été modifiée avec succès.');

            return new RedirectResponse($this->generateUrl('admin_repair_index'));
        }

        return $this->render('admin/repair/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/reparations/{id}/delete', name: 'admin_repair_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Repair $repair, EntityManagerInterface $em, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete_repair' . $repair->getId(), $data['_token'])) {


            // On supprime la reparation de la base
            $em->remove($repair);
            $em->flush();

            $this->addFlash('success', 'Reparation supprimé avec succès.');


            return new JsonResponse(['success' => true, 'message' => 'Reparation supprimé avec succès'], 200);
        }

        // Echec de la suppréssion
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
