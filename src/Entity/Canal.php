<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CanalRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"nombreCanal"}, message="There is already an account with this email")
 */
class Canal implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $sexo;

    /**
     * @ORM\Column(type="date")
     */
    private $fnac;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="idCanal", orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Suscripcion", mappedBy="idCanal")
     */
    private $misSuscripciones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentario", mappedBy="idCanalComentado")
     */
    private $comentarios;


    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->misSuscripciones = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getFnac(): ?\DateTimeInterface
    {
        return $this->fnac;
    }

    public function setFnac(\DateTimeInterface $fnac): self
    {
        $this->fnac = $fnac;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setIdCanal($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getIdCanal() === $this) {
                $video->setIdCanal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Suscripcion[]
     */
    public function getMisSuscripciones(): Collection
    {
        return $this->misSuscripciones;
    }

    public function addMisSuscripcione(Suscripcion $misSuscripcione): self
    {
        if (!$this->misSuscripciones->contains($misSuscripcione)) {
            $this->misSuscripciones[] = $misSuscripcione;
            $misSuscripcione->setIdCanal($this);
        }

        return $this;
    }

    public function removeMisSuscripcione(Suscripcion $misSuscripcione): self
    {
        if ($this->misSuscripciones->contains($misSuscripcione)) {
            $this->misSuscripciones->removeElement($misSuscripcione);
            // set the owning side to null (unless already changed)
            if ($misSuscripcione->getIdCanal() === $this) {
                $misSuscripcione->setIdCanal(null);
            }
        }

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
            $comentario->setIdCanalComentado($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getIdCanalComentado() === $this) {
                $comentario->setIdCanalComentado(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->nombre;
    }

}
