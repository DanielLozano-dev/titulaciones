<?php

namespace App\Entity;

use App\Repository\TareasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareasRepository::class)]
class Tareas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fechaCreacion = null;

    #[ORM\Column]
    private ?bool $completada = null;

    #[ORM\ManyToOne(inversedBy: 'tareas')]
    private ?Matricula $matricula = null;



    /**
     * @param string|null $descripcion
     * @param bool|null $completada
     * @param \DateTime|null $fechaCreacion
     */
    public function __construct(?string $descripcion)
    {
        $this->descripcion = $descripcion;
        $this->completada = false;
        $this->fechaCreacion = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTime $fechaCreacion): static
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function isCompletada(): ?bool
    {
        return $this->completada;
    }

    public function setCompletada(bool $completada): static
    {
        $this->completada = $completada;

        return $this;
    }

    public function getMatricula(): ?Matricula
    {
        return $this->matricula;
    }

    public function setMatricula(?Matricula $matricula): static
    {
        $this->matricula = $matricula;

        return $this;
    }


}
