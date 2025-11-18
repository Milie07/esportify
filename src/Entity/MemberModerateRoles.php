<?php

namespace App\Entity;

use App\Enum\MemberLabelStatus;
use App\Repository\MemberModerateRolesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberModerateRolesRepository::class)]
#[ORM\Table(name: 'member_moderate_roles')]
class MemberModerateRoles
{
  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "memberModerate")]
  #[ORM\JoinColumn(name: "member_id", referencedColumnName: "member_id", onDelete: "CASCADE")]
  private ?Member $member = null;

  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "moderateMemberRole")]
  #[ORM\JoinColumn(name: "member_role_id", referencedColumnName: "member_role_id", onDelete: "CASCADE")]
  private ?MemberRoles $memberRole = null;

  // ENUM('Actif', 'Banni', 'Non-Actif')
  #[ORM\Column(name: "member_label_status", enumType: MemberLabelStatus::class, type: TYPES::STRING, length: 20, options: ['default' => 'Actif'])]
  private string $memberLabelStatus = 'Actif';

  #[ORM\Column(name: "assigned_at", type: TYPES::DATETIME_IMMUTABLE, nullable: true)]
  private ?\DateTimeImmutable $assignedAt = null;



  public function getMember(): ?Member
  {
    return $this->member;
  }
  public function setMember(?Member $member): static
  {
    $this->member = $member;

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

  public function getMemberLabelStatus(): ?string
  {
    return $this->memberLabelStatus;
  }
  public function setMemberLabelStatus(?string $memberLabelStatus): static
  {
    $this->memberLabelStatus = $memberLabelStatus;

    return $this;
  }

  public function getAssignedAt(): ?\DateTimeImmutable
  {
    return $this->assignedAt;
  }
  public function setAssignedAt(?\DateTimeImmutable $assignedAt): static
  {
    $this->assignedAt = $assignedAt;

    return $this;
  }
}
