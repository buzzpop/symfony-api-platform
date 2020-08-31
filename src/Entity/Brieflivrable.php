<?php

namespace App\Entity;

use App\Repository\BrieflivrableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrieflivrableRepository::class)
 */
class Brieflivrable
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
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=Livrable::class, mappedBy="brieflivrable")
     */
    private $livrable;

    /**
     * @ORM\OneToMany(targetEntity=brief::class, mappedBy="brieflivrable")
     */
    private $brief;

    public function __construct()
    {
        $this->livrable = new ArrayCollection();
        $this->brief = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|livrable[]
     */
    public function getLivrable(): Collection
    {
        return $this->livrable;
    }

    public function addLivrable(livrable $livrable): self
    {
        if (!$this->livrable->contains($livrable)) {
            $this->livrable[] = $livrable;
            $livrable->setBrieflivrable($this);
        }

        return $this;
    }

    public function removeLivrable(livrable $livrable): self
    {
        if ($this->livrable->contains($livrable)) {
            $this->livrable->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getBrieflivrable() === $this) {
                $livrable->setBrieflivrable(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|brief[]
     */
    public function getBrief(): Collection
    {
        return $this->brief;
    }

    public function addBrief(brief $brief): self
    {
        if (!$this->brief->contains($brief)) {
            $this->brief[] = $brief;
            $brief->setBrieflivrable($this);
        }

        return $this;
    }

    public function removeBrief(brief $brief): self
    {
        if ($this->brief->contains($brief)) {
            $this->brief->removeElement($brief);
            // set the owning side to null (unless already changed)
            if ($brief->getBrieflivrable() === $this) {
                $brief->setBrieflivrable(null);
            }
        }

        return $this;
    }
}
