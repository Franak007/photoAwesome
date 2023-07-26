<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('mediaTitle', TextType::class, [
                'label' => "Titre du média",
                'required' => false
            ])
            ->add('userEmail', TextType::class, [
                'label' => "Email du créateur",
                'required' => false
            ])
            ->add('mediaCreationDate', DateType::class, [
                'label' => "Média créé après le : ",
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
