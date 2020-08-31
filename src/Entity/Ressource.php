<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 * @ApiResource()
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     */
    private $piegejoindre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=brief::class, inversedBy="ressources")
     */
    private $brief;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPiegejoindre(): ?string
    {
        return $this->piegejoindre;
    }

    public function setPiegejoindre(string $piegejoindre): self
    {
        $this->piegejoindre = $piegejoindre;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getBrief(): ?brief
    {
        return $this->brief;
    }

    public function setBrief(?brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }
}
