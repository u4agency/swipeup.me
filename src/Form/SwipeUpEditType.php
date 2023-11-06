<?php

namespace App\Form;

use App\Entity\SwipeUp;
use App\Service\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SwipeUpEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $classes = "bg-white flex relative py-4 px-3 rounded-xl text-lg w-full overflow-x-hidden outline-none border-none focus:ring-0 focus:outline-none";
        $acceptedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        ];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du SwipeUp',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du SwipeUp',
                'attr' => [
                    'rows' => 3
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status du SwipeUp',
                'attr' => [
                    'class' => $classes,
                ],
                'choices' => array_flip(Status::STATUS),
                'expanded' => true,
            ])
            ->add('slug', TextType::class, [
                'label' => 'Lien du SwipeUp',
            ])
            ->add('logoFile', VichImageType::class, [
                'label' => 'Logo du SwipeUp',
                'attr' => [
                    'class' => 'hidden',
                    'accept' => implode(", ", $acceptedMimeTypes),
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/webp'],
                        'mimeTypesMessage' => "Le fichier envoyé n'est pas valide",
                        'maxHeight' => 320,
                        'maxWidth' => 320,
                        'minRatio' => 1,
                        'maxRatio' => 1,
                    ])
                ],
                'allow_delete' => false,
                'image_uri' => $options['data']->getLogoName() === $options['data']->getRealLogoName(),
                'download_uri' => false,
                'required' => false,
            ])
            ->add('gaId', TextType::class, [
                'label' => 'Identifiant Google Analytics',
                'required' => false,
                'attr' => [
                    'class' => $classes,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^G-[A-Za-z0-9]{10}$/',
                        'message' => "Le code Google Analytics n'est pas valide",
                    ])
                ],
            ])
            ->add('fbId', TextType::class, [
                'label' => 'Identifiant Facebook Pixel',
                'required' => false,
                'attr' => [
                    'class' => $classes,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{15,16}$/',
                        'message' => "Le code Facebook Pixel n'est pas valide",
                    ])
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SwipeUp::class,
        ]);
    }
}
