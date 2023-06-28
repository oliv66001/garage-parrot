<?php

namespace App\Controller;

use Exception;
use App\Entity\Contact;
use App\Entity\Vehicle;
use Psr\Log\LoggerInterface;
use App\Form\ContactFormType;
use App\Service\SendMailService;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact')]
    #[Route('/contact/{id}', name: 'app_contact_vehicle', defaults: ['id' => null])]

    public function index(Request $request, EntityManagerInterface $em, SendMailService $mail, LoggerInterface $logger, BusinesshoursRepository $businessHoursRepository, VehicleRepository $vehicleRepository, $id = null): Response
    {

        $vehicle = null;

        if ($id) {
            $vehicle = $vehicleRepository->find($id);
        }
    
        $contact = new Contact();
        $contact->setSubject($vehicle);

        $form = $this->createForm(ContactFormType::class, $contact);

        $form->handleRequest($request);
        $session = $request->getSession();
        $business_hours = $businessHoursRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            try {
                $mail->send(
                    'garage-parrot@crocobingo.fr',
                    'vince.parrotg@gmail.com',
                    'Nouveau message de contact',
                    'contact',
                    ['contact' => $contact]
                );
                $this->addFlash('success', 'Votre message a bien été envoyé !');
            }  catch (Exception $e) {
                $logger->error('Erreur lors de l\'envoi du courriel : ', ['exception' => $e]);
                $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }
            

            return $this->redirectToRoute('app_main');
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'business_hours' => $business_hours,
            'formContact' => $form->createView()
        ]);
    }
}
