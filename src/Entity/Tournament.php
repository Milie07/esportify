<?php

namespace App\Entity;

use App\Enum\CurrentStatus;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "tournament_id", type: TYPES::INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(name: "title", type: TYPES::STRING, length: 250)]
    private ?string $title = null;

    #[ORM\Column(name: "description", type: TYPES:: TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: "start_at", type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(name: "end_at", type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(name: "capacity_gauge", type: TYPES::INTEGER, options: ["default" => 0])]
    private ?int $capacityGauge = 0;

    #[ORM\Column(name: "tagline", type: TYPES::STRING, length: 255, nullable: true)]
    private ?string $tagline = null;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    // ENUM('En Attente', 'Validé', 'En Cours', 'Terminé', 'Refusé')
    #[ORM\Column(name: "current_status", enumType: CurrentStatus::class, type: TYPES:: STRING, length: 20, options: ['default' => 'En Attente'])]
    private string $currentStatus = 'En Attente';

    //RELATIONS    
        // MEMBER_REGISTER_TOURNAMENT
    #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberRegisterTournament::class, orphanRemoval: true)]
    private Collection $tournamentRegister;
    public function getTournamentRegister(): Collection { return $this->tournamentRegister; }
    
        // MEMBER_PARTICIPATE_TOURNAMENT
    #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberParticipateTournament::class, orphanRemoval: true)]
    private Collection $participateTournament;
    public function getParticipateTournament(): Collection { return $this->participateTournament; }
    
        // ADD_FAVORITES
    #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberAddFavoritesTournament::class, orphanRemoval: true)]
    private Collection $addFavoritestournament;
    public function getAddFavoritestournament(): Collection { return $this->addFavoritestournament; }

        // CONCERN
    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentHistory::class, orphanRemoval: true)]
    private Collection $tournamentHistory;
    public function getTournamentHistory(): Collection { return $this->tournamentHistory; }
    
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "tournament_image_id", referencedColumnName: "tournament_image_id", nullable: true, onDelete: "SET NULL")]
    private ?TournamentImages $tournamentImage = null;

    public function __construct()
    {
        $this->tournamentRegister = new ArrayCollection();
        $this->participateTournament  = new ArrayCollection();
        $this->addFavoritestournament   = new ArrayCollection();
        $this->tournamentHistory   = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
        
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }
        
    public function setstartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): \DateTimeImmutable
    {
        return $this->endAt;
    }
        
    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getCapacityGauge(): int
    {
        return $this->capacityGauge;
    }
        
    public function setCapacityGauge(int $capacityGauge): static
    {
        $this->capacityGauge = $capacityGauge;

        return $this;
    }

    public function getTagline(): ?string
    {
        return $this->tagline;
    }
        
    public function setTagline(string $tagline): static
    {
        $this->tagline = $tagline;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
        
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->currentStatus;
    }
    public function setCurrentStatus(?string $currentStatus): static
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    public function getTournamentImage(): TournamentImages
    {
        return $this->tournamentImage;
    }
        
    public function setTournamentImage(TournamentImages $tournamentImage): static
    {
        $this->tournamentImage = $tournamentImage;

        return $this;
    }
}
