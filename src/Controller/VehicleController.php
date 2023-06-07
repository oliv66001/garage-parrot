<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Vehicle;
use App\Entity\Categorie;
use Psr\Log\LoggerInterface;
use App\Repository\VehicleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinessHoursRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        BusinessHoursRepository $businessHoursRepository,
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
        // Assuming a custom method `searchByYear()` in your repository

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
        BusinessHoursRepository $businessHoursRepository,
        VehicleRepository $vehicleRepository,
        int $page = 1
    ): Response {
        $vehicles = $vehicleRepository->findByCategory($category, $page, 6);

        $businessHours = $businessHoursRepository->findAll();

        return $this->render('vehicle/category.html.twig', [
            'category' => $category,
            'vehicles' => $vehicles,
            'business_hours' => $businessHours,
        ]);
    }

    #[Route('/detail/{slug}', name: 'detail')]
    public function detail(Vehicle $vehicle, BusinessHoursRepository $businessHoursRepository, VehicleRepository $vehicleRepository): Response
    {
        $businessHours = $businessHoursRepository->findAll();
        $year = new DateTime();
        $formattedYear = $year->format('Y-m-d');

        return $this->render('vehicle/detail.html.twig', [
            'vehicles' => $vehicle,
            'business_hours' => $businessHours,
            'year' => $formattedYear,
        ]);
    }
}
