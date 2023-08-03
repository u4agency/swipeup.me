<?php

namespace App\Form;

use App\Entity\SwipeUp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
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
                    'rows' => 1
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status du SwipeUp',
                'attr' => [
                    'class' => $classes,
                ],
                'choices' => [
                    'Public' => 'public',
                    'Non-répertorié' => 'unlisted',
                    'Privé' => 'private',
                ],
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SwipeUp::class,
        ]);
    }
}
