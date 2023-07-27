<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Media;
use App\Form\CategoryType;
use App\Form\MediaType;
use App\Form\MediaSearchType;
use App\Repository\MediaRepository;
use Container3xYJlWO\getKnpPaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/media')]
class MediaController extends AbstractController
{

    public function __construct(
        private MediaRepository $mediaRepository,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
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

        return $this->render('media/indexAdmin.html.twig', [
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
    public function add(Request $request,SluggerInterface $slugger): Response
    {

        /**
         * ci dessous, récupère l'utilisateur connecté,
         * soit une entité User(si connecté)
         * soit null (si pas connecté
         */
        $user = $this->getUser();
//        à utiliser en cas d'accès avec une route ou on n'est pas forcément connecté (ce n'est pas notre cas ici)
//        if($user === null){
//            return $this->redirectToRoute('app_home');
//        }
//
        $uploadDirectory = $this->getParameter('upload_file'); //va récupérer les uploads dans le dossier public


        $media = new Media();
        //Je relie le média à l'utilisateur connecté
        $media->setUser($user);
        //Je donne la date à mon média
        $media->setCreatedAt(new \DateTime());

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($media->getTitle());
            $media->setSlug($slug);

            $file = $form->get('file')->getData();

            // on créé un nouveau nom unique pour le fichier téléchargé
            if($file){
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); //récupère le nom du fichier sans l'extension
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();

                //on déplace le fichier dans le dossier d'upload avec son nouveau nom
                try {
                    $file->move(
                        $this->getParameter('upload_file'),
                        $newFileName
                    );
                    //on donne le chemin du fichier au média
                    $media->setFilePath($newFileName);
                } catch(FileException $e) {

                }
            }

            $this->entityManager->persist($media);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_media');

        }

        return $this->render('media/add.html.twig', [
            'formMedia' => $form->createView()
        ]);
    }
}
