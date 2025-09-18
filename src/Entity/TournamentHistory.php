<?php

namespace App\Entity;

use App\Enum\CurrentStatus;
use App\Repository\TournamentHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tournament_history')]
#[ORM\Entity(repositoryClass: TournamentHistoryRepository::class)]
class TournamentHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'tournament_history_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(name:'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'action_type', length: 50)]
    private ?string $action_type = null;

    #[ORM\Column(name:'detail', type: Types::JSON, nullable: true)]
    private ?array $detail = null;

    #[ORM\Column(name:'to_status', type: Types::STRING, enumType: CurrentStatus::class, length: 20)]
    private ?CurrentStatus $to_status = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentHistory')]
    #[ORM\JoinColumn(name: 'tournament_id', referencedColumnName: 'tournament_id', onDelete: 'CASCADE', nullable: false)]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'tournamentHistories')]
    #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'member_id', onDelete: 'CASCADE', nullable: false)]
    private ?Member $member = null;

    // getters / setters …

    public function getId(): ?int { return $this->id; }

    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updated_at; }
    public function setUpdatedAt(?\DateTimeInterface $updated_at): static { $this->updated_at = $updated_at; return $this; }

    public function getActionType(): ?string { return $this->action_type; }
    public function setActionType(string $action_type): static { $this->action_type = $action_type; return $this; }

    public function getDetail(): ?array { return $this->detail; }
    public function setDetail(?array $detail): static { $this->detail = $detail; return $this; }

    public function getToStatus(): ?CurrentStatus { return $this->to_status; }
    public function setToStatus(CurrentStatus $to_status): static { $this->to_status = $to_status; return $this; }

    public function getTournament(): ?Tournament { return $this->tournament; }
    public function setTournament(?Tournament $tournament): static { $this->tournament = $tournament; return $this; }

    public function getMember(): ?Member { return $this->member; }
    public function setMember(?Member $member): static { $this->member = $member; return $this; }
}