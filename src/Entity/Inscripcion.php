<?php

namespace App\Entity;

use App\Repository\InscripcionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: InscripcionRepository::class)]
#[ORM\Table(name: 'inscripcion', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'inscripcion_unique_participante_evento', columns: ['participante_id', 'evento_id'])
])]
#[UniqueEntity(
    fields: ['participante', 'evento'],
    message: 'Esta inscripción para ese participante y evento ya existe.'
)]
class Inscripcion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fechaInscripcion = null;

    #[ORM\ManyToOne(inversedBy: 'inscripcions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participante $participante = null;

    #[ORM\ManyToOne(inversedBy: 'inscripcions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evento $evento = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaInscripcion(): ?\DateTimeInterface
    {
        return $this->fechaInscripcion;
    }

    public function setFechaInscripcion(\DateTimeInterface $fechaInscripcion): static
    {
        $this->fechaInscripcion = $fechaInscripcion;

        return $this;
    }

    public function getParticipante(): ?Participante
    {
        return $this->participante;
    }

    public function setParticipante(?Participante $participante): static
    {
        $this->participante = $participante;

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): static
    {
        $this->evento = $evento;

        return $this;
    }
}
