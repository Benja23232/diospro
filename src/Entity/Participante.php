<?php

namespace App\Entity;
use App\Entity\Evento;
use App\Repository\ParticipanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipanteRepository::class)]
class Participante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apellido = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $dni = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $sexo = null;
    
    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $fechaNacimiento = null;
    
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $edad = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $celular = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tutor1 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telefonoTutor1 = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tutor2 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telefonoTutor2 = null;
    
    
    #[ORM\Column(name: 'deQueParticipa', length: 255, nullable: false)]
    private ?string $deQueParticipa = null;
    
    #[ORM\Column(name: 'obra_social_nro_afiliado', type: 'string', length: 255, nullable: false)]
    private ?string $obraSocialnumeroAfiliado = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $observaciones= null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $dieta = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otraDieta = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $medicacion = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otraMedicacion = null;
    
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $dificultades = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otrasDificultades = null;

    #[ORM\ManyToOne(inversedBy: 'participantes')]
    private ?Localidad $localidad = null;

    #[ORM\ManyToOne(targetEntity: Evento::class, inversedBy: 'participantes')]
    private ?Evento $evento = null;

    

    /**
     * @var Collection<int, Inscripcion>
     */
    // Se establece la relación One-to-Many con la entidad Inscripcion.
    #[ORM\OneToMany(targetEntity: Inscripcion::class, mappedBy: 'participante', orphanRemoval: true, cascade: ['remove'])]
    private Collection $inscripcions;
    
   

    // Propiedad añadida para guardar el nombre del archivo PDF de la ficha médica
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $fichaMedicaFileName = null;

    #[ORM\Column(length: 25, nullable: false)]
    private ?string $nombreCredencial = null;

  

    public function __construct()
    {
        $this->inscripcions = new ArrayCollection();
    }
    
    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function setNombre(?string $nombre): static { $this->nombre = $nombre; return $this; }
    public function getApellido(): ?string { return $this->apellido; }
    public function setApellido(?string $apellido): static { $this->apellido = $apellido; return $this; }
    public function getDni(): ?string { return $this->dni; }
    public function setDni(?string $dni): static { $this->dni = $dni; return $this; }
    public function getSexo(): ?string { return $this->sexo; }
    public function setSexo(?string $sexo): static { $this->sexo = $sexo; return $this; }
    public function getFechaNacimiento(): ?\DateTimeInterface { return $this->fechaNacimiento; }
    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): static { $this->fechaNacimiento = $fechaNacimiento; return $this; }
    public function getEdad(): ?int { return $this->edad; }
    public function setEdad(?int $edad): static { $this->edad = $edad; return $this; }
    public function getCelular(): ?string { return $this->celular; }
    # tutores
    public function getTutor1(){return $this->tutor1;}
    public function setTutor1($tutor1){$this->tutor1 = $tutor1;return $this;}

   public function getTelefonoTutor1(){return $this->telefonoTutor1;}
   public function setTelefonoTutor1($telefonoTutor1){$this->telefonoTutor1 = $telefonoTutor1;return $this;}

  public function getTutor2(){return $this->tutor2;}
  public function setTutor2($tutor2){$this->tutor2 = $tutor2;return $this;}

  public function getTelefonoTutor2(){return $this->telefonoTutor2;}
  public function setTelefonoTutor2($telefonoTutor2){$this->telefonoTutor2 = $telefonoTutor2;return $this;}


    public function setCelular(?string $celular): static { $this->celular = $celular; return $this; }
    public function getDeQueParticipa(): ?string { return $this->deQueParticipa; }
    public function setDeQueParticipa(?string $deQueParticipa): static { $this->deQueParticipa = $deQueParticipa; return $this; }

    public function getObraSocialnumeroAfiliado(): ?string {
         return $this->obraSocialnumeroAfiliado; }

    public function setObraSocialnumeroAfiliado(?string $obraSocialnumeroAfiliado): static { 
        $this->obraSocialnumeroAfiliado = $obraSocialnumeroAfiliado; return $this; }

    public function getObservaciones(): ?string { 
        return $this->observaciones; }

    public function setObservaciones(?string $observaciones): static {
         $this->observaciones = $observaciones; return $this; }
         
    public function getDieta(): ?string { return $this->dieta; }
    public function setDieta(?string $dieta): static { $this->dieta = $dieta; return $this; }
    public function getOtraDieta(): ?string { return $this->otraDieta; }
    public function setOtraDieta(?string $otraDieta): static { $this->otraDieta = $otraDieta; return $this; }
    public function getMedicacion(): ?string { return $this->medicacion; }
    public function setMedicacion(?string $medicacion): static { $this->medicacion = $medicacion; return $this; }
    public function getOtraMedicacion(): ?string { return $this->otraMedicacion; }
    public function setOtraMedicacion(?string $otraMedicacion): static { $this->otraMedicacion = $otraMedicacion; return $this; }
    public function getDificultades(): ?string { return $this->dificultades; }
    public function setDificultades(?string $dificultades): static { $this->dificultades = $dificultades; return $this; }
    public function getOtrasDificultades(): ?string { return $this->otrasDificultades; }
    public function setOtrasDificultades(?string $otrasDificultades): static { $this->otrasDificultades = $otrasDificultades; return $this; }
    public function getLocalidad(): ?Localidad { return $this->localidad; }
    public function setLocalidad(?Localidad $localidad): static { $this->localidad = $localidad; return $this; }
    
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
            $inscripcion->setParticipante($this);
        }

        return $this;
    }

    public function removeInscripcion(Inscripcion $inscripcion): static
    {
        if ($this->inscripcions->removeElement($inscripcion)) {
            // set the owning side to null (unless already changed)
            if ($inscripcion->getParticipante() === $this) {
                $inscripcion->setParticipante(null);
            }
        }
        return $this;
    }


    // Métodos (getter y setter) añadidos para la nueva propiedad
    public function getFichaMedicaFileName(): ?string
    {
        return $this->fichaMedicaFileName;
    }

    public function setFichaMedicaFileName(?string $fichaMedicaFileName): static
    {
        $this->fichaMedicaFileName = $fichaMedicaFileName;
        return $this;
    }

    public function getNombreCredencial(): ?string
    {
        return $this->nombreCredencial;
    }

    public function setNombreCredencial(?string $nombreCredencial): static
    {
        $this->nombreCredencial = $nombreCredencial;

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
