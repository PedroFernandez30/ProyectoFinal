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
    private $canalComentado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal")
     * @ORM\JoinColumn(nullable=true)
     */
    private $canalQueComenta;

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

    public function getCanalComentado(): ?Canal
    {
        return $this->canalComentado;
    }

    public function setCanalComentado(?Canal $canalComentado): self
    {
        $this->canalComentado = $canalComentado;

        return $this;
    }

    public function getCanalQueComenta(): ?Canal
    {
        return $this->canalQueComenta;
    }

    public function setCanalQueComenta(?Canal $canalQueComenta): self
    {
        $this->canalQueComenta = $canalQueComenta;

        return $this;
    }
}
