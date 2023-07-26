<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Media;
use App\Form\CategoryType;
use App\Form\MediaInsertType;
use App\Form\MediaSearchType;
use App\Repository\MediaRepository;
use Container3xYJlWO\getKnpPaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/media')]
class MediaController extends AbstractController
{

    public function __construct(
        private MediaRepository $mediaRepository,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator
    )
    {
    }

    #[Route('/', name: 'app_media')]
    public function index(Request $request): Response
    {
// $qb = SELECT * FROM media INNER JOIN user ON media.user_id = user.id

        $qb = $this->mediaRepository->getQbAll();

        $form = $this->createForm(MediaSearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data= $form->getData();

            if($data['mediaTitle'] !== null) {
                $qb->andWhere('m.title LIKE :toto')
                    ->setParameter('toto', "%".$data['mediaTitle']."%");
            }
            if($data['userEmail'] !== null) {
                //INNER JOIN user ON media.user_id = user.id
                //WHERE user.email = {ma_recherche}
                $qb->innerJoin('m.user', 'u')
                    ->andWhere('u.email = :email')
                    ->setParameter('email', $data['userEmail']);
            }
            if($data['mediaCreationDate'] !== null) {
                $qb ->andWhere('m.createdAt > :creationDate')
                    ->setParameter('creationDate', $data['mediaCreationDate']);
            }
        }


        $pagination = $this->paginator->paginate(

            $qb,
            $request->query->getInt('page',1),
            15

        );

//        $medias = $this->mediaRepository->findAll();

        return $this->render('media/index.html.twig', [
//            'controller_name' => 'MediaController',
                'medias' => $pagination,
                'form' => $form->createView()
        ]);
    }

    #[Route('/show/{id}', name: 'app_media_show')]
    public function detail($id): Response
    {
        $media = $this->mediaRepository->find($id);

        return $this->render('media/show.html.twig', [
            'media' => $media
        ]);
    }

    #[Route('/add', name: 'app_media_add')]
    public function add(Request $request): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaInsertType::class, $media);
        $form->handleRequest($request); // Donne la consigne au formulaire d'écouter ce qui se passe dans la request

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_media');
        }

        return $this->render('media/add.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
