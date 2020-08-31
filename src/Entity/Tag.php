<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"Tag:read"}},
 *      collectionOperations={
 *     "get"={"path"="/admin/tags"},
 *     "post"={"path"="/admin/tags"},
 * },
 *     itemOperations={
        "get"={"path"="/admin/tags/{id}", "normalization_context"={"groups"={"tagId:read"}}},
        "put"={"path"="/admin/tags/{id}"},
 *     }
 * 
 * )
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"Tag:read","tagId:read","grptag:read","tags","brief:read","briefbrouillon:read","briefV:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Tag:read","tagId:read","grptag:read","tags","brief:read","briefbrouillon:read","briefV:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Tag:read","tagId:read","grptag:read","tags","brief:read","briefbrouillon:read","briefV:read"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, inversedBy="tags")
     * @Groups({"Tag:read","tagId:read"})
     */
    private $GroupeTag;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="Tags")
     */
    private $briefs;

    public function __construct()
    {
        $this->GroupeTag = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTag(): Collection
    {
        return $this->GroupeTag;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->GroupeTag->contains($groupeTag)) {
            $this->GroupeTag[] = $groupeTag;
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->GroupeTag->contains($groupeTag)) {
            $this->GroupeTag->removeElement($groupeTag);
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeTag($this);
        }

        return $this;
    }
}
