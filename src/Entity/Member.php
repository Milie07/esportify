<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo est déjà utilisé.')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[ORM\Table(name: 'member')]
#[ORM\UniqueConstraint(name: "uq_member_pseudo", columns: ["pseudo"])]
#[ORM\UniqueConstraint(name: "uq_member_email", columns: ["email"])]

class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: 'member_id', type: Types::INTEGER, options: ['unsigned' => true])]
  private ?int $id = null;
  
  #[ORM\Column(name: 'first_name', type: Types::STRING, length: 100)]
  #[Assert\NotBlank(message: "Le nom est obligatoire.")]
  #[Assert\Length(
    min: 2,
    max: 100,
    message: "Le nom doit contenir entre {{ min }} et {{ max }} caractères."
  )]
  #[Assert\Regex(
    pattern: '/^[a-zA-ZÀ-ÿ\-]+$/u',
    message: "Le nom ne peut contenir que des lettres, les accents et les tirets."
  )]
  private ?string $firstName = null;

  #[ORM\Column(name: 'last_name', type: Types::STRING, length: 100)]
  #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
  #[Assert\Length(
    min: 2,
    max: 100,
    message: "Le prénom doit contenir entre {{ min }} et {{ max }} caractères."
  )]
  #[Assert\Regex(
    pattern: '/^[a-zA-ZÀ-ÿ\-]+$/u',
    message: "Le prénom ne peut contenir que des lettres, les accents et les tirets."
  )]
  private ?string $lastName = null;

  #[ORM\Column(name: 'pseudo', type: Types::STRING, length: 100, unique: true)]
  #[Assert\NotBlank(message: "Le pseudo est obligatoire.")]
  #[Assert\Length(
    min: 3,
    max: 20,
    message: "Le pseudo doit contenir entre {{ min }} et {{ max }} caractères."
  )]
  #[Assert\Regex(
    pattern: '/^[a-zA-Z0-9_-]+$/',
    message: "Le pseudo ne peut contenir que des lettres, chiffres, underscores et tirets."
  )]
  private ?string $pseudo = null;

  #[ORM\Column(name: 'email', type: Types::STRING, length: 100, unique: true)]
  #[Assert\NotBlank(message: "L'email est obligatoire.")]
  #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
  private ?string $email = null;

  #[ORM\Column(name: 'password_hash', type: Types::STRING, length: 255)]
  #[Assert\NotBlank(groups: ['registration'], message: "Le mot de passe est obligatoire.")]
  private ?string $passwordHash = null;

  #[ORM\Column(name: 'member_score', type: Types::INTEGER, options: ['default' => 0])]
  #[Assert\NotNull]
  #[Assert\PositiveOrZero]
  private ?int $memberScore = 0;

  // RELATIONS 
  #[ORM\ManyToOne(targetEntity: MemberAvatars::class)]
  #[ORM\JoinColumn(name: "member_avatar_id", referencedColumnName: "member_avatar_id", nullable: true, onDelete: "SET NULL")]
  private ?MemberAvatars $memberAvatar = null;

  #[ORM\ManyToOne(targetEntity: MemberRoles::class)]
  #[ORM\JoinColumn(name: "member_role_id", referencedColumnName: "member_role_id", nullable: false)]
  #[Assert\NotNull(message: "Le rôle de l'utilisateur est obligatoire.")]
  private ?MemberRoles $memberRole = null;

  // MEMBER_MODERATE_ROLES à venir
  #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberModerateRoles::class, orphanRemoval: true)]
  private Collection $memberModerate;
  public function getMemberModerate(): Collection
  {
    return $this->memberModerate;
  }

  // MEMBER_REGISTER_TOURNAMENT à venir
  #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberRegisterTournament::class, orphanRemoval: true)]
  private Collection $memberRegister;
  public function getMemberRegister(): Collection
  {
    return $this->memberRegister;
  }

  // MEMBER_PARTICIPATE_TOURNAMENT à venir
  #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberParticipateTournament::class, orphanRemoval: true)]
  private Collection $memberParticipate;
  public function getMemberParticipate(): Collection
  {
    return $this->memberParticipate;
  }

  // ADD_FAVORITES à venir
  #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberAddFavoritesTournament::class, orphanRemoval: true)]
  private Collection $memberAddFavorites;
  public function getMemberAddFavorites(): Collection
  {
    return $this->memberAddFavorites;
  }

  // HISTORIQUE: à venir (un membre -> plusieurs mise en historique)
  #[ORM\OneToMany(mappedBy: 'member', targetEntity: TournamentHistory::class, orphanRemoval: true)]
  private Collection $tournamentHistories;

  public function __construct()
  {
    $this->memberModerate = new ArrayCollection();
    $this->memberRegister = new ArrayCollection();
    $this->memberParticipate = new ArrayCollection();
    $this->memberAddFavorites = new ArrayCollection();
    $this->tournamentHistories = new ArrayCollection();
  }

  public function getUserIdentifier(): string
  {
    return (string) ($this->getPseudo() ?? '');
  }

  /**
   * Base: ROLE_USER.
   * + rôle principal mappé depuis MemberRoles (admin/organizer/player).
   */

  public function getRoles(): array
  {
    // Rôle par défaut
    $roles = ['ROLE_USER'];

    if ($this->memberRole instanceof MemberRoles) {
      $code = $this->memberRole->getCode();
      if ($code) {
        $code = strtoupper($code);
        if (!str_starts_with($code, 'ROLE_')) {
          $code = 'ROLE_' . $code;
        }
        $roles[] = $code;
      } elseif (method_exists($this->memberRole, 'getSymfonyRole')) {
        $mapped = (string) $this->memberRole->getSymfonyRole();
        if ($mapped) {
          $roles[] = strtoupper($mapped);
        }
      }
    }
    return array_values(array_unique($roles));
  }

  public function eraseCredentials(): void {}

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(?string $firstName): static
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(?string $lastName): static
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getPseudo(): ?string
  {
    return $this->pseudo;
  }

  public function setPseudo(?string $pseudo): static
  {
    $this->pseudo = $pseudo;
    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(?string $email): static
  {
    $this->email = $email;

    return $this;
  }
  public function getPassword(): string
  {
    return $this->passwordHash ?? '';
  }

  public function setPassword(string $hashed): static
  {
    $this->passwordHash = $hashed;
    return $this;
  }

  public function getMemberScore(): ?int
  {
    return $this->memberScore;
  }
  public function setMemberScore(?int $memberScore): static
  {
    $this->memberScore = $memberScore;
    return $this;
  }
  public function getMemberAvatar(): ?MemberAvatars
  {
    return $this->memberAvatar;
  }
  public function setMemberAvatar(?MemberAvatars $memberAvatar): static
  {
    $this->memberAvatar = $memberAvatar;
    return $this;
  }
  public function getMemberRole(): ?MemberRoles
  {
    return $this->memberRole;
  }
  public function setMemberRole(?MemberRoles $memberRole): static
  {
    $this->memberRole = $memberRole;
    return $this;
  }

  /** 
   * @return Collection<int, 
   * TournamentHistory> 
   * */
  public function getTournamentHistories(): Collection
  {
    return $this->tournamentHistories;
  }

  public function addTournamentHistory(TournamentHistory $history): static
  {
    if (!$this->tournamentHistories->contains($history)) {
      $this->tournamentHistories->add($history);
      $history->setMember($this);
    }
    return $this;
  }

  public function removeTournamentHistory(TournamentHistory $history): static
  {
    if ($this->tournamentHistories->removeElement($history)) {
      if ($history->getMember() === $this) {
        $history->setMember(null);
      }
    }
    return $this;
  }

  public function getUsername(): string
  {
    return $this->getUserIdentifier();
  }
  public function getSalt(): ?string
  {
    return null;
  }

  /* Affichage de l'avatar 
    * en fonction de la personne connectée 
    */
  public function getAvatarPath(): ?string
  {
    if (!$this->memberAvatar) {
      return null;
    }

    // On sait que la méthode existe => pas besoin de method_exists()
    $value = $this->memberAvatar->getAvatarUrl();

    if (!$value) {
      return null;
    }

    // Normalisation du chemin
    $value = trim(str_replace('\\', '/', $value));
    $value = preg_replace('#^/?public/#i', '', $value);
    $value = ltrim($value, '/');

    if (!preg_match('#^uploads/#i', $value)) {
      $value = 'uploads/avatars/' . $value;
    }

    return $value;
  }


  /**
   * Affichage du pseudo 
   */
  public function getOrganizer(): string
  {
    if (!empty($this->pseudo)) {
      return $this->pseudo;
    }

    $parts = array_filter([$this->firstName, $this->lastName]);
    $full = trim(implode(' ', $parts));

    return $full !== '' ? $full : 'Utilisateur';
  }
}
