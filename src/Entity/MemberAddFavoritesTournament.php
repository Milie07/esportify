<?php

namespace App\Entity;

use App\Repository\MemberAddFavoritesTournamentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberAddFavoritesTournamentRepository::class)]
#[ORM\Table(name: 'member_add_favorites_tournament')]
class MemberAddFavoritesTournament
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: "memberAddFavorites")]
    #[ORM\JoinColumn(name: "member_id", referencedColumnName: "member_id", onDelete: "CASCADE")]
    private ?Member $member = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: "addFavoritestournament")]
    #[ORM\JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id", onDelete: "CASCADE")]
    private ?Tournament $tournament = null;

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
}
