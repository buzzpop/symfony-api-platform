<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"chat:read"}},
 *   collectionOperations={
 * "get_commentaire"={
 * "method"="GET",
 * "path"="/api/users/promo/{id}/apprenant/{id1}/chats",
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_Apprenant') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="commentaire",
 * },
 *  "post_commentaire"={
 *      "method"="POST",
 *      "path"="/api/users/promo/{id}/apprenant/{id1}/chats",
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_Apprenant') or is_granted('ROLE_CM'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *       "route_name"="post_commentaire",
 *      },
 * },
 *
 *itemOperations={
*  "get"={}
 * }
 * 
 * )
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"chat:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *  @Groups({"chat:read"})
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"chat:read"})
     */
    private $pieceJointes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     *  @Groups({"chat:read"})
     * 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promos::class, inversedBy="chats")
     *  @Groups({"chat:read"})
     */
    private $promos;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPieceJointes(): ?string
    {
        return $this->pieceJointes;
    }

    public function setPieceJointes(string $pieceJointes): self
    {
        $this->pieceJointes = $pieceJointes;

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

    public function getPromos(): ?Promos
    {
        return $this->promos;
    }

    public function setPromos(?Promos $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
