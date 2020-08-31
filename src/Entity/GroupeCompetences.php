<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 * @ApiResource(
 *
 *      normalizationContext={"groups"={"grpe:read", "grpecomp:read"}},
 *     collectionOperations={
 *     "get_competences"={
 *     "method"="GET",
 *     "path"="/admin/grpecompetences",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *     "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *     "get_competences_by_group"={
 * "method"="GET",
 * "path"="/admin/grpecompetences/competences",
 *     "normalization_context"={"groups"={"comp:read"}},
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *      },
 *     "get_gComp_Comp"={
 *     "method"="GET",
 *     "path"="/admin/referentiels/grpecompetences",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     "route_name"="get_gComp_Comp",
 *
 *     },
 *     "post_groupe_competences"={
 * "method"="POST",
 * "path"="/api/admin/grpecompetences",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_groupe_competences",
 *      },
 * },
 *      itemOperations={
 *     "get"={"path"="/admin/grpecompetences/{id}","security"="is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM')"},
 *     "put"={"path"="/admin/grpecompetences/{id}","security"="is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM')"},
 *
 *  "get_competences_by_group_id"={
 * "method"="GET",
 * "path"="/api/admin/grpecompetences/{id}/competences" ,
 * "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_competences_by_group_id",
 *
 * },
 *
 *     },
 *
 * )
 */
class GroupeCompetences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grpe:read","grpecomp:read","ref_grpe:read","grpcmp_by_ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpe:read","grpecomp:read","ref_grpe:read","grpcmp_by_ref"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"grpe:read","grpecomp:read","ref_grpe:read","grpcmp_by_ref"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="groupeCompetences")
     * @Groups({"grpecomp:read","comp:read","CGR:read"})
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiels::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="grpCompetence")
     */
    private $user;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Competences[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competence->contains($competence)) {
            $this->competence->removeElement($competence);
        }

        return $this;
    }

    /**
     * @return Collection|Referentiels[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiels $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiels $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
            $this->referentiels->removeElement($referentiel);
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
