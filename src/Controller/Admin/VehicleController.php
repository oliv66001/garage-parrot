<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Vehicle;
use App\Form\VehicleFormType;
use App\Service\PictureService;
use App\Repository\ContactRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/vehicle', name: 'admin_vehicle_')]
/**
 * Summary of VehicleController
 */
class VehicleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(VehicleRepository $vehicleRepository, BusinesshoursRepository $businessHoursRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN');
        $business_hours = $businessHoursRepository->findAllOrderedByDay();
        $vehicle = $vehicleRepository->findAll();
        return $this->render('admin/vehicle/index.html.twig', compact('vehicle', 'business_hours'));
    }

    #[Route('/ajout', name: 'add')]
    /**
     * Summary of add
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @param PictureService $pictureService
     * @return Response
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        PictureService $pictureService,
        BusinesshoursRepository $businessHoursRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN');
       
       
        $business_hours = $businessHoursRepository->findAllOrderedByDay();
        // Création d'un nouveau véhicule
        $vehicle = new Vehicle();

        // Création du formulaire
        $vehicleForm = $this->createForm(VehicleFormType::class, $vehicle);

        $vehicleForm->handleRequest($request);
       
        //Vérification du soumission du formulaire
        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {

            // Récuperation des images
            $images = $vehicleForm->get('images')->getData();
                
            foreach ($images as $image) {
                $folder = 'vehicle';


                // Generate a unique name for the file before saving it
                $fichier = $pictureService->add($image, $folder, 300, 300);
                
                $img = new Image();
                $img->setName($fichier);
                $vehicle->addImage($img);
               
                // Move the file to the directory where brochures are stored

            }

            $slug = $slugger->slug($vehicle->getBrand());
            $vehicle->setSlug($slug);
            $em->persist($vehicle);
            $em->flush();


            //Message flash
            $this->addFlash('success', 'Le véhicule a bien été ajouté');

            //Redirection vers la page de détails du véhicule
            return $this->redirectToRoute('admin_vehicle_index', ['slug' => $vehicle->getSlug()]);
        }

        return $this->render('admin/vehicle/add.html.twig', compact('vehicleForm', 'business_hours'));
    }


    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        PictureService $pictureService,
        BusinesshoursRepository $businessHoursRepository
    ): Response {
        //Vérification si l'user peut éditer avec le voter
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN', $vehicle);

        $business_hours = $businessHoursRepository->findAllOrderedByDay();
        // Création du formulaire
        $vehicleForm = $this->createForm(VehicleFormType::class, $vehicle);

        $vehicleForm->handleRequest($request);

        //Vérification du soumission du formulaire
        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
           
            // Récuperation des images
            $images = $vehicleForm->get('images')->getData();

            foreach ($images as $image) {
                $folder = 'vehicle';


                // Generate a unique name for the file before saving it
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $vehicle->addImage($img);
                // Move the file to the directory where brochures are stored

            }
            $slug = $slugger->slug($vehicle->getBrand());
            $vehicle->setSlug($slug);
            $em->persist($vehicle);
            $em->flush();


            //Message flash
            $this->addFlash('success', 'Le véhicule a bien été modifier');

            //Redirection vers la page de détails du véhicule
            return $this->redirectToRoute('admin_vehicle_index');
        }

        return $this->render('admin/vehicle/edit.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
            'vehicle' => $vehicle,
            'business_hours' => $business_hours,

        ]);
    }

    // Ajoutez l'annotation de la route en haut de votre méthode, en changeant le nom de la route et le chemin si nécessaire
    #[Route('/suppression/vehicle/{id}', name: 'delete_vehicle', methods: ['DELETE'])]
    public function deleteVehicle(
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $em,
        ContactRepository $contactRepository
    ): JsonResponse {
    
        $data = json_decode($request->getContent(), true);
    
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete_vehicle' . $vehicle->getId(), $data['_token'])) {
    
            // On récupère les contacts liés au véhicule
            $contacts = $contactRepository->findBy(['subject' => $vehicle]);
    
            // On supprime les contacts liés au véhicule
            foreach ($contacts as $contact) {
                $em->remove($contact);
            }
    
            // On supprime le véhicule de la base
            $em->remove($vehicle);
            $em->flush();
    
            $this->addFlash('success', 'Véhicule supprimé avec succès.');
    
    
            return new JsonResponse(['success' => true, 'message' => 'Véhicule supprimé avec succès'], 200);
        }
    
        // Echec de la suppréssion
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
        $this->denyAccessUnlessGranted('ROLE_COLAB_ADMIN', $image->getVehicle());

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