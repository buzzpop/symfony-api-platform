<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefMaPromosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefMaPromosRepository::class)

 * @ApiResource(
 * )

 */
class BriefMaPromos
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
    private $statut_cloture_EnCour;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="briefMaPromos")
     * @Groups({"brief:read","briefbrouillon:read"})
     */
    private $Promos;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefMaPromos")
     */
    private $brief;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="briefMaPromos")
     *  @Groups({"briefbrouillon:read"})
     */
    private $livrablePartiel;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="briefMaPromos")
     */
    private $briefApprenants;



    public function __construct()
    {
        $this->livrablePartiel = new ArrayCollection();
        $this->briefApprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatutClotureEnCour(): ?string
    {
        return $this->statut_cloture_EnCour;
    }

    public function setStatutClotureEnCour(string $statut_cloture_EnCour): self
    {
        $this->statut_cloture_EnCour = $statut_cloture_EnCour;

        return $this;
    }

    public function getPromos(): ?promos
    {
        return $this->Promos;
    }

    public function setPromos(?promos $Promos): self
    {
        $this->Promos = $Promos;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiel(): Collection
    {
        return $this->livrablePartiel;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiel->contains($livrablePartiel)) {
            $this->livrablePartiel[] = $livrablePartiel;
            $livrablePartiel->setBriefMaPromos($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiel->contains($livrablePartiel)) {
            $this->livrablePartiel->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBriefMaPromos() === $this) {
                $livrablePartiel->setBriefMaPromos(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenants(): Collection
    {
        return $this->briefApprenants;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants[] = $briefApprenant;
            $briefApprenant->setBriefMaPromos($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenants->contains($briefApprenant)) {
            $this->briefApprenants->removeElement($briefApprenant);
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getBriefMaPromos() === $this) {
                $briefApprenant->setBriefMaPromos(null);
            }
        }

        return $this;
    }


}
