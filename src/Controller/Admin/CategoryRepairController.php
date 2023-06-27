<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Businesshours;
use App\Entity\CategoryRepair;
use App\Service\PictureService;
use App\Form\CategoryRepairFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use App\Repository\CategoryRepairRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryRepairController extends AbstractController
{
    #[Route('/categories', name: 'categories_list')]
    public function list(CategoryRepairRepository $categoryRepairRepository, BusinessHoursRepository $businessHoursRepository): Response
    {
        $categories = $categoryRepairRepository->findAll();
        $businessHours = $businessHoursRepository->findAll(); 
    
        return $this->render('categories/list.html.twig', [
            'categories' => $categories,
            'business_hours' => $businessHours,
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
    public function add(Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $categoryRepair = new CategoryRepair();
        $form = $this->createForm(CategoryRepairFormType::class, $categoryRepair);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            $folder = 'vehicle';
        
            // Generate a unique name for the file before saving it
            if ($imageFile) {
                $fichier = $pictureService->add($imageFile, $folder, 300, 300);
            
                $img = new Image();
                $img->setName($fichier);
                $categoryRepair->setImage($img);
            }
            
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
    public function edit(CategoryRepair $categoryRepair, Request $request, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        $form = $this->createForm(CategoryRepairFormType::class, $categoryRepair);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $folder = 'vehicle';
    
            if ($imageFile) {
                // Generate a unique name for the file before saving it
                $fichier = $pictureService->add($imageFile, $folder, 300, 300);
            
                // if there is an old image, remove it
                if ($categoryRepair->getImage()) {
                    $oldImg = $categoryRepair->getImage();
                    $em->remove($oldImg);
                }
    
                $img = new Image();
                $img->setName($fichier);
                $categoryRepair->setImage($img);
            }
        
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
    

    #[Route('/admin/categorie-réparation/delete/{id}', name: 'admin_category_repair_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_COLAB_ADMIN')]
    public function delete(CategoryRepair $categoryRepair, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        
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

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(
        Image $image,
        Request $request,
        EntityManagerInterface $em,
        PictureService $pictureService
    ): JsonResponse {
        //Vérification si l'user peut supprimer avec le voter
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN', $image->getCategoryRepair());

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();

            // On supprime le fichier
            if ($pictureService->delete($nom, 'vehicle', 300, 300)) {

                // On supprime l'entrée de la base
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }

            // Echec de la suppréssion
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
