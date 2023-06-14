<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    private $imageRepository;
    private $em;

    public function __construct(ImageRepository $imageRepository, EntityManagerInterface $em)
    {
        $this->imageRepository = $imageRepository;
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        
        return $this->render('admin/index.html.twig');
    }
   
}