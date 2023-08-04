<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Swipe;
use App\Entity\Ville;
use App\Entity\SwipeImage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SwipeSectionType extends AbstractType
{
    public function __construct(
        private readonly Security         $security,
    )
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'mapped' => false,
                'label' => "Titre de la section",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Titre de la section',
                ],
            ])
            ->add('background', VichFileType::class, [
            ->add('background', FileType::class, [
                'mapped' => false,
                'label' => "Fond de la section",
                'required' => true,
            ])
            ->add('buttonText', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Texte du bouton",
                'attr' => [
                    'placeholder' => 'Texte du bouton',
                ],
            ])
            ->add('buttonHref', UrlType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Lien du bouton",
                'attr' => [
                    'placeholder' => 'Lien du bouton',
                ],
            ])
        ;
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (isset($data['background']) && !empty($data['background'])) {
                    $swipeImage = new SwipeImage();
                    $swipeImage->setAuthor($this->security->getUser());
                    $swipeImage->setBackgroundName($data['background']);
                    $swipeImage->setBackgroundFile($data['background']);
                    $swipeImage->setAlt("A Swipe background");

                    $form->getData()->setBackground($swipeImage);

                    $data['background'] = $swipeImage->getBackgroundFile();
                    $event->setData($data);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
        $resolver->setDefaults([
            'data_class' => Swipe::class,
        ]);
    }
    }
}
