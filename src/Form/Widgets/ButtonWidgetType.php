<?php

namespace App\Form\Widgets;

use App\Entity\WidgetSwipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonWidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                  "placeholder" => "Le texte de votre bouton",
                ],
            ])
            ->add('href', UrlType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le lien de votre bouton",
                ],
            ])
            ->add('primaryColor', ColorType::class, [
                'required' => true,
                'attr' => [
                    "value" => "#ffffff",
                    "placeholder" => "La couleur primaire de votre bouton",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
