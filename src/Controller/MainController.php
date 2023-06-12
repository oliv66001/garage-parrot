<?php

namespace App\Controller;

use App\Entity\Testimony;
use App\Form\TestimonyFormType;
use App\Repository\TestimonyRepository;
use App\Repository\BusinessHoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_main')]
    public function index(BusinessHoursRepository $businessHoursRepository, TestimonyRepository $testimonyRepository, Request $request): Response
    {

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

        $imageLeftUrl = $this->getLeftImageUrl();
        $imageRightUrl = $this->getRightImageUrl();

        return $this->render('main/index.html.twig', [
            'business_hours' => $businessHours,
            'testimony' => $testimony,
            'form' => $form->createView(),
            'image_left_url' => $imageLeftUrl, // Ajoutez ceci
            'image_right_url' => $imageRightUrl, // et ceci
        ]);
    }
}
