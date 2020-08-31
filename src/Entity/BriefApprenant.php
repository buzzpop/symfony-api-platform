<?php

namespace App\Entity;

use App\Repository\BriefApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefApprenantRepository::class)
 */
class BriefApprenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="briefApprenants")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromos::class, inversedBy="briefApprenants")
     */
    private $briefMaPromos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;





    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getBriefMaPromos(): ?BriefMaPromos
    {
        return $this->briefMaPromos;
    }

    public function setBriefMaPromos(?BriefMaPromos $briefMaPromos): self
    {
        $this->briefMaPromos = $briefMaPromos;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


}
