<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromosRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromosRepository::class)
 * @ApiResource(
 *     attributes={ "input_formats"={"json"={"application/json"}}, "denormalization_context"={"groups"={"promos:write"}},},
 * collectionOperations={
 *  "get"={
 *      "path":"admin/promos",
 *      "normalization_context"={"groups"={"groupe:read"}},
 *  },"post"={
 *      "path"="admin/promos",
 *      "normalization_context"={"groups"={"groupe:read"}},
 *
 *      "get_promo_principal"={
 *              "method"="GET",
 *              "path"="/admin/promo/principal",
 *              "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *
 *     "get_promos"={
 *  "normalization_context"={"groups"={"groupe:read"}},
 *     "method"="GET",
 *     "path"="/api/admin/promos",
 *     "access_control"="(is_granted('ROLE_Administrateur') )",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *     "post_promos"={
 * "method"="POST",
 * "path"="/api/admin/promo",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_promos",
 *      },
 * },
 * },
 * itemOperations={
 *  "get"={
 *      "path":"admin/promo/{id}",
 *      "normalization_context"={"groups":"groupe:read"}
 * },
 * "put"={
 *      "path":"admin/promo/{id}"
 *   },
 * 
 * }
 * 
 * 
 * )
 */
class Promos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read","brief:read"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la langue")
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter le titre")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     *
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la reference Agate")
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la date début")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la date fin privisoire")
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="initialiser la fabrique a Sonatel Academy")
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter la date de fin relle")
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read","promos:write","brief:read"})
     * @Assert\NotBlank(message="ajouter l'état")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promos",cascade={"persist"})
     * @Groups({"groupe:read","promo_principal:read","promos:write","profilSAppS:read"})
     */
    private $groupe;


    /**
    * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="promos")
    *@Groups({"groupe:read","promo_principal:read","promos:write"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromos::class, mappedBy="Promos")
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="promos")
     */
    private $chats;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="promos")
     */
    private $competenceValides;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class, inversedBy="promos")
     * @Groups({"post_promo:read","brief:read"})
     */
    private $referentiel;



    
    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addPromo($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
            $formateur->removePromo($this);
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
            $briefMaPromo->setPromos($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromos $briefMaPromo): self
    {
        if ($this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos->removeElement($briefMaPromo);
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromos() === $this) {
                $briefMaPromo->setPromos(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setPromos($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
            // set the owning side to null (unless already changed)
            if ($chat->getPromos() === $this) {
                $chat->setPromos(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompetenceValide[]
     */
    public function getCompetenceValides(): Collection
    {
        return $this->competenceValides;
    }

    public function addCompetenceValide(CompetenceValide $competenceValide): self
    {
        if (!$this->competenceValides->contains($competenceValide)) {
            $this->competenceValides[] = $competenceValide;
            $competenceValide->setPromos($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getPromos() === $this) {
                $competenceValide->setPromos(null);
            }
        }

        return $this;
    }

    public function getReferentiel(): ?Referentiels
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiels $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }


}
