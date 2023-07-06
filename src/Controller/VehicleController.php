<?php

namespace App\Controller;

use DateTime;


use App\Entity\Contact;
use App\Entity\Vehicle;
use App\Entity\Categorie;
use Psr\Log\LoggerInterface;
use App\Repository\ImageRepository;
use App\Repository\VehicleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinesshoursRepository;
use App\Repository\VehicleOptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/véhicules', name: 'app_vehicle_')]
class VehicleController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(
        VehicleRepository $vehicleRepository,
        BusinesshoursRepository $businessHoursRepository,
        CategorieRepository $categorieRepository,
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        int $page = 1
    ): Response {
        // Fetch categories from the database
        $categories = $categorieRepository->findAll();
        $images = $imageRepository->findAll();
        $currentYear = (int)date('Y');
        $date = new DateTime(date('Y-m-d'));
        $businessHours = $businessHoursRepository->findAll();
        $this->logger->info('Searching vehicles');
        // Fetch search parameters
        $categoryName = $request->query->get('category');
        $image = $request->query->get('image');
        $price = $request->query->get('price');
        $year = $request->query->get('year');
        $kilometer = $request->query->get('kilometer');

        // Fetch vehicles from the database based on search parameters
        $vehicles = $vehicleRepository->searchByYear($year, $categoryName, $price, $kilometer);

        // Redirect to the main index if no vehicles are found
        if (empty($vehicles)) {
            return new RedirectResponse($this->generateUrl('app_vehicle_index'));
        }

        // For AJAX requests, respond with a JSON object of vehicles
        if ($request->isXmlHttpRequest()) {
            $response = [];
            foreach ($vehicles as $vehicle) {
                $categoryName = $vehicle->getCategorie() ? $vehicle->getCategorie()->getName() : 'N/A';
                if (!isset($response[$categoryName])) {
                    $response[$categoryName] = [];
                }

                $imageEntities = $vehicle->getImages();
                $images = [];
                foreach ($imageEntities as $image) {
                    $images[] = [
                        'id' => $image->getId(),
                        'name' => $image->getName(),
                    ];
                }
                $response[$categoryName][] = [
                    'brand' => $vehicle->getBrand(),
                    'description' => $vehicle->getDescription(),
                    'price' => $vehicle->getPrice(),
                    'image' => $vehicle->getImage(), 
                    'slug' => $vehicle->getSlug(),
                    'id' => $vehicle->getId(),
                    'year' => $vehicle->getYear(),
                    'kilometer' => $vehicle->getKilometer(),
                    'images' => $images, 
                ];
            }

            return new JsonResponse($response);
        }

        // Render the main index page
        return $this->render('vehicle/index.html.twig', [
            'categories' => $categories,
            'vehicles' => $vehicles,
            'images' => $images,
            'business_hours' => $businessHours,
            'currentYear' => $currentYear,
            'year' => $year,
            'date' => $date,
            'kilometer' => $kilometer,
        ]);
    }


    #[Route('/{categoryId}', name: 'category', requirements: ['categoryId' => '\d+'])]
    public function category(
        Categorie $category,
        BusinesshoursRepository $businessHoursRepository,
        VehicleRepository $vehicleRepository,
        int $page = 1
    ): Response {
        $vehicle = $vehicleRepository->findByCategory($category, $page, 6);

        $businessHours = $businessHoursRepository->findAll();

        return $this->render('vehicle/category.html.twig', [
            'category' => $category,
            'vehicle' => $vehicle,
            'business_hours' => $businessHours,
        ]);
    }

    #[Route('/vehicle/{slug}', name: 'detail')]
    public function detail(string $slug, VehicleRepository $vehicleRepository, Vehicle $vehicle, BusinesshoursRepository $businessHoursRepository): Response
    {

        $vehicle = $vehicleRepository->findOneBy(['slug' => $slug]);
        $businessHours = $businessHoursRepository->findAll();
        $year = new DateTime();
        $formattedYear = $year->format('Y-m-d');

        if (!$vehicle) {
            throw $this->createNotFoundException('Ce véhicule n\'existe pas.');
        }

        return $this->render('vehicle/detail.html.twig', [
            'vehicles' => $vehicle,
            'business_hours' => $businessHours,
            'year' => $formattedYear,
        ]);
    }

    #[Route('/vehicle/{slug}/contact', name: 'vehicle_contact')]
    public function contactVehicle(Vehicle $vehicle, Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $contact->setSubject($vehicle);

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            // Redirect or show a success message
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }

    #[Route('/vehicules/{id}/images', name: 'vehicle_images', methods: ['GET'])]
    public function images(Vehicle $vehicle, ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findBy(['vehicle' => $vehicle]);

        // Transforme les images en un format approprié pour la réponse JSON
        $imageData = array_map(function ($image) {
            return [
                'id' => $image->getId(),
                'name' => $image->getName(),
                // Ajoutez ici d'autres propriétés de l'image que vous souhaitez inclure
            ];
        }, $images);

        return $this->json($imageData);
    }
}
