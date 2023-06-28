<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'E-mail'
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Nom'
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Employé Admin' => 'ROLE_COLAB_ADMIN',
                        'Administrateur' => 'ROLE_ADMIN',
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Rôles',
                    'attr' => [
                        'class' => 'form-check'
                    ],
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ],
                    'choice_attr' => function($choice, $key, $value) {
                        // Si la valeur est 'ROLE_USER', alors ajoute l'attribut 'disabled'
                        if ($value == 'ROLE_USER') {
                            return ['disabled' => 'disabled'];
                        }
                        return [];
                    },
                ]
            )
            
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8, 
                        'minMessage' => 'Votre mot de passe doit être au moins de {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre, un caractère spécial et comporter au moins 8 caractères.'
                    ]),
                ]
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
