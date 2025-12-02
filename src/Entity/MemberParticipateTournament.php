<?php

namespace App\Entity;

use App\Repository\MemberParticipateTournamentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberParticipateTournamentRepository::class)]
class MemberParticipateTournament
{
  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "memberParticipate")]
  #[ORM\JoinColumn(name: "member_id", referencedColumnName: "member_id", onDelete: "CASCADE")]
  private ?Member $member = null;

  #[ORM\Id]
  #[ORM\ManyToOne(inversedBy: "participateTournament")]
  #[ORM\JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id", onDelete: "CASCADE")]
  private ?Tournament $tournament = null;

  #[ORM\Column(name: "tournament_score", type: Types::INTEGER, options: ['default' => 0])]
  private ?int $tournamentScore = null;

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

  public function getTournamentScore(): ?int
  {
    return $this->tournamentScore;
  }

  public function setTournamentScore(?int $tournamentScore): static
  {
    $this->tournamentScore = $tournamentScore;

    return $this;
  }
}
