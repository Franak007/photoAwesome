<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'mapped' =>false,
                'label' => 'Lien URL : '
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre : '
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description : '
            ])
            /**
             * Comment ajouter un user au formulaire
             * Pas besoin dans notre cas car on prendra l'utilisateur connecté
             */
//            ->add('user', EntityType::class, [
//                'class' => User::class,
////                'query_builder' => function(EntityRepository $er) {
////                    return $er->createQueryBuilder('u')
////                        ->orderBy('u.email', 'ASC');
////                },
//                'choice_label' => 'email',
//                'expanded' => true,
//                'label' => 'Créateur du média : '
//            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                // Option ci-dessous nécessaire lorsque a un tableau d'entités
                'multiple' => true,
                'expanded' => true,
                'label' => 'Choisissez les catégories du média : '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
