<?php

namespace App\Controller\Admin;

use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_category')]
    public function index(Request $request): Response
    {
        // $qb = SELECT * FROM category

        $qb = $this->contactRepository->getQbAll();

        $result = $qb->getQuery()->getResult();
//        $categoryEntities = $this->categoryRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'contacts' => $result,

        ]);
    }
    #[Route('/show/{id}', name: 'app_contact_show')]
    public function detail($id): Response
    {
        $contact = $this->contactRepository->find($id);

        return $this->render('contact/show.html.twig', [
            'contact' => $contact
        ]);
    }
}
