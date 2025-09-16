<?php

namespace App\Form;

use App\Entity\NavigationLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Ex: À propos, Contact, etc.'
                ]
            ])
            ->add('url', UrlType::class, [
                'label' => 'URL',
                'attr' => [
                    'placeholder' => 'https://example.com'
                ]
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'help' => 'Détermine l\'ordre d\'affichage dans la barre de navigation'
            ])
            ->add('isVisible', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false,
                'help' => 'Décochez pour masquer le lien sans le supprimer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NavigationLink::class,
        ]);
    }
}
