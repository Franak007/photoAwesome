<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private MediaRepository$mediaRepository
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/api/category', name: 'api_category')]
    public function apiCategoryCollection()
    {
        $categories = $this->categoryRepository->findAll();
        return $this->json($categories, context: ['groups' =>'toto']);
    }

    #[Route('/api/media', name: 'api_media')]
    public function apimediaCollection()
    {
        $medias = $this->mediaRepository->findAll();
        return $this->json($medias, context: ['groups' =>'media']);
    }
}
