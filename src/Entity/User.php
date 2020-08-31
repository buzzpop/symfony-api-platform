<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"formateur"="Formateur", "apprenant"="Apprenant", "user"="User"})
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email doit être unique"
 * )
 *
 * @ApiResource(
 *
 *     normalizationContext={"groups"={"user:read"},},
 *  collectionOperations={
 *      "get"={"path"="/admin/users"},
 * "get_users_by_profil"={
 * "method"="GET",
 * "path"="/api/admin/profils/{id}/users" ,
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="users_by_profil",
 *
 * },
 *     "post_users"={
 * "method"="POST",
 * "path"="/api/admin/users" ,
 * "access_control"="(is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_users",
 *
 * },
 * "get_apprenant"={
 * "method"="GET",
 * "path"="/api/apprenants" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_apprenant",
 *
 * },
 * "post_apprenant"={
 * "method"="POST",
 * "path"="/api/apprenants" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur') or is_granted('ROLE_CM'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="post_apprenant",
 *
 * },
 *
 *     "get_formateurs"={
 * "method"="GET",
 * "path"="/api/formateurs" ,
 * "access_control"="(is_granted('ROLE_CM') or is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_formateurs",
 *
 * },
 *
 * },
 *
 *      itemOperations={
 *     "get"={"path"="/admin/users/{id}"},
 *     "put"={"path"="/admin/users/{id}"},
 *      "get_apprenant_by_id"={
 * "method"="GET",
 * "path"="/api/apprenants/{id}" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Apprenant')or is_granted('ROLE_CM')or is_granted('ROLE_Administrateur') )",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_apprenant_by_id",
 *
 * },
 *      "get_formateur_by_id"={
 * "method"="GET",
 * "path"="/api/formateurs/{id}" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_CM') or is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * "route_name"="get_formateur_by_id",
 *
 * },
 *
 * "put_apprenant_by_id"={
 * "method"="PUT",
 * "path"="/apprenants/{id}" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur') or is_granted('ROLE_Apprenant'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 * },
 *     "put_formateur_by_id"={
 * "method"="PUT",
 * "path"="/formateurs/{id}" ,
 * "access_control"="(is_granted('ROLE_Formateur') or is_granted('ROLE_Administrateur'))",
 * "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 *
 *
 * },
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read","promo_principal:read","chat:read","post_groupe:write","brief:read","briefbrouillon:read", "profilSApp:read","profilSAppS:read","competences:read","competence_collection"})

     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Groups({"user:read","chat:read","groupe:read","promo_principal:read","brief:read","briefbrouillon:read", "profilSApp:read","profilSAppS:read","competences:read","competence_collection"})
     */
    private $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Prénom est obligatoire")
     * @Groups({"user:read","groupe:read","promo_principal:read","brief:read","briefbrouillon:read", "profilSApp:read","profilSAppS:read","competences:read","competence_collection"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Groups({"user:read","groupe:read","promo_principal:read","brief:read","briefbrouillon:read", "profilSApp:read","profilSAppS:read","competences:read","competence_collection"})
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read","groupe:read","promo_principal:read","briefbrouillon:read", "profilSApp:read","profilSAppS:read"})
     * @Assert\NotBlank(message="Le profil est obligatoire")
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=GroupeCompetences::class, mappedBy="user")
     */
    private $grpCompetence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut="false";
    /**
     * @ORM\Column(type="blob")
     */
    // private $avatar;
  

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user")
     */
    private $chats;


    public function __construct()
    {
        $this->grpCompetence = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }


    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGrpCompetence(): Collection
    {
        return $this->grpCompetence;
    }

    public function addGrpCompetence(GroupeCompetences $grpCompetence): self
    {
        if (!$this->grpCompetence->contains($grpCompetence)) {
            $this->grpCompetence[] = $grpCompetence;
            $grpCompetence->setUser($this);
        }

        return $this;
    }

    public function removeGrpCompetence(GroupeCompetences $grpCompetence): self
    {
        if ($this->grpCompetence->contains($grpCompetence)) {
            $this->grpCompetence->removeElement($grpCompetence);
            // set the owning side to null (unless already changed)
            if ($grpCompetence->getUser() === $this) {
                $grpCompetence->setUser(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    // public function getAvatar()
    // {
    //     return $this->avatar;
    // }

    // public function setAvatar($avatar): self
    // {
    //     $this->avatar = $avatar;

    //     return $this;
    // }
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
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }


}
