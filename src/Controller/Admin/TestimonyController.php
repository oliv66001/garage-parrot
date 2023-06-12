<?php

namespace App\Controller\Admin;

use App\Entity\Testimony;
use App\Form\TestimonyType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_testimony_')]
#[IsGranted('ROLE_COLAB_ADMIN')]
class TestimonyController extends AbstractController
{
    #[Route('/admin/testimony', name: 'index')]
    public function index(TestimonyRepository $testimonyRepository): Response
    {
        $unvalidatedTestimonies = $testimonyRepository->findBy(['validation' => false]);
    
        return $this->render('admin/testimony/index.html.twig', [
            'unvalidated_testimonies' => $unvalidatedTestimonies,
        ]);
    }
    
    
    #[Route("/admin/testimony/{id}/validate", name: "validate", methods: ["POST"])]
    public function validate(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN');
        $testimony = $em->getRepository(Testimony::class)->find($id);
    
        if (!$testimony) {
            throw $this->createNotFoundException('No testimony found for id ' . $id);
        }
    
        $testimony->setValidation(true); // Set validation to true
        $em->flush();
    
        return $this->redirectToRoute('testimony_index');
    }    

}