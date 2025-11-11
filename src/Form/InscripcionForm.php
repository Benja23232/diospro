<?php

namespace App\Form;

use App\Entity\Inscripcion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Participante;
use App\Entity\Evento;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class InscripcionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaInscripcion')
         //   ->add('participante', EntityType::class, [
          //  'class' => Participante::class,
         /*   'choice_label' => function (Participante $p) {
                return $p->getDni() . ' ' . $p->getApellido() . ' ' . $p->getNombre();
            },
            'placeholder' => 'Seleccione un participante',
            'label' => 'Participante',
        ])*/
        ->add('dniParticipante', TextType::class, [
            'mapped' => false, // no está en la entidad Inscripcion
            'label' => 'DNI del participante',
            'required' => true,
        ])        
        ->add('nombreParticipante', TextType::class, [
            'mapped' => false,
            'label' => 'Nombre',
            'required' => false,
            'attr' => ['readonly' => true],
        ])
        ->add('apellidoParticipante', TextType::class, [
            'mapped' => false,
            'label' => 'Apellido',
            'required' => false,
            'attr' => ['readonly' => true],
        ])        
        ->add('evento', EntityType::class, [
            'class' => Evento::class,
            'choice_label' => 'nombre',
            'placeholder' => 'Seleccione un evento',
            'label' => 'Evento',
        ])
         ->add('deQueParticipa', TextType::class, [
                'mapped' => false,
                'label' => '¿Cómo participa?',
                'required' => false,
            ]) ->add('fichaMedicaFile', FileType::class, [
                'label' => 'Ficha Médica (PDF, DOC, JPG, etc)',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'accept' => 'application/pdf, image/jpeg, image/png, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpeg',
                            'image/png',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Formatos aceptados: PDF, JPG, PNG, DOC o DOCX.',
                    ])
                ],
            ]);
    ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscripcion::class,
        ]);
    }
}
