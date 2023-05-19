<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use Psr\Log\LoggerInterface;
use App\Security\Voter\UserVoter;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/utilisateurs', name: 'admin_user_')]
class UserController extends AbstractController
{
 
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll([], ['firstname', 'ASC'] );
        return $this->render('admin/user/index.html.twig', compact('user'));
    }

    #[Route('/edition{id}', name: 'edit')]
    public function edit(
        User $user,
        Request $request, 
        EntityManagerInterface $em 
       ): Response
    {
        //Vérification si l'user peut éditer avec le voter
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);


        // Création du formulaire
        $userForm = $this->createForm(UserFormType::class, $user);

        $userForm->handleRequest($request);

        //Vérification du soumission du formulaire
        if ($userForm->isSubmitted() && $userForm->isValid()) {

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

   // Ajoutez l'argument UserRepository à votre méthode deleteUser
#[Route('/suppression/user/{id}', name: 'delete_user', methods: ['DELETE'])]
public function deleteUser(
    
    EntityManagerInterface $em,
    UserRepository $userRepository,
    LoggerInterface $logger,
    Request $request,
    int $id,
    User $user
): JsonResponse {

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
