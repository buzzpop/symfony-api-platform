<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      attributes={"denormalization_context"={"groups"={"post_groupe:write"}}},
 *     normalizationContext={"groups"={"groupes:read"}},
 *     collectionOperations={
 *
 *          "get_groupes"={
 *              "method"="GET",
 *              "path"="/admin/groupes",
 *              "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "post_groupes"={
 *              "method"="POST",
 *              "path"="/admin/groupes",
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get_apprenants_by_groupes"={
 *              "method"="GET",
 *              "path"="/api/admin/{groupes}/apprenants",
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="get_apprenants_by_groupes",
 *          },
 *     },
 *     itemOperations={
 *       "get"={
 *              "path"="/admin/groupes/{id}",
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *             },
 *       "put"={
 *              "path"="/admin/groupes/{id}",
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *             },
 *       "delete_apprenant"={
 *                  "method"="DELETE",
 *                  "path"="/api/admin/groupes/{id}/apprenant/{Id}",
 *                  "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *                  "access_control_message"="Vous n'avez pas access à cette Ressource",
 *                  "route_name"="delete_apprenant",
 *                },
 *     },
 * )
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupes:read","groupe:read","promos:write","brief:read","profilSAppS:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupes:read", "groupe:read","post_groupe:write","brief:read","profilSAppS:read"})
     * @Assert\NotBlank(message="Ajouter le nom du Groupe")
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupes:read", "groupe:read","post_groupe:write","brief:read","profilSAppS:read"})
     *
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupes:read", "groupe:read","post_groupe:write","brief:read","profilSAppS:read"})
     * @Assert\NotBlank(message="Ajouter le statut du Groupe")
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupes:read", "groupe:read","post_groupe:write","brief:read","profilSAppS:read"})
     * @Assert\NotBlank(message="Ajouter le type du Groupe")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="groupe")
     *  @Groups({"groupes:read","profilSAppS:read"})
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     *@Groups({"groupes:read","promo_principal:read","post_groupe:write","brief:read","profilSAppS:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes",cascade={"persist"})
     *  @Groups({"groupes:read"})
     * 
     *
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="groupe")
     */
    private $etatBriefGroupes;



   
    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreation(): ?DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
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
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
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
            $etatBriefGroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes->removeElement($etatBriefGroupe);
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getGroupe() === $this) {
                $etatBriefGroupe->setGroupe(null);
            }
        }

        return $this;
    }



}
