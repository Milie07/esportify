<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control input_filter',
                    'placeholder' => 'Votre nom',
                    'minlength' => 2,
                    'maxlength' => 100,
                ],
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control input_filter',
                    'placeholder' => 'Votre prénom',
                    'minlength' => 2,
                    'maxlength' => 100,
                ],
                'required' => true,
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control input_filter',
                    'placeholder' => 'Votre pseudo',
                    'minlength' => 3,
                    'maxlength' => 20,
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control input_filter',
                    'placeholder' => 'example@example.fr',
                ],
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'class' => 'form-control input_filter',
                        'placeholder' => 'Votre mot de passe',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'class' => 'form-control input_filter',
                        'placeholder' => 'Confirmer votre mot de passe',
                    ],
                ],
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
                    ]),
                ],
            ])
            ->add('avatar', ChoiceType::class, [
                'label' => 'Choisir un avatar',
                'mapped' => false,
                'choices' => [
                    'Avatar 1' => 1,
                    'Avatar 2' => 2,
                    'Avatar 3' => 3,
                    'Avatar 4' => 4,
                    'Avatar 5' => 5,
                    'Avatar 6' => 6,
                    'Avatar 7' => 7,
                    'Avatar 8' => 8,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 1,
                'attr' => ['class' => 'avatar-choice'],
            ])
            ->add('conditions', CheckboxType::class, [
                'label' => "J'accepte les conditions générales d'utilisation",
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Vous devez accepter les conditions générales d\'utilisation.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'register',
        ]);
    }
}