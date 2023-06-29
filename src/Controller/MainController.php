<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Testimony;
use App\Form\TestimonyFormType;
use App\Repository\ImageRepository;
use App\Repository\RepairRepository;
use App\Repository\VehicleRepository;
use App\Repository\CategorieRepository;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinesshoursRepository;
use App\Repository\CategoryRepairRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    private EntityManagerInterface $em;
    private ImageRepository $imageRepository;
    private ImageRepository $imageRepository;
    private RepairRepository $repairRepository;

    public function __construct(EntityManagerInterface $em, ImageRepository $imageRepository, RepairRepository $repairRepository)
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository;
        $this->imageRepository = $imageRepository;
        $this->repairRepository = $repairRepository;
    }

    #[Route('/', name: 'app_main')]
    public function index(BusinesshoursRepository $businessHoursRepository, TestimonyRepository $testimonyRepository, Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository, CategoryRepairRepository $categoryRepairRepository): Response
    {

        $categories = $categoryRepairRepository->findAll();
        $repairs = $this->repairRepository->findAll();
        $category = $entityManager->getRepository(Categorie::class)->findAll();
        $vehicle = $vehicleRepository->findAll();
        $testimonyEntity = new Testimony();
        $form = $this->createForm(TestimonyFormType::class, $testimonyEntity);
        $businessHours = $businessHoursRepository->findAll();

        $businessHours = $businessHoursRepository->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $testimonyEntity->setValidation(false);
            $testimonyEntity->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($testimonyEntity);
            $this->em->flush();

            return $this->redirectToRoute('app_main');
        }


        $testimony = $testimonyRepository->findBy(['validation' => true], ['createdAt' => 'DESC'], 6);



        return $this->render('main/index.html.twig', [
            'categories' => $categories,
            'repairs' => $repairs,
            'business_hours' => $businessHours,
            'testimony' => $testimony,
            'vehicles' => $vehicle,
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/accept-cookies", name: "accept_cookies", methods: ["POST"])]

    public function acceptCookies(Request $request): JsonResponse
    {
        $request->getSession()->set('cookies_accepted', true);

        return new JsonResponse(['status' => 'success']);
    }

    #[Route("/refuse-cookies", name: "refuse_cookies", methods: ["POST"])]

    public function refuseCookies(Request $request): JsonResponse
    {
        $request->getSession()->set('cookies_refused', true);

        return new JsonResponse(['status' => 'success']);
    }
}
