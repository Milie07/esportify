<?php

namespace App\Entity;

use App\Enum\MemberRoleLabel;
use App\Repository\MemberRolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRolesRepository::class)]
#[ORM\Table(name: 'member_roles')]
#[ORM\UniqueConstraint(name: 'uq_member_roles_code', columns: ['code'])]


class MemberRoles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "member_role_id", type: Types:: INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;

    //ENUM('Player', 'Organizer', 'Admin')
    #[ORM\Column(name: "member_role_label", enumType: MemberRoleLabel::class, length: 20, options: ['default' => 'Player'])]
    private ?MemberRoleLabel $memberRoleLabel = null;

    #[ORM\Column(name: "created_at", type: TYPES:: DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: "updated_at", type: TYPES:: DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(name: 'code', type: Types::STRING, length: 32, unique: true)]
    private string $code;

    // RELATIONS
    #[ORM\OneToMany(mappedBy: "memberRole", targetEntity: MemberModerateRoles::class, orphanRemoval: true)]
    private Collection $moderateMemberRole;

    public function __construct()
    {
        $this->moderateMemberRole = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberRoleLabel(): ?MemberRoleLabel
    {
        return $this->memberRoleLabel;
    }

    public function setMemberRoleLabel(?MemberRoleLabel$memberRoleLabel): self
    {
        $this->memberRoleLabel = $memberRoleLabel;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getModerateMemberRole(): Collection
    {
        return $this->moderateMemberRole;
    }

    public function getSymfonyRole(): string
    {
        if ($this->memberRoleLabel instanceof MemberRoleLabel) {
            if (method_exists($this->memberRoleLabel, 'symfonyRole')) {
                return $this->memberRoleLabel->symfonyRole();
            }
            if (method_exists($this->memberRoleLabel, 'value')) {
                return 'ROLE_' . strtoupper(preg_replace('/[^A-Z0-9]+/i', '_', $this->memberRoleLabel->value));
            }
            // fallback: string cast
            return 'ROLE_' . strtoupper(preg_replace('/[^A-Z0-9]+/i', '_', (string) $this->memberRoleLabel));
        }
        return 'ROLE_USER';
    }

}
