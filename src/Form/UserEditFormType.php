<?php

namespace App\Form;

use App\Entity\Newsletter;
use App\Entity\SwipeImage;
use App\Entity\User;
use App\Entity\Widget;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Repository\NewsletterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditFormType extends AbstractType
{
    public function __construct(
        private readonly NewsletterRepository $newsletterRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => "Votre adresse email",
                    'autocomplete' => 'email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez une adresse email valide',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => "Votre pseudo",
                    'autocomplete' => 'pseudo'
                ],
            ]);

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();

                    $form->add('newsletter', CheckboxType::class, [
                        'required' => false,
                        'mapped' => false,
                        'label' => 'Newsletter',
                        'data' => (bool)$options['newsletter'],
                    ]);
                })
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    $data = $event->getData();

                    if (!$form->get('newsletter')->getData() && $options['newsletter']) {
                        $this->newsletterRepository->remove($options['newsletter']);
                    } elseif ($form->get('newsletter')->getData() && !$options['newsletter']) {
                        $newsletter = new Newsletter();
                        $newsletter->setEmail($data->getEmail());
                        $newsletter->setSource('profile_edit');

                        $this->newsletterRepository->save($newsletter);
                    }
                });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'newsletter' => '',
        ]);
    }
}
