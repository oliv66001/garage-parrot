<?php

namespace App\Form;

use App\Entity\BusinessHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessHoursFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j < 60; $j += 30) {
                $hours[sprintf("%02d:%02d", $i, $j)] = sprintf("%02d:%02d", $i, $j);
            }
        }

        $builder
            ->add('day', ChoiceType::class, [
                'choices' => [
                    'Lundi' => 'Lundi',
                    'Mardi' => 'Mardi',
                    'Mercredi' => 'Mercredi',
                    'Jeudi' => 'Jeudi',
                    'Vendredi' => 'Vendredi',
                    'Samedi' => 'Samedi',
                    'Dimanche' => 'Dimanche'
                ],
                'label' => 'Jour',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('openTimeMorning', ChoiceType::class, [
                'choices' => $hours,
                'label' => 'Heure d\'ouverture',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('closedTimeMorning', ChoiceType::class, [
                'choices' => $hours,
                'label' => 'Heure de fermeture',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('openTimeAfternoon', ChoiceType::class, [
                'choices' => $hours,
                'label' => 'Heure d\'ouverture',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('closedTimeAfternoon', ChoiceType::class, [
                'choices' => $hours,
                'label' => 'Heure de fermeture',
                'attr' => [
                    'class' => 'form-control'
                ]
                ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BusinessHours::class,
        ]);
    }
}
