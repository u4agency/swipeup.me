<?php

namespace App\Form;

use App\Service\FileTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SwipeFastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $acceptedMimeTypes = [
            ...FileTypeService::$videoMimeTypes,
            ...FileTypeService::$imageMimeTypes,
        ];

        $builder
            ->add('text', TextType::class, [
                'label' => 'Texte du bouton',
                'required' => true,
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien du bouton',
                'required' => true,
            ])
            ->add('background', FileType::class, [
                'mapped' => false,
                'label' => "Fond de la section",
                'required' => true,
                'attr' => [
                    'class' => 'sr-only',
                    'accept' => implode(", ", $acceptedMimeTypes),
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1G',
                        'mimeTypes' => $acceptedMimeTypes,
                        'mimeTypesMessage' => "Le fichier envoyé est trop volumineux ou n'est pas un fichier vidéo ou image valide.",
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
