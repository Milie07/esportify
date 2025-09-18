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
    #[ORM\Column(name: "member_avatar_id", type: TYPES:: INTEGER, options: ['unsigned' => true])]
    private ?int $memberAvatarId = null;

    #[ORM\Column(name: "avatar_url", type: TYPES:: STRING, length: 255, nullable: true)]
    private ?string $avatarUrl = null;


    public function getMemberAvatarId(): ?int
    {
        return $this->memberAvatarId;
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
}
