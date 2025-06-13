<?php

namespace App\Entity;

use App\Repository\CentroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentroRepository::class)]
class Centro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    /**
     * @var Collection<int, Titulacion>
     */
    #[ORM\ManyToMany(targetEntity: Titulacion::class, mappedBy: 'centros')]
    private Collection $titulacions;

    #[ORM\Column]
    private ?bool $activo = null;

    /**
     * @var Collection<int, Matricula>
     */
    #[ORM\OneToMany(targetEntity: Matricula::class, mappedBy: 'centro')]
    private Collection $matriculas;

    public function __construct()
    {
        $this->titulacions = new ArrayCollection();
        $this->matriculas = new ArrayCollection();
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Collection<int, Titulacion>
     */
    public function getTitulacions(): Collection
    {
        return $this->titulacions;
    }

    public function addTitulacion(Titulacion $titulacion): static
    {
        if (!$this->titulacions->contains($titulacion)) {
            $this->titulacions->add($titulacion);
            $titulacion->addCentro($this);
        }

        return $this;
    }

    public function removeTitulacion(Titulacion $titulacion): static
    {
        if ($this->titulacions->removeElement($titulacion)) {
            $titulacion->removeCentro($this);
        }

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection<int, Matricula>
     */
    public function getMatriculas(): Collection
    {
        return $this->matriculas;
    }

    public function addMatricula(Matricula $matricula): static
    {
        if (!$this->matriculas->contains($matricula)) {
            $this->matriculas->add($matricula);
            $matricula->setCentro($this);
        }

        return $this;
    }

    public function removeMatricula(Matricula $matricula): static
    {
        if ($this->matriculas->removeElement($matricula)) {
            // set the owning side to null (unless already changed)
            if ($matricula->getCentro() === $this) {
                $matricula->setCentro(null);
            }
        }

        return $this;
    }
}
