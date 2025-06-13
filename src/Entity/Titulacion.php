<?php

namespace App\Entity;

use App\Repository\TitulacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitulacionRepository::class)]
class Titulacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\Column]
    private ?int $horas = null;

    #[ORM\Column]
    private ?bool $activa = null;

    /**
     * @var Collection<int, Centro>
     */
    #[ORM\ManyToMany(targetEntity: Centro::class, inversedBy: 'titulacions')]
    private Collection $centros;

    public function __construct()
    {
        $this->centros = new ArrayCollection();
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

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getHoras(): ?int
    {
        return $this->horas;
    }

    public function setHoras(int $horas): static
    {
        $this->horas = $horas;

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
     * @return Collection<int, Centro>
     */
    public function getCentros(): Collection
    {
        return $this->centros;
    }

    public function addCentro(Centro $centro): static
    {
        if (!$this->centros->contains($centro)) {
            $this->centros->add($centro);
        }

        return $this;
    }

    public function removeCentro(Centro $centro): static
    {
        $this->centros->removeElement($centro);

        return $this;
    }
}
