<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
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
    private ?string $firstName = null;

    #[ORM\Column(name: 'last_name', type: Types::STRING, length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(name: 'pseudo', type: Types::STRING, length: 100, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(name: 'email', type: Types::STRING, length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'password_hash', type: Types::STRING, length: 255)]
    private ?string $passwordHash = null;

    #[ORM\Column(name: 'member_score', type: Types::INTEGER, options: ['default' => 0])]
    private ?int $memberScore = 0;

    // RELATIONS 
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "member_avatar_id", referencedColumnName: "member_avatar_id", nullable: true, onDelete: "SET NULL")]
    private ?MemberAvatars $memberAvatarId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "member_role_id", referencedColumnName: "member_role_id")]
    private ?MemberRoles $memberRole = null;

        // MEMBER_MODERATE_ROLES
    #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberModerateRoles::class, orphanRemoval: true)]
    private Collection $memberModerate;
    public function getMemberModerate(): Collection
    {
        return $this->memberModerate;
    }

        // MEMBER_REGISTER_TOURNAMENT
    #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberRegisterTournament::class, orphanRemoval: true)]
    private Collection $memberRegister;
    public function getMemberRegister(): Collection
    {
        return $this->memberRegister;
    }

        // MEMBER_PARTICIPATE_TOURNAMENT
    #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberParticipateTournament::class, orphanRemoval: true)]
    private Collection $memberParticipate;
    public function getMemberParticipate(): Collection
    {
        return $this->memberParticipate;
    }

        // ADD_FAVORITES
    #[ORM\OneToMany(mappedBy: "member", targetEntity: MemberAddFavoritesTournament::class, orphanRemoval: true)]
    private Collection $memberAddFavorites;
    public function getMemberAddFavorites(): Collection
    {
        return $this->memberAddFavorites;
    }

        // HISTORIQUE: côté inverse (un membre -> plusieurs historisations)
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

    /**
     * Interface pour se Connecter.
     */
    public function getUserIdentifier(): string
    {
        return (string) ($this->getPseudo() ?? '');
    }

    /**
     * Rôles de sécurité exposés à Symfony.
     * Base: ROLE_USER.
     * + rôle principal mappé depuis MemberRoles (admin/organizer/player).
     */
    
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        if ($this->memberRole) {
        $roles[] = $this->memberRole->getSymfonyRole();
        }
        return array_values(array_unique($roles));
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

    /**
     * Pas de données sensibles temporaires à purger.
     */
    public function eraseCredentials(): void
    {
        // no-op
    }

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

    public function getMemberScore(): ?int
    {
        return $this->memberScore;
    }

    public function setMemberScore(?int $memberScore): static
    {
        $this->memberScore = $memberScore;

        return $this;
    }

    public function getMemberAvatarId(): ?MemberAvatars
    {
        return $this->memberAvatarId;
    }

    public function setMemberAvatar(?MemberAvatars $memberAvatarId): static
    {
        $this->memberAvatarId = $memberAvatarId;

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
    
    public function getAvatarPath(): ?string
{
    if (!$this->memberAvatarId) {
        return null;
    }

    $avatar = $this->memberAvatarId;
    $value = null;

    // On n'utilise que des getters (sécurité IDE + Doctrine proxy)
    if (method_exists($avatar, 'getAvatarUrl')) {
        $value = $avatar->getAvatarUrl();
    } else {
        // Aucun getter connu -> on ne tente pas d'accéder à des propriétés privées
        return null;
    }

    if (!$value) {
        return null;
    }

    // Normalisation simple pour usage avec asset():
    $value = trim(str_replace('\\', '/', $value));
    $value = preg_replace('#^/?public/#i', '', $value);
    $value = ltrim($value, '/');

    if (!preg_match('#^uploads/#i', $value)) {
        $value = 'uploads/avatars/' . $value;
    }

    return $value;
}
}