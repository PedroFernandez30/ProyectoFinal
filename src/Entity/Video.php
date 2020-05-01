<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     */
    private $mg;

    /**
     * @ORM\Column(type="integer")
     */
    private $dislike;

    /**
     * @ORM\Column(type="time")
     */
    private $duracion;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaPublicacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCanal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCategoria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentario", mappedBy="idVideo")
     */
    private $comentarios;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getMg(): ?int
    {
        return $this->mg;
    }

    public function setMg(int $mg): self
    {
        $this->mg = $mg;

        return $this;
    }

    public function getDislike(): ?int
    {
        return $this->dislike;
    }

    public function setDislike(int $dislike): self
    {
        $this->dislike = $dislike;

        return $this;
    }

    public function getDuracion(): ?\DateTimeInterface
    {
        return $this->duracion;
    }

    public function setDuracion(\DateTimeInterface $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getIdCanal(): ?Canal
    {
        return $this->idCanal;
    }

    public function setIdCanal(?Canal $idCanal): self
    {
        $this->idCanal = $idCanal;

        return $this;
    }

    public function getIdCategoria(): ?Categoria
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(?Categoria $idCategoria): self
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * @return Collection|Comentario[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setIdVideo($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getIdVideo() === $this) {
                $comentario->setIdVideo(null);
            }
        }

        return $this;
    }
}