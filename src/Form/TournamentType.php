<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du tournoi',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre du tournoi',
                    'maxlength' => 120,
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description du tournoi',
                    'rows' => 5,
                    'maxlength' => 500,
                ],
                'required' => true,
            ])
            ->add('tagline', TextType::class, [
                'label' => 'Tagline',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Une phrase accrocheuse',
                    'maxlength' => 60,
                ],
                'required' => true,
            ])
            ->add('startAt', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
                'input' => 'datetime_immutable',
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
                'input' => 'datetime_immutable',
            ])
            ->add('capacityGauge', IntegerType::class, [
                'label' => 'Nombre de joueurs maximum',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 100,
                    'placeholder' => 'Ex: 16',
                ],
                'required' => true,
            ])
            ->add('tournamentImage', FileType::class, [
                'label' => 'Image du tournoi',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png,image/webp',
                ],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Seuls les formats JPEG, PNG et WebP sont acceptés.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'create_tournament',
        ]);
    }
}