<?php

namespace App\Controller\Admin;

use App\Entity\Vehicle;
use App\Entity\Image;
use App\Form\VehicleFormType;
use App\Repository\VehicleRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/vehicle', name: 'admin_vehicle_')]
/**
 * Summary of VehicleController
 */
class VehicleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(VehicleRepository $vehicleRepository): Response
    {

        $vehicle = $vehicleRepository->findAll();
        return $this->render('admin/vehicle/index.html.twig', compact('vehicle'));
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
        PictureService $pictureService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        return $this->render('admin/vehicle/add.html.twig', compact('vehicleForm'));
    }


    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        PictureService $pictureService
    ): Response {
        //Vérification si l'user peut éditer avec le voter
        $this->denyAccessUnlessGranted('DISHE_EDIT', $vehicle);

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
            $this->addFlash('success', 'Le produit a bien été modifier');

            //Redirection vers la page de détails du produit
            return $this->redirectToRoute('admin_vehicle_index');
        }

        return $this->render('admin/vehicle/edit.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
            'vehicle' => $vehicle

        ]);
    }

    // Ajoutez l'annotation de la route en haut de votre méthode, en changeant le nom de la route et le chemin si nécessaire
    #[Route('/suppression/vehicle/{id}', name: 'delete_dishe', methods: ['DELETE'])]
    public function deleteDishe(
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
       

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete_dishe' . $vehicle->getId(), $data['_token'])) {


            // On supprime le produit de la base
            $em->remove($vehicle);
            $em->flush();

            $this->addFlash('success', 'Produit supprimé avec succès.');


            return new JsonResponse(['success' => true, 'message' => 'Produit supprimé avec succès'], 200);

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
        // $this->denyAccessUnlessGranted('DISHE_EDIT', $image->getVehicle());

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
