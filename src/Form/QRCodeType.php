<?php

namespace App\Form;

use App\Entity\URLShortener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QRCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('link', UrlType::class, [
                'label' => 'Votre lien',
                'attr' => [
                    'placeholder' => 'Exemple: https://votre-lien.fr',
                ],
            ])
            ->add('urlshort', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Suivre les statistiques de ce lien',
                'expanded' => true,
                'data' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => URLShortener::class,
        ]);
    }
}
