<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\ImageRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'name',
                'position' => 'position',
                'vehicle' => 'vehicle',
                'url' => 'url',
                'label' => 'Image',
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
