<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 *
 *      normalizationContext={"groups"={"grptag:read"}},
 *      collectionOperations={
 *     "get"={"path"="/admin/grptags"},
 *     "post"={"path"="/admin/grptags"},
 * },
 *     itemOperations={
 *      "get"={"path"="/admin/grptags/{id}"},
 *     "get"={"path"="/admin/grptags/{id}/tags", "normalization_context"={"groups"={"tags"}}},
 *
 *    "get_apprenant_by_id"={
 * "method"="PUT",
 * "path"="/api/apprenants/{id}" ,
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     "route_name"="put_tags",
 *     },
 *
 *     }
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"Tag:read","tagId:read","grptag:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"Tag:read","tagId:read","grptag:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="GroupeTag")
     * @Groups({"grptag:read","tags"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addGroupeTag($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeGroupeTag($this);
        }

        return $this;
    }
}
