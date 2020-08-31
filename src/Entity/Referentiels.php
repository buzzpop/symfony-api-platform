<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielsRepository::class)
 * @ApiResource(
 *       normalizationContext={"groups"={"ref_grpe:read"},"groups"={"CGR:read"},},
 *      collectionOperations={
 *     "get_referentiels"={
 *     "method"="GET",
 *     "path"="/admin/referentiels",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *     "post_ref"={
 * "method"="POST",
 * "path"="/api/admin/referentiels",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_ref",
 *      },
 *   },
 *       itemOperations={
 *       "get"={"path"="/admin/referentiels/{id}","normalization_context"={"groups"={"grpcmp_by_ref"}}},
 *       "put"={"path"="/admin/referentiels/{id}"},
 *      "post_ref"={
 * "method"="POST",
 * "path"="/api/admin/referentiels",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_ref",
 *      },
 *
 *     *"get_competences_by_group_id_by_ref_id"={
 * "method"="GET",
 * "path"="/api/admin/referentiels/{idr}/grpecompetences/{idg}",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_competences_by_group_id_by_ref_id",
 *
 *
 *      },
 *
 *     },
 * )
 */
class Referentiels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     * @Assert\NotBlank(message="Le libelle est Obligatoire")
     *
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     * @Assert\NotBlank(message="La presentation  est Obligatoire")
     */
    private $presentation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     * @Assert\NotBlank(message="Le Programme est Obligatoire")
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     * @Assert\NotBlank(message="Le critere d'Admission est Obligatoire")
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="text")
     * @Groups({"ref_grpe:read", "groupe:read","promo_principal:read"})
     * @Assert\NotBlank(message="Le critere d'Evaluation est Obligatoire")
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels")
     *@Groups({"ref_grpe:read","grpcmp_by_ref","promo_principal:read","CGR:read"})
     */
    private $groupeCompetences;



    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="referenciel")
     */
    private $competenceValides;

    /**
     * @ORM\OneToMany(targetEntity=Promos::class, mappedBy="referentiel")
     */
    private $promos;

    
    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

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

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
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
            $competenceValide->setReferenciel($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getReferenciel() === $this) {
                $competenceValide->setReferenciel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promos[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promos $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promos $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

   
}
