<?php

namespace App\Form\Widgets;

use App\Entity\WidgetSwipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextWidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le titre de votre section",
                ],
            ])
            ->add('color', ColorType::class, [
                'required' => false,
                'attr' => [
                    "value" => "#ffffff",
                    "placeholder" => "Le couleur du texte de votre section",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
