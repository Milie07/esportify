<?php

namespace App\Entity;

use App\Enum\CurrentStatus;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: "tournament_id", type: Types::INTEGER, options: ['unsigned' => true])]
  private ?int $id = null;

  #[ORM\Column(name: "title", type: Types::STRING, length: 250)]
  #[Assert\NotBlank(message: "Le titre est obligatoire.")]
  #[Assert\Length(
    min: 2,
    max: 120,
    minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
    maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
  )]
  private ?string $title = null;

  #[ORM\Column(name: "description", type: Types::TEXT)]
  #[Assert\NotBlank(message: "La description est obligatoire.")]
  #[Assert\Length(
    min: 10,
    max: 500,
    minMessage: "La description doit contenir au moins {{ limit }} caractères.",
    maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
  )]
  private ?string $description = null;

  #[ORM\Column(name: "start_at", type: Types::DATETIME_IMMUTABLE)]
  #[Assert\NotNull(message: "La date de début est obligatoire.")]
  #[Assert\GreaterThan(value: "now", message: "La date de début doit être une date ultérieure.")]
  private ?\DateTimeImmutable $startAt = null;

  #[ORM\Column(name: "end_at", type: Types::DATETIME_IMMUTABLE)]
  #[Assert\NotNull(message: "La date de fin est obligatoire.")]
  #[Assert\GreaterThan(propertyPath: "startAt", message: "La date de fin doit être une date ultérieure.")]
  private ?\DateTimeImmutable $endAt = null;

  #[ORM\Column(name: "capacity_gauge", type: Types::INTEGER, options: ["default" => 0])]
  #[Assert\NotNull(message: "La nombre de joueur max est obligatoire.")]
  #[Assert\Positive(message: "La nombre de joueur max doit être supérieur à 0.")]
  #[Assert\Range(
    min: 1,
    max: 100,
    notInRangeMessage: "La nombre de joueur max doit être entre {{ min }} et {{ max }}."
  )]
  private ?int $capacityGauge = 0;

  #[ORM\Column(name: "tagline", type: Types::STRING, length: 60)]
  #[Assert\NotBlank(message: "La Tagline est obligatoire.")]
  #[Assert\Length(
    max: 60,
    maxMessage: "La Tagline ne peut pas dépasser {{ limit }} caractères."
  )]
  private ?string $tagline = null;

  #[ORM\Column(name: "created_at", type: Types::DATETIME_IMMUTABLE)]
  #[Assert\NotNull(message: "La date de création est obligatoire.")]
  private ?\DateTimeImmutable $createdAt = null;

  // ENUM('En Attente', 'Validé', 'En Cours', 'Terminé', 'Refusé')
  #[ORM\Column(name: "current_status", type: Types::STRING, enumType: CurrentStatus::class, length: 20, options: ['default' => CurrentStatus::EN_ATTENTE->value])]
  #[Assert\NotNull(message: "Le statut est obligatoire.")]
  private CurrentStatus $currentStatus = CurrentStatus::EN_ATTENTE;

  //RELATIONS    
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(name: "tournament_image_id", referencedColumnName: "tournament_image_id", nullable: true, onDelete: "SET NULL")]
  private ?TournamentImages $tournamentImage = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(name: 'member_id', referencedColumnName: 'member_id', nullable: false, options: ['unsigned' => true])]
  #[Assert\NotNull(message: "L'organisateur est obligatoire.")]
  private ?Member $organizer = null;

  // MEMBER_REGISTER_TOURNAMENT
  #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberRegisterTournament::class, orphanRemoval: true)]
  private Collection $tournamentRegister;
  public function getTournamentRegister(): Collection
  {
    return $this->tournamentRegister;
  }

  // MEMBER_PARTICIPATE_TOURNAMENT
  #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberParticipateTournament::class, orphanRemoval: true)]
  private Collection $participateTournament;
  public function getParticipateTournament(): Collection
  {
    return $this->participateTournament;
  }

  // ADD_FAVORITES
  #[ORM\OneToMany(mappedBy: "tournament", targetEntity: MemberAddFavoritesTournament::class, orphanRemoval: true)]
  private Collection $addFavoritestournament;
  public function getAddFavoritestournament(): Collection
  {
    return $this->addFavoritestournament;
  }

  // CONCERN
  #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentHistory::class, orphanRemoval: true)]
  private Collection $tournamentHistory;
  public function getTournamentHistory(): Collection
  {
    return $this->tournamentHistory;
  }

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

  public function setStartAt(\DateTimeImmutable $startAt): static
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

  public function getCurrentStatus(): ?CurrentStatus
  {
    return $this->currentStatus;
  }
  public function setCurrentStatus(?CurrentStatus $currentStatus): static
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

  public function getOrganizer(): Member
  {
    return $this->organizer;
  }

  public function setOrganizer(Member $organizer): static
  {
    $this->organizer = $organizer;

    return $this;
  }

  public function getImagePath(): ?string
  {
    if (!$this->tournamentImage) {
      return null;
    }
    return $this->tournamentImage->getImagePath();
  }

  public function isValidated(): bool
  {
    return $this->currentStatus->isValide();
  }

  /**
   * Validation personnalisée : vérifier que le tournoi a une durée raisonnable
   */
  #[Assert\Callback]
  public function validate(ExecutionContextInterface $context): void
  {
    if ($this->startAt && $this->endAt) {
      $duration = $this->startAt->diff($this->endAt);
      
      // Vérifier que le tournoi dure au moins 1 heure
      if ($duration->h < 1 && $duration->days === 0) {
        $context->buildViolation('Le tournoi doit durer au moins 1 heure.')
          ->atPath('endAt')
          ->addViolation();
      }
      
      // Vérifier que le tournoi ne dure pas plus de 5 jours
      if ($duration->days > 5) {
        $context->buildViolation('Le tournoi ne peut pas durer plus de 5 jours.')
          ->atPath('endAt')
          ->addViolation();
      }
    }
  }
}
