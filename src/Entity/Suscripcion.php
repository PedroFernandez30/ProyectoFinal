<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuscripcionRepository")
 */
class Suscripcion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal", inversedBy="misSuscripciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idCanal;

    public function getId(): ?int
    {
        return $this->id;
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
}
