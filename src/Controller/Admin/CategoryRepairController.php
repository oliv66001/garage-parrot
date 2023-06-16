<?php

namespace App\Controller\Admin;

use App\Entity\CategoryRepair;
use App\Form\CategoryRepairFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepairRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryRepairController extends AbstractController
{
    #[Route('/categories', name: 'categories_list')]
    public function list(CategoryRepairRepository $categoryRepairRepository): Response
    {
        $categories = $categoryRepairRepository->findAll();
    
        return $this->render('categories/list.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/admin/category_repair', name: 'admin_category_repair_index')]
    #[IsGranted('ROLE_COLAB_ADMIN')]
    public function index(CategoryRepairRepository $categoryRepairRepository): Response
    {
        $categoryRepairs = $categoryRepairRepository->findAll();
        return $this->render('admin/category_repair/index.html.twig', [
            'categoryRepairs' => $categoryRepairs,
        ]);
    }
    

    #[Route('/admin/category_repair/add', name: 'admin_category_repair_add')]
    #[IsGranted('ROLE_COLAB_ADMIN')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $categoryRepair = new CategoryRepair();
        $form = $this->createForm(CategoryRepairFormType::class, $categoryRepair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categoryRepair);
            $em->flush();

            $this->addFlash('success', 'La nouvelle catégorie de réparation a été créée avec succès.');

            return new RedirectResponse($this->generateUrl('admin_category_repair_index'));
        }

        return $this->render('admin/category_repair/add.html.twig', [
            'categoryRepairForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/category_repair/{id}/edit', name: 'admin_category_repair_edit')]
    #[IsGranted('ROLE_COLAB_ADMIN')]
    public function edit(CategoryRepair $categoryRepair, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryRepairFormType::class, $categoryRepair);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categoryRepair);
            $em->flush();

            $this->addFlash('success', 'La catégorie de réparation a été modifiée avec succès.');

            return new RedirectResponse($this->generateUrl('admin_category_repair_index'));
        }

        return $this->render('admin/category_repair/edit.html.twig', [
            'categoryRepair' => $categoryRepair,
            'categoryRepairForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/category_repair/delete/{id}', name: 'admin_category_repair_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_COLAB_ADMIN')]
    public function delete(CategoryRepair $categoryRepair, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Avant de procéder à la suppression, vérifiez si la catégorie contient des réparations
        if (count($categoryRepair->getRepairs()) > 0) {
            // Si la catégorie contient des réparations, renvoyez une erreur
            return new JsonResponse(['error' => 'Cette catégorie contient des réparations et ne peut pas être supprimée'], 400);
        }
    
        if ($this->isCsrfTokenValid('delete_category' . $categoryRepair->getId(), $data['_token'])) {
            $em->remove($categoryRepair);
            $em->flush();
    
            $this->addFlash('success', 'Catégorie supprimée avec succès.');
    
            return new JsonResponse(['success' => true, 'message' => 'Catégorie supprimée avec succès'], 200);
        }
    
        // Échec de la suppression
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
