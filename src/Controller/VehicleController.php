<?php

namespace App\Controller;

use DateTime;

use App\Entity\Contact;
use App\Entity\Vehicle;
use App\Entity\Categorie;
use Psr\Log\LoggerInterface;
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
        EntityManagerInterface $entityManager,
        Request $request,
        int $page = 1
    ): Response {
        // Fetch categories from the database
        $categories = $categorieRepository->findAll();
        $currentYear = (int)date('Y');
        $date = new DateTime(date('Y-m-d'));
        $businessHours = $businessHoursRepository->findAll();
        $this->logger->info('Searching vehicles');
        // Fetch search parameters
        $categoryName = $request->query->get('category');
        $price = $request->query->get('price');
        $year = $request->query->get('year');
        $kilometer = $request->query->get('kilometer');
    
        // Fetch vehicles from the database based on search parameters
        $vehicles = $vehicleRepository->searchByYear($year, $categoryName, $price, $kilometer);
    
        // Get only vehicles that should be displayed on the homepage
        //$displayOnHomePage = true;
        //$vehiclesForHomePage = $vehicleRepository->findByDisplayOnHomePage($displayOnHomePage);
    
        // Check if no vehicles found and render the main index page
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
                $response[$categoryName][] = [
                    'brand' => $vehicle->getBrand(),
                    'description' => $vehicle->getDescription(),
                    'price' => $vehicle->getPrice(),
                    'image' => $vehicle->getImage(),
                    'slug' => $vehicle->getSlug(),
                    'id' => $vehicle->getId(),
                    'year' => $vehicle->getYear(),
                    'kilometer' => $vehicle->getKilometer(),
                ];
            }
    
            return new JsonResponse($response);
        }
    
        // Render the main index page
        return $this->render('vehicle/index.html.twig', [
            'categories' => $categories,
            'vehicles' => $vehicles,
            //'vehiclesForHomePage' => $vehiclesForHomePage,
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

}
