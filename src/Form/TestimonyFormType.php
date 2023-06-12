<?php

namespace App\Form;

use App\Entity\Testimony;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label'=>'Nom',
            'attr' => [
                'placeholder' => 'Votre nom',
                'class' => 'form-control'
            ],
            ])
            ->add('message', null, ['label'=>'Message',
            'attr' => [
                'placeholder' => 'Votre message',
                'class' => 'form-control'
            ],]);
    
        if ($options['ROLE_COLAB_ADMIN']) {
            $builder->add('validation', null, ['label'=>'Validation']);
        }
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimony::class,
            'ROLE_COLAB_ADMIN' => false, // Set to false by default
        ]);
    }
}
