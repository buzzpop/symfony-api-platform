<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Controller\BriefController;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"brief:read"}},
 *      collectionOperations={
 *     "get_brief"={
 *     "method"="GET",
 *     "path"="/api/formateurs/briefs",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     "route_name"="get_brief",
 *     },
 *
 *      "get_brief_groupe_promo"={
 *     "method"="GET",
 *     "path"="/api/formateurs/promo/{id_p}/groupe/{id_g}/briefs",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      "route_name"="get_brief_groupe_promo",
 *
 *     },
 *      "get_brief_promo"={
 *     "method"="GET",
 *     "path"="/api/formateurs/promos/{id}/briefs",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      "route_name"="get_brief_promo",
 *
 *     },
 *
 *      "get_brief_brouillons_formateurs"={
 *     "method"="GET",
 *     "path"="/api/formateurs/{id}/briefs/brouillons",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      "route_name"="get_brief_brouillons_formateurs",
 *     },
 *
 *      "get_brief_valide"={
 *     "method"="GET",
 *     "path"="/api/formateurs/{id}/briefs/valide",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      "route_name"="get_brief_valide",
 *
 *     },
 *      "get_livrable_partiel_by_brief"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="get_livrable_partiel_by_brief",
 *          },
 *          "add_url"={
 *              "access_control"="(is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_url",
 *          },
 *          "add_brief"={
 *              "access_control"="(is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_brief",
 *          },
 *          "dupliquer_brief"={
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="dupliquer_brief",
 *          },
 *
 *
 *     },
 *     itemOperations={
 *      "get",
 *      "get"={
 *      "get_briefbyPromos"={
 *          "method"="GET",
 *          "path"="/formateurs/promo/{id_p}/briefs/{id_b}",
 *          "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name"="get_briefbyPromos",
 *      },
 *     },
 *
 *          "put_brief"={
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_brief",
 *          },
 *          "affecter_brief"={
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="affecter_brief",
 *          },
 *     }
 *
 * )
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief:read","briefV:read"})
     */

    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefV:read"})
     *
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefV:read"})
     */
    private $nomBrief;

    /**
     * @ORM\Column(type="text")
    *@Groups({"brief:read","briefV:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
   * @Groups({"brief:read","briefV:read"})
     */
    private $contexte;



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefV:read"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","briefV:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"brief:read","briefV:read"})
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="blob")
     */
    private $imagePromos;

    /**
     * @ORM\Column(type="boolean")
     *@Groups({"brief:read","briefV:read"})
     */
    private $archiver;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"brief:read","briefV:read"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief:read","briefV:read"})
     *
     */
    private $etat_brouillons_assigne_valide;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     *  @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     *
     */
    private $Tags;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     *  @Groups({"brief:read","briefbrouillon:read"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     * @Groups({"brief:read","briefbrouillon:read","briefV:read","briefinP:read"})
     *
     */
    private $niveau;


    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     *  @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     *
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromos::class, mappedBy="brief")
     *  @Groups({"brief:read"})
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="brief")
     *  @Groups({"brief:read"})
     */
    private $etatBriefGroupes;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="brief")
     *  @Groups({"brief:read","briefbrouillon:read","briefV:read"})
     *
     *
     */
    private $livrableAttendus;


    public function __construct()
    {
        $this->Tags = new ArrayCollection();
        $this->niveau = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
        $this->livrableAttendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getNomBrief(): ?string
    {
        return $this->nomBrief;
    }

    public function setNomBrief(string $nomBrief): self
    {
        $this->nomBrief = $nomBrief;

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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }



    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getImagePromos()
    {
        return $this->imagePromos ;
    }

    public function setImagePromos($imagePromos): self
    {
        $this->imagePromos = $imagePromos;

        return $this;
    }

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(bool $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getEtatBrouillonsAssigneValide(): ?string
    {
        return $this->etat_brouillons_assigne_valide;
    }

    public function setEtatBrouillonsAssigneValide(string $etat_brouillons_assigne_valide): self
    {
        $this->etat_brouillons_assigne_valide = $etat_brouillons_assigne_valide;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->Tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->Tags->contains($tag)) {
            $this->Tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->Tags->contains($tag)) {
            $this->Tags->removeElement($tag);
        }

        return $this;
    }

    public function getFormateurs(): ?Formateur
    {
        return $this->formateurs;
    }

    public function setFormateurs(?Formateur $formateurs): self
    {
        $this->formateurs = $formateurs;

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


    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefMaPromos[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromos $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromos $briefMaPromo): self
    {
        if ($this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos->removeElement($briefMaPromo);
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBrief() === $this) {
                $briefMaPromo->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes->removeElement($etatBriefGroupe);
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getBrief() === $this) {
                $etatBriefGroupe->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus->removeElement($livrableAttendu);
            $livrableAttendu->removeBrief($this);
        }

        return $this;
    }

}
