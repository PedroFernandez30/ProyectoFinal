<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComentarioRepository")
 */
class Comentario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenido;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaComentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Video", inversedBy="comentarios")
     */
    private $idVideo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal", inversedBy="comentarios")
     */
    private $idCanalComentado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCanalQueComenta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getFechaComentario(): ?\DateTimeInterface
    {
        return $this->fechaComentario;
    }

    public function setFechaComentario(\DateTimeInterface $fechaComentario): self
    {
        $this->fechaComentario = $fechaComentario;

        return $this;
    }

    public function getIdVideo(): ?Video
    {
        return $this->idVideo;
    }

    public function setIdVideo(?Video $idVideo): self
    {
        $this->idVideo = $idVideo;

        return $this;
    }

    public function getIdCanalComentado(): ?Canal
    {
        return $this->idCanalComentado;
    }

    public function setIdCanalComentado(?Canal $idCanalComentado): self
    {
        $this->idCanalComentado = $idCanalComentado;

        return $this;
    }

    public function getIdCanalQueComenta(): ?Canal
    {
        return $this->idCanalQueComenta;
    }

    public function setIdCanalQueComenta(?Canal $idCanalQueComenta): self
    {
        $this->idCanalQueComenta = $idCanalQueComenta;

        return $this;
    }
}
