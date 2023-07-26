<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use App\Form\MediaSearchType;
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
        private CategoryRepository     $categoryRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_category')]
    public function index(Request $request): Response
    {
        // $qb = SELECT * FROM category

        $qb = $this->categoryRepository->getQbAll();

        $form = $this->createForm(CategorySearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['categoryLabel'] !== null) { //categoryLabel est récupéré dans CategorySearchType
                $qb->andWhere('c.label LIKE :toto')
                    ->setParameter('toto', "%" . $data['categoryLabel'] . "%");
            }
        }

        $result = $qb->getQuery()->getResult();
//        $categoryEntities = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
//            'controller_name' => 'CategoryController',
            'categories' => $result,
            'form' => $form->createView()
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
        $form->handleRequest($request); // Donne la consigne au formulaire d'écouter ce qui se passe dans la request

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView()

        ]);
    }

    #[Route('/delete/{id}', name: 'app_category_delete')]
    public function delete($id): Response
    {
        $category = $this->categoryRepository->find($id);

        if ($category !== null) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_category');
    }

    #[Route('/edit/{id}', name: 'app_category_edit')]
    public function edit($id, Request $request): Response
    {
        $category = $this->categoryRepository->find($id);

        if ($category !== null) {
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request); // Donne la consigne au formulaire d'écouter ce qui se passe dans la request

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($category);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_category');
            }

            return $this->render('category/edit.html.twig', [
                'form' => $form->createView()

            ]);
        }
        return $this->redirectToRoute('app_category');
    }

}
