<?php
// src/Form/ParticipanteForm.php

namespace App\Form;

use App\Entity\Participante;
use App\Entity\Evento; 
use App\Entity\Localidad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\File;

class ParticipanteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('apellido', TextType::class, ['label' => 'Apellido'])
            ->add('nombreCredencial', TextType::class, ['label' => 'Nombre de Credencial'])
            ->add('dni', TextType::class, ['label' => 'DNI'])
            ->add('sexo', ChoiceType::class, [
                'label' => 'Sexo',
                'choices' => [
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                ],
                'placeholder' => 'Seleccione sexo',
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('edad', NumberType::class, ['label' => 'Edad'])
            ->add('celular', TextType::class, ['label' => 'Celular'])
            ->add('tutor1', TextType::class, [
                'label' => 'Tutor 1',
                'required' => true,
            ])
            ->add('telefonoTutor1', TextType::class, [
                'label' => 'Teléfono Tutor 1',
                'required' => true,
            ])            
            ->add('tutor2', TextType::class, [
                'label' => 'Tutor 2',
                'required' => false,
            ])
            ->add('telefonoTutor2', TextType::class, [
                'label' => 'Teléfono Tutor 2',
                'required' => false,
            ])->add('evento', EntityType::class, [
                'class' => Evento::class,
                'choice_label' => 'nombre',
                'label' => false,
                'attr' => ['style' => 'display:none'],
                'required' => false,
            ])            
            ->add('deQueParticipa', TextType::class, ['label' => '¿De que participa?'])
            ->add('localidad', EntityType::class, [
                'class' => Localidad::class,
                'choice_label' => 'nombre',
                'label' => 'Localidad',
            ])
            ->add('obraSocialnumeroAfiliado', TextType::class, ['label' => 'Obra Social/N° de afiliado'])
            ->add('observaciones', TextType::class, ['label' => 'Observaciones'])
            ->add('dieta', ChoiceType::class, [
                'label' => 'Dieta',
                'choices' => [
                    'Sin Dieta Especial' => 'ninguna',
                    'Vegetariana' => 'vegetariana',
                    'Vegana' => 'vegana',
                    'Celíaca' => 'celiaca',
                    'Diabética' => 'diabetica',
                    'Otra' => 'otro',
                ],
                'placeholder' => 'Seleccione una dieta',
                'required' => true,
            ])
            ->add('otraDieta', TextType::class, [
                'label' => 'Especifique otra dieta',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medicacion', ChoiceType::class, [
                'label' => 'Medicación',
                'choices' => [
                    'No' => 'no',
                    'Sí' => 'otro',
                ],
                'placeholder' => '¿Toma medicación?',
                'required' => true,
            ])
            ->add('evento', EntityType::class, [
                 'class' => Evento::class,
                 'choice_label' => 'nombre',
                 'label' => 'Evento',
                 'disabled' => false,
                 'required' => true,
             ])             
            ->add('otraMedicacion', TextType::class, [
                'label' => 'Especifique medicación',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dificultades', ChoiceType::class, [
                'choices' => [
                    'Ninguna' => 'ninguna',
                    'Visual' => 'visual',
                    'Auditiva' => 'auditiva',
                    'Motriz' => 'motriz',
                    'Otro' => 'otro',
                ],
                'placeholder' => 'Seleccionar dificultad',
                'required' => true,
                'label' => 'Dificultades',
            ])
            ->add('otrasDificultades', TextType::class, [
                'label' => 'Especifique otras dificultades',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fichaMedicaFile', FileType::class, [
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participante::class,
        ]);
    }
}
