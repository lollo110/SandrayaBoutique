<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email. ',
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer un nom. ",
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer un prénom. ",
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                'label' => "Username",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un username. ',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Votre username doit contenir au moins {{ limit }} caractères.",
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('portable', TelType::class, [
                'label' => 'Portable',
                "constraints" => [
                    new NotBlank([
                        'message' => "Veuillez entrer un numero de portable. "
                    ])
                ]
            ])
            ->add('add_livraison', TextType::class, [
                'label' => 'Adresse de livraison',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer un adresse de livraison. "
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une ville. '
                    ])
                ]
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un code postal. '
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les <a href="/terms" target="_blank">Conditions générales</a>',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d’accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => "Mot de Passe",
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                        'message' => 'Votre mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
