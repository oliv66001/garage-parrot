<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\JWTService;
use Psr\Log\LoggerInterface;
use App\Service\SendMailService;
use App\Security\Voter\UserVoter;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/admin/utilisateurs', name: 'admin_user_')]
class UserController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll([], ['firstname', 'ASC']);
        return $this->render('admin/user/index.html.twig', compact('user'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt,
    ): Response {
        // Check if user has ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Seuls les administrateurs peuvent accéder à cette page.');
        }

        $user = new User();
        $user->setRoles(['ROLE_USER']);        
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
       
        
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
        
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
        
            //génération du JWT de l'utilisateur
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];
        
            $payload = [
                'user_id' => $user->getId(),
            ];
        
            $token = $jwt->generate(
                $header,
                $payload,
                $this->getParameter('app.jwtsecret')
            );
        
           $mail->send(
                'garage-parrot@crocobingo.fr',
                $user->getEmail(),
                'Activation de votre compte',
                'register',
                compact('user', 'token')
        
            );
        
            // Message flash
            $this->addFlash('success', 'Le collaborateur a bien été ajouté');
        
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        
        return $this->render('admin/user/add.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
public function edit(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
{
    if (!$this->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Seuls les administrateurs peuvent accéder à cette page.');
    }

    // Création du formulaire
    $userForm = $this->createForm(UserFormType::class, $user);

    $userForm->handleRequest($request);

    //Vérification du soumission du formulaire
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        
        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $userForm->get('password')->getData()
            )
        );

        $em->persist($user);
        $em->flush();

        //Message flash
        $this->addFlash('success', 'L\'utilisateur a bien été modifier');

        //Redirection vers la page de détails du produit
        return $this->redirectToRoute('admin_user_index');
    }

    return $this->render('admin/user/edit.html.twig', [
        'userForm' => $userForm->createView(),
        'user' => $user

    ]);
}


    #[Route('/suppression/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $em, LoggerInterface $logger, Request $request, User $user, UserRepository $userRepository, int $id): JsonResponse
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Seuls les administrateurs peuvent accéder à cette page.');
        }
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        // Récupérer l'utilisateur à supprimer
        $userToDelete = $userRepository->find($id);


        if (!$userToDelete) {
            return new JsonResponse(['error' => 'Utilisateur introuvable'], 404);
        }

        $logger->info('User to delete: ' . $userToDelete->getId());

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete_user' . $userToDelete->getId(), $data['_token'])) {


            // On supprime le compte utilisateur de la base
            $em->remove($userToDelete);
            $em->flush();

            $this->addFlash('success', 'Compte utilisateur supprimé avec succès.');

            return new JsonResponse(['success' => true, 'message' => 'Compte utilisateur supprimé avec succès'], 200);
        }

        // Échec de la suppression
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
