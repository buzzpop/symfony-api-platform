<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @ApiResource (
 *  normalizationContext={"groups"={"profilSortie:read"}},
 *   collectionOperations={
 *     "get"={"path"="/admin/profilSorties"},

 *
 * 
 *      "get_apprenant_by_promoPS"={
 *              "method"="GET",
 *                  "path"="/api/admin/promo/{id}/profilsorties",
 *                  "access_control"="(is_granted('ROLE_Administrateur'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource",
 *                  "route_name"="apprenant_by_promoPS",
 *          },
 * 
 *       "post_profilSorties"={
 *           "method"="POST",
 *           "path"="/api/admin/profilSorties",
 *           "access_control"="(is_granted('ROLE_Administrateur'))",
 *           "access_control_message"="Vous n'avez pas access à cette Ressource",
 *           "route_name"="post_profilSorties",
 *      },
 * },
 *
 *
 *
 *
 * )
 */
class ProfilSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"profilSortie:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profilSortie:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     * @Groups({"profilSApp:read","profilSAppS:read","profilSortie:read"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
    }

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

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removeProfilSortie($this);
        }

        return $this;
    }

    
}
