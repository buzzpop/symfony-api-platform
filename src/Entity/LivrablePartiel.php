<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\LivrablePartielController;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "getAppByComp"={
 *         "method"="GET",
 *         "path"="/api/formateurs/promo/{id_promo}/referentiel/{id_ref}/competences",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_Admin') or is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="getAppByComp"
 *     },
 *
 *      "form_stat"={
 *         "method"="GET",
 *         "path"="api/formateur/promo/{idp}/referentiels/{idr}/statistique/competence",
 *         "controller"=LivrablePartielController::class,
 *         "route_name"="form_stat"
 *     },
 *      "post_formateur"={
 *              "path"="formateurs/livrablepartiels/{id}/commentaires",
 *              "route_name"="post_formateur",
 *          },
 *     "get_livrablepartiel3"={
 *     "method"="GET",
 *     "path"="/formateurs/livrablepartiels/{id}/commentaires",
 *     "controller":"App\Controller\LivrablePartielController::class",
 *     "route_name"="recuperer_les_commentaires",
 *      },
 *      "get_apprenant_briefs_valides"={
 *         "method"="GET",
 *         "path"="/api/apprenants/{id}/promo/{idc}/referentiel/{ide}/statistiques/briefs",
 *         "access_control"="(is_granted('ROLE_Admin') or is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="get_apprenant_briefs_valides"
 *     },
 *
 *      },
 *
 *     itemOperations={
 *      "get"={},
 *            "get_deux_it"={
 *          "method"="PUT",
 *         "path"="/api/apprenants/{id}/livrablepartiels/{id_d}",
 *         "controller"=LivrablePartielController::class,
 *         "access_control"="(is_granted('ROLE_Admin') or is_granted('ROLE_FORMATEUR'))",
 *         "route_name"="get_deux_it"
 *     },
 *     }
 * )
 */
class LivrablePartiel
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
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreRendus;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreCorriger;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     */
    private $niveau;



    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromos::class, inversedBy="livrablePartiel")
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="livrablePartiel")
     *
     */
    private $apprenantLivrablePartiels;

    public function __construct()
    {
        $this->niveau = new ArrayCollection();
        $this->apprenantLivrablePartiels = new ArrayCollection();
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

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getNbreRendus(): ?int
    {
        return $this->nbreRendus;
    }

    public function setNbreRendus(int $nbreRendus): self
    {
        $this->nbreRendus = $nbreRendus;

        return $this;
    }

    public function getNbreCorriger(): ?int
    {
        return $this->nbreCorriger;
    }

    public function setNbreCorriger(int $nbreCorriger): self
    {
        $this->nbreCorriger = $nbreCorriger;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->contains($niveau)) {
            $this->niveau->removeElement($niveau);
        }

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

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel);
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getLivrablePartiel() === $this) {
                $apprenantLivrablePartiel->setLivrablePartiel(null);
            }
        }

        return $this;
    }
}
