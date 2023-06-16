<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                '' => 0,
                '★' => 1,
                '★★' => 2,
                '★★★' => 3,
                '★★★★' => 4,
                '★★★★★' => 5,
            ],
            'expanded' => true,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
