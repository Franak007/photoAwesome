<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaInsertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filePath', TextType::class, [
                'label' => 'Lien URL : '
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre : '
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'required' => false
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de création : ',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Choisissez les catégories du média : '

            ])

            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Créateur du média : '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
