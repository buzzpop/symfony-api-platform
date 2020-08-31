<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"competences:read"}},
 *     collectionOperations={
 *     "get"={"path"="/admin/competences","normalization_context"={"groups"={"niveaux:read"}}},
 *
 *      "post_competence"={
 * "method"="POST",
 * "path"="/api/admin/competences",
 *      "denormalization_context"={"groups"={"postC:read"}},
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_competence",
 *      },
 *  },
 *  itemOperations={
            "get"={"path"="/admin/competences/{id}", "normalization_context"={"groups"={"nivAssocié:read"}}},
 *
 *      "put_competence"={
 * "method"="PUT",
 * "path"="/api/admin/competences/{id}",
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="put_competence",
 *      },
 *     }
 *
 * )
 */
class Competences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *@Groups({"competences:read","grpecomp:read","comp:read","CGR:read","postC:read","brief:read","briefbrouillon:read","briefV:read","briefinP:read","competences:read","grp"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competences:read","grpecomp:read","comp:read","CGR:read","postC:read","brief:read","briefbrouillon:read","briefV:read","briefinP:read","competences:read","grp"})
     * @Assert\NotBlank(message="Le champs Libelle ne doit pas etre vide")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="competence" )
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competences")
     * @Groups({"nivAssocié:read","postC:read"})
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValide::class, mappedBy="competence")
     */
    private $competenceValides;



    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->competenceValides = new ArrayCollection();
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
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetences() === $this) {
                $niveau->setCompetences(null);
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
            $competenceValide->setCompetence($this);
        }

        return $this;
    }

    public function removeCompetenceValide(CompetenceValide $competenceValide): self
    {
        if ($this->competenceValides->contains($competenceValide)) {
            $this->competenceValides->removeElement($competenceValide);
            // set the owning side to null (unless already changed)
            if ($competenceValide->getCompetence() === $this) {
                $competenceValide->setCompetence(null);
            }
        }

        return $this;
    }

}
