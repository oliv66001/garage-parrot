<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/categorie', name: 'admin_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categorie = $categorieRepository->findBy([], ['id' => 'DESC']);
        return $this->render('admin/categorie/index.html.twig',[
            'categorie' => $categorie,
        ]
        );
    }

    #[Route('/ajouter', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, CategorieFormType $category, CategorieRepository $categorieRepository): Response
    {
        $category = new Categorie();
        $categorie = $categorieRepository->findBy([], ['id' => 'DESC']);
        $form = $this->createForm(CategorieFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès.');

            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/add.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route("/categorie/edit/{id}", name:"edit", methods:["GET", "POST"])]

    public function edit(Request $request, Categorie $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Catégorie mise à jour avec succès.');

            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/edit.html.twig', [
            'category' => $category,
            'categoryForm' => $form->createView(),
        ]);
    }
   
    
 #[Route("/categorie/delete/{id}", name:"delete_categorie", methods:["DELETE"])]
 
public function delete(Request $request, Categorie $category, EntityManagerInterface $em): Response
{
    $data = json_decode($request->getContent(), true);

    if ($this->isCsrfTokenValid('delete_categorie' . $category->getId(), $data['_token'])) {
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès.');

        return new JsonResponse(['success' => true, 'message' => 'Catégorie supprimée avec succès'], 200);
    }

    // Échec de la suppression
    return new JsonResponse(['error' => 'Token invalide'], 400);
}

}