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
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal", inversedBy="suscritosAMi")
     * @ORM\JoinColumn(nullable=false)
     */
    private $canalAlQueSuscribe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Canal", inversedBy="misSuscripciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $canalQueSuscribe;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanalAlQueSuscribe(): ?Canal
    {
        return $this->canalAlQueSuscribe;
    }

    public function setCanalAlQueSuscribe(?Canal $canalAlQueSuscribe): self
    {
        $this->canalAlQueSuscribe = $canalAlQueSuscribe;

        return $this;
    }

    public function getCanalQueSuscribe(): ?Canal
    {
        return $this->canalQueSuscribe;
    }

    public function setCanalQueSuscribe(?Canal $canalQueSuscribe): self
    {
        $this->canalQueSuscribe = $canalQueSuscribe;

        return $this;
    }

}
