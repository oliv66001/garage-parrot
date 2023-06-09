<?php

namespace App\Controller;

use DateTime;
use Swift_Message;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\BusinesshoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    
    #[Route(path: '/connexion', name: 'app_login')]
   
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - 
            it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-passe', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request, 
        UserRepository $usersRepository, 
        TokenGeneratorInterface $tokenGenerator, 
        EntityManagerInterface $entityManager, 
        BusinesshoursRepository $businessHoursRepository,
        SendMailService $mail): Response
    {
        $businessHours = $businessHoursRepository->findAll();
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisitateur par son e-mail
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());
           
            //on vérifie si on a un utilisateur
            if($user){
                //On génère un token de réinisialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                //On génère un liens de réinitialisation de mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);
                
                //On crée les donnée du mail
                $context = compact('url', 'user');

                //Envoi du mail
                $mail->send(
                    'garage-parrot@crocobingo.fr',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'E-mail envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            //$user est null
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('security/reset_password_request.html.twig', [
            'business_hours' => $businessHours,
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token, 
    Request $request, 
    UserRepository $usersRepository, 
    EntityManagerInterface $entityManager, 
    BusinesshoursRepository $businessHoursRepository,
    UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $businessHours = $businessHoursRepository->findAll();
        //On vérifie su on a ce token dans la base de donnée
        $user = $usersRepository->findOneByResetToken($token);
        
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                //On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                   
                    );
               
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Mot de passe changer avec succès');
                    return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'business_hours' => $businessHours,
                'passForm' => $form->createView()
            ]);
        }
        
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
