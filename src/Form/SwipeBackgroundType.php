<?php

namespace App\Form;

use App\Entity\SwipeImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SwipeBackgroundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('backgroundFile', VichFileType::class, [
                'required' => true,
                'attr' => [
                    'class' => ''
                ],
                'label' => true
            ])
            ->add('isPublic', HiddenType::class, [
                'data' => 'false',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SwipeImage::class,
        ]);
    }
}
