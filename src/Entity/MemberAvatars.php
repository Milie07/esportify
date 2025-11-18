<?php

namespace App\Entity;

use App\Repository\MemberAvatarsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberAvatarsRepository::class)]
#[ORM\Table(name: 'member_avatars')]
class MemberAvatars
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: "member_avatar_id", type: Types::INTEGER, options: ['unsigned' => true])]
  private ?int $memberAvatar = null;

  #[ORM\Column(name: "avatar_url", type: Types::STRING, length: 255, nullable: true)]
  private ?string $avatarUrl = null;

  #[ORM\Column(name: "code", type: Types::INTEGER, unique: true)]
  private ?int $code = null;

  public function getMemberAvatarId(): ?int
  {
    return $this->memberAvatar;
  }

  public function getAvatarUrl(): ?string
  {
    return $this->avatarUrl;
  }
  public function setAvatarUrl(?string $avatarUrl): static
  {
    $this->avatarUrl = $avatarUrl;

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
}
