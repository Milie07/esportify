<?php

namespace App\Entity;

use App\Repository\TournamentImagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentImagesRepository::class)]
class TournamentImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "tournament_image_id", type: TYPES::INTEGER, options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(name: "image_url", type: TYPES::STRING, length: 255, nullable: true)]
    private ?string $imageUrl = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        
        return $this;
    }
}
