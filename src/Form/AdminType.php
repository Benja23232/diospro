<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Localidad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'home-input'],
                'label_attr' => ['class' => 'home-label']
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Role usuario' => 'ROLE_USER',
                    'Role administrador' => 'ROLE_ADMIN',
                    'Role Super Usuario' => 'ROLE_SUPER_ADMIN'
                ],
                'expanded' => false,
                'multiple' => true,
                'label' => '* Roles',
                'attr' => ['class' => 'home-input'],
                'label_attr' => ['class' => 'home-label']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => '* Password',
                    'attr' => ['class' => 'home-input'],
                    'label_attr' => ['class' => 'home-label']
                ],
                'second_options' => [
                    'label' => '* Repetir Password',
                    'attr' => ['class' => 'home-input'],
                    'label_attr' => ['class' => 'home-label']
                ]
            ])->add('localidad', EntityType::class, [
    'class' => Localidad::class,
    'choice_label' => 'nombre', // o el campo que represente el nombre de la localidad
    'placeholder' => 'Selecciona una localidad',
    'required' => true,
    'label' => '* Localidad',
    'attr' => ['class' => 'home-input'],
    'label_attr' => ['class' => 'home-label']
])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
