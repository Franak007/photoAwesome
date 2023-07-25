<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{

    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_category')]
    public function index(): Response
    {

        $categoryEntities = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
//            'controller_name' => 'CategoryController',
            'categories' => $categoryEntities
        ]);
    }

    #[Route('/show/{id}', name: 'app_category_show')]
    public function detail($id): Response
    {

        $categoryEntity = $this->categoryRepository->find($id);

        if ($categoryEntity === null) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/show.html.twig', [
//            'controller_name' => 'CategoryController',
            'category' => $categoryEntity
        ]);
    }

    #[Route('/add', name: 'app_category_add')]
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request); // Donne la consigne au formulaire d'écouter ce qui se passe dans la request

        if($form -> isSubmitted() && $form->isValid()) {

        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView()

        ]);
    }

}
