<?php

namespace App\Form;

use App\Entity\Evento;
use App\Entity\Localidad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EventoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add('localidad', EntityType::class, [
                'class' => Localidad::class,
                'choice_label' => 'nombre', // mostrar el nombre de la localidad
                'placeholder' => 'Seleccionar localidad',
                'required' => false, // poné true si querés que sea obligatorio
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evento::class,
        ]);
    }
}
