<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fechaInicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fechaFin = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $inscripcionAbiertaParaAdmins = true;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Participante::class)]
    private Collection $participantes;
    

    /**
     * @var Collection<int, Inscripcion>
     */
    // Se establece la relación One-to-Many con la entidad Inscripcion.
    #[ORM\OneToMany(targetEntity: Inscripcion::class, mappedBy: 'evento')]
    private Collection $inscripcions;

    #[ORM\ManyToOne(inversedBy: 'eventos')]
    private ?Localidad $localidad = null;
    
    public function __construct()
    {
        $this->inscripcions = new ArrayCollection();
        $this->participantes = new ArrayCollection();

    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getFechaInicio(): ?\DateTime
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTime $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;
        return $this;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTime $fechaFin): static
    {
        $this->fechaFin = $fechaFin;
        return $this;
    }

    /**
     * @return Collection<int, Inscripcion>
     */
    public function getInscripcions(): Collection
    {
        return $this->inscripcions;
    }

    public function addInscripcion(Inscripcion $inscripcion): static
    {
        if (!$this->inscripcions->contains($inscripcion)) {
            $this->inscripcions->add($inscripcion);
            $inscripcion->setEvento($this);
        }

        return $this;
    }

    public function removeInscripcion(Inscripcion $inscripcion): static
    {
        if ($this->inscripcions->removeElement($inscripcion)) {
            // set the owning side to null (unless already changed)
            if ($inscripcion->getEvento() === $this) {
                $inscripcion->setEvento(null);
            }
        }

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): static
    {
        $this->localidad = $localidad;
        return $this;
    }

    public function isInscripcionAbiertaParaAdmins(): bool
    {
        return $this->inscripcionAbiertaParaAdmins;
    }

    public function setInscripcionAbiertaParaAdmins(bool $abierta): static
    {
        $this->inscripcionAbiertaParaAdmins = $abierta;
        return $this;
    }    

    /**
 * @return Collection<int, Participante>
 */
public function getParticipantes(): Collection
{
    return $this->participantes;
}

public function addParticipante(Participante $participante): static
{
    if (!$this->participantes->contains($participante)) {
        $this->participantes->add($participante);
        $participante->setEvento($this);
    }

    return $this;
}

public function removeParticipante(Participante $participante): static
{
    if ($this->participantes->removeElement($participante)) {
        if ($participante->getEvento() === $this) {
            $participante->setEvento(null);
        }
    }

    return $this;
}

}
