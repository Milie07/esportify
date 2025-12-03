<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control input_filter mb-3',
                    'placeholder' => 'Votre nom',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'required' => true,
                'mapped' => false,
            ])
            ->add('surname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control input_filter mb-3',
                    'placeholder' => 'Votre prénom',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'required' => true,
                'mapped' => false,
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control input_filter mb-3',
                    'placeholder' => 'Votre pseudo',
                    'minlength' => 2,
                    'maxlength' => 50,
                ],
                'required' => false,
                'mapped' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control input_filter mb-3',
                    'placeholder' => 'example@example.fr',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Assert\Email(['message' => 'L\'email {{ value }} n\'est pas valide.']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Qui êtes-vous ?',
                'choices' => [
                    'Un visiteur' => 'visiteur',
                    'Un joueur' => 'joueur',
                    'Un organisateur' => 'organisateur',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'role_user'],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Votre message concerne',
                'choices' => [
                    'Un problème technique' => 'Problème Technique',
                    'Un compte utilisateur' => 'Compte Utilisateur',
                    'Aide et informations' => 'Aide et Informations',
                    'Autre' => 'Autre',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'question_type'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'class' => 'form-control input_filter',
                    'placeholder' => 'Votre message ici...',
                    'rows' => 8,
                    'maxlength' => 5000,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le message est obligatoire.']),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 5000,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'contact',
        ]);
    }
}