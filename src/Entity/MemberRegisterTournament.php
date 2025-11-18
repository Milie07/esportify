<?php

namespace App\Entity;

use App\Repository\MemberRegisterTournamentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRegisterTournamentRepository::class)]

class MemberRegisterTournament
{
  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "memberRegister")]
  #[ORM\JoinColumn(name: "member_id", referencedColumnName: "member_id", onDelete: "CASCADE")]
  private ?Member $member = null;

  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "tournamentRegister")]
  #[ORM\JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id", onDelete: "CASCADE")]
  private ?Tournament $tournament = null;

  #[ORM\Column(name: "date_register", type: Types::DATETIME_IMMUTABLE)]
  private ?\DateTimeImmutable $dateRegister = null;

  #[ORM\Column(name: "updated_at", type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $updatedAt = null;

  public function getMember(): ?Member
  {
    return $this->member;
  }

  public function setMember(?Member $member): static
  {
    $this->member = $member;

    return $this;
  }

  public function getTournament(): ?Tournament
  {
    return $this->tournament;
  }

  public function setTournament(?Tournament $tournament): static
  {
    $this->tournament = $tournament;

    return $this;
  }

  public function getDateRegister(): ?\DateTimeImmutable
  {
    return $this->dateRegister;
  }

  public function setDateRegister(?\DateTimeImmutable $dateRegister): static
  {
    $this->dateRegister = $dateRegister;

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
}
