<?php

namespace App\Form;

use App\Entity\Vehicle;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VehicleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('image', TextType::class, [
                'label' => 'Image',
            ])
            ->add('kilometer', NumberType::class, [
                'label' => 'Kilométrage',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('year', ChoiceType::class, [
                'choices' => $this->generateYearChoices(),
                'label' => 'Année',
                'required' => false,
                'placeholder' => 'Toutes les années',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('contact', TextType::class, [
                'label' => 'Contact',
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Categorie::class,
                'choice_label' => 'name',
            ])
        ;
    }

    private function generateYearChoices(): array
    {
        $currentYear = (int) date('Y');
        $startYear = 1990;
        $years = range($currentYear, $startYear, -1);
        $choices = array_combine($years, $years);

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'method' => 'GET',
        ]);
    }
}
