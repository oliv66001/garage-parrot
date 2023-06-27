<?php

namespace App\Controller\Admin;

use App\Entity\Testimony;
use App\Form\TestimonyType;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_testimony_')]
#[IsGranted('ROLE_COLAB_ADMIN')]
class TestimonyController extends AbstractController
{
    #[Route('/testimony', name: 'index')]
    public function index(TestimonyRepository $testimonyRepository): Response
    {
        $unvalidatedTestimonies = $testimonyRepository->findBy(['validation' => false]);
    
        
        return $this->render('admin/testimony/index.html.twig', [
            'unvalidated_testimonies' => $unvalidatedTestimonies,
        ]);
    }
    
    
    #[Route("/testimony/{id}/validate", name: "validate")]
    public function validate(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN');
        $testimony = $em->getRepository(Testimony::class)->find($id);
    
        if (!$testimony) {
            throw $this->createNotFoundException('No testimony found for id ' . $id);
            
        }
        $this->addFlash('success', 'Le témoignage a bien été validé');
        $testimony->setValidation(true);
        $em->flush();
    
        return $this->redirectToRoute('admin_testimony_index');
    }    

    #[Route("/testimony/{id}/delete", name: "delete", methods: ['DELETE'])]

    public function testimonyDelete(
        Testimony $testimony,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);
    
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete_testimony' . $testimony->getId(), $data['_token'])) {
            // On supprime le témoignage de la base
            try {
                $em->remove($testimony);
                $em->flush();
    
                $this->addFlash('success', 'Message supprimé avec succès.');
    
                return new JsonResponse(['success' => true, 'message' => 'Message supprimé avec succès'], 200);
            } catch (\Exception $e) {
                // S'il y a une exception, renvoyez une réponse JSON avec l'erreur
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }
        } else {
            // Échec de la suppression
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }
}    