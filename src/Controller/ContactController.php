<?php

namespace App\Controller;

use App\Entity\Contact;
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

    #[Route('/', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    #[Route('/add', name: 'app_contact_add')]
    public function add(Request $request): Response
    {
        $contact = new Contact();

        $status = 'non_lu';
        $contact->setCreatedAt(new \DateTime());
        $contact->setStatus($status);

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request); // Donne la consigne au formulaire d'écouter ce qui se passe dans la request

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/add.html.twig', [
            'formContact' => $form->createView()

        ]);
    }
}
