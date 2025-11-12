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

    #[ORM\Column(name: "image_url", type: TYPES::STRING, length: 255, nullable: false)]
    private ?string $imageUrl = null;

    #[ORM\Column(name: "code", type: Types:: INTEGER, unique: true)]
    private ?int $code = null;


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

    public function getCode(): ?int
    {
      return $this->code;
    }

    public function setCode(?int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getImagePath(): ?string
    {
        if (!$this->imageUrl) {
            return null;
        }
    
        // normalisation : backslashes -> slash
        $value = str_replace('\\', '/', trim($this->imageUrl));
    
        // supprimer un éventuel préfixe "public/"
        $value = preg_replace('#^/?public/#i', '', $value);
    
        // supprimer slash initial s'il reste
        $value = ltrim($value, '/');
    
        // si ce n'est pas déjà un chemin commençant par uploads/ ou build/, on préfixe
        if (!preg_match('#^(uploads/|build/)#i', $value)) {
            $value = 'uploads/tournaments/' . $value;
        }
    
        return $value;
    }
}
