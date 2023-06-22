<?php

namespace App\Form;

use App\Entity\Repair;
use App\Entity\CategoryRepair;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RepairFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la réparation",
                'attr' => [
                    'placeholder' => "Nom de la réparation",
                    'class' => "form-control"
                ]
                ])
            ->add('description', TextareaType::class, [
                'label' => "Description de la réparation",
                'attr' => [
                    'placeholder' => "Description de la réparation",
                    'class' => "form-control"
                ]
                ])
            ->add('price' , IntegerType::class, [
                'label' => "Prix de la réparation",
                'attr' => [
                    'placeholder' => "Prix de la réparation",
                    'class' => "form-control"
                ]
                ])
                ->add('category', EntityType::class, [
                    'label' => 'Catégorie',
                    'class' => CategoryRepair::class,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Repair::class,
        ]);
    }
}
