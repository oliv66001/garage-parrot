<?php

namespace App\Form;

use App\Entity\Vehicle;
use App\Entity\Categorie;
use App\Entity\VehicleOption;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VehicleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

      
          $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('displayOnHomePage', CheckboxType::class, [
                'label'    => 'Afficher sur la page d\'accueil  ',
                'required' => false,
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                'class' => 'form-control'
                ],
                'constraints' => [
                    new All(
                        new Image([
                            'maxWidth' => 1280,
                            'maxWidthMessage' => 'L\'image doit faire {{ max_width }} pixels de large au maximum',
                            'maxHeight' => 700,
                            'maxHeightMessage' => 'L\'image doit faire {{ max_height }} pixels de haut au maximum',
                            'minHeight' => 500,
                            'minHeightMessage' => 'L\'image doit faire {{ min_height }} pixels de haut au minimum',
                            'minWidth' => 1000,
                            'minWidthMessage' => 'L\'image doit faire {{ min_width }} pixels de large au minimum',
                            
                ])
                )
                        ],
            ])
           
            ->add('kilometer', NumberType::class, [
                'label' => 'Kilométrage',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('year', DateType::class, [
                'label' => 'Année',
                'required' => false,
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Categorie::class,
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
            'data_class' => Vehicle::class,
        ]);
    }
}
