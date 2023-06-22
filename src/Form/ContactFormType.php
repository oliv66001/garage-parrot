<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,
            [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'form-control'
                ]
            ])
            ->add('lastname', TextType::class,
            [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'form-control'
                ]
            ])
            ->add('mail', EmailType::class,
            [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email',
                    'class' => 'form-control'
                ]
            ])
            ->add('phone', TelType::class,
            [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Votre téléphone',
                    'class' => 'form-control'
                ]
            ])
            ->add('message', TextareaType::class,
            [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message',
                    'class' => 'form-control'
                ]
            ])
            ->add('subject', EntityType::class, [
                'class' => Vehicle::class,
                'label' => 'Véhicule',
                'required' => false,
                'placeholder' => 'Choisissez un véhicule d\'occasion ou laisser vide',
                'choice_label' => function (Vehicle $vehicle) {
                    return sprintf('%s - %s - %s km - Année: %s', $vehicle->getBrand(), $vehicle->getKilometer(), $vehicle->getYear()->format('Y'), $vehicle->getPrice());
                },
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ],
            ])
           
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn2 mt-3',
                    
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
