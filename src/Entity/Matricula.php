<?php

namespace App\Entity;

use App\Repository\MatriculaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatriculaRepository::class)]
class Matricula
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $fecha_incio = null;

    #[ORM\Column]
    private ?\DateTime $fecha_fin = null;

    #[ORM\ManyToOne(inversedBy: 'matricula')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'matriculas')]
    private ?Centro $centro = null;

    #[ORM\ManyToOne(targetEntity: Titulacion::class)]
    private ?Titulacion $titulacion = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'titulacion')]
    private Collection $matriculas;

    #[ORM\Column]
    private ?bool $activa = null;

    /**
     * @var Collection<int, Tareas>
     */
    #[ORM\OneToMany(targetEntity: Tareas::class, mappedBy: 'matricula')]
    private Collection $tareas;

    public function __construct()
    {
        $this->matriculas = new ArrayCollection();
        $this->activa = true;
        $this->tareas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaIncio(): ?\DateTime
    {
        return $this->fecha_incio;
    }

    public function setFechaIncio(\DateTime $fecha_incio): static
    {
        $this->fecha_incio = $fecha_incio;

        return $this;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTime $fecha_fin): static
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCentro(): ?Centro
    {
        return $this->centro;
    }

    public function setCentro(?Centro $centro): static
    {
        $this->centro = $centro;

        return $this;
    }

    public function getTitulacion(): ?Titulacion
    {
        return $this->titulacion;
    }

    public function setTitulacion(?Titulacion $titulacion): static
    {
        $this->titulacion = $titulacion;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMatriculas(): Collection
    {
        return $this->matriculas;
    }

    public function addMatricula(self $matricula): static
    {
        if (!$this->matriculas->contains($matricula)) {
            $this->matriculas->add($matricula);
            $matricula->setTitulacion($this);
        }

        return $this;
    }

    public function removeMatricula(self $matricula): static
    {
        if ($this->matriculas->removeElement($matricula)) {
            // set the owning side to null (unless already changed)
            if ($matricula->getTitulacion() === $this) {
                $matricula->setTitulacion(null);
            }
        }

        return $this;
    }

    public function isActiva(): ?bool
    {
        return $this->activa;
    }

    public function setActiva(bool $activa): static
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * @return Collection<int, Tareas>
     */
    public function getTareas(): Collection
    {
        return $this->tareas;
    }

    public function addTarea(Tareas $tarea): static
    {
        if (!$this->tareas->contains($tarea)) {
            $this->tareas->add($tarea);
            $tarea->setMatricula($this);
        }

        return $this;
    }

    public function removeTarea(Tareas $tarea): static
    {
        if ($this->tareas->removeElement($tarea)) {
            // set the owning side to null (unless already changed)
            if ($tarea->getMatricula() === $this) {
                $tarea->setMatricula(null);
            }
        }

        return $this;
    }
}
