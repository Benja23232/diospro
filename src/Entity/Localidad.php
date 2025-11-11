<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Participante>
     */
    #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Participante::class)]
    private Collection $participantes;

    /**
     * @var Collection<int, Admin>
     */
    #[ORM\OneToMany(targetEntity: Admin::class, mappedBy: 'localidad')]
    private Collection $admins;

    /**
     * @var Collection<int, Evento>
     */
    #[ORM\OneToMany(targetEntity: Evento::class, mappedBy: 'localidad')]
    private Collection $eventos;

    public function __construct()
    {
        $this->participantes = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->eventos = new ArrayCollection();
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
            $participante->setLocalidad($this);
        }

        return $this;
    }

    public function removeParticipante(Participante $participante): static
    {
        if ($this->participantes->removeElement($participante)) {
            if ($participante->getLocalidad() === $this) {
                $participante->setLocalidad(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Admin>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(Admin $admin): static
    {
        if (!$this->admins->contains($admin)) {
            $this->admins->add($admin);
            $admin->setLocalidad($this);
        }

        return $this;
    }

    public function removeAdmin(Admin $admin): static
    {
        if ($this->admins->removeElement($admin)) {
            if ($admin->getLocalidad() === $this) {
                $admin->setLocalidad(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): static
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos->add($evento);
            $evento->setLocalidad($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): static
    {
        if ($this->eventos->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getLocalidad() === $this) {
                $evento->setLocalidad(null);
            }
        }

        return $this;
    }

    public function __toString(): string
{
    return $this->nombre ?? '';
}
}
