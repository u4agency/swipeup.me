<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Votre nom d'utilisateur",
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre nom d\'utilisateur doit comporter au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Nom d'utilisateur",
                    'autocomplete' => "username"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre adresse mail",
                'required' => true,
                'attr' => [
                    'placeholder' => "Adresse mail",
                    'autocomplete' => "email"
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les conditions d'utilisation",
                'label_attr' => [
                    'class' => "ml-2 block text-sm text-gray-300"
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être les mêmes.',
                'mapped' => false,
                'required' => true,

                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => "Mot de passe",
                        'autocomplete' => 'password'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un mot de passe valide',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ]
                ],
                'second_options' => [
                    'label' => 'Répétez le mot de passe',
                    'attr' => [
                        'placeholder' => "Répétez le mot de passe",
                        'autocomplete' => 'password'
                    ],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
