<?php

namespace App\Enum;

enum MemberRoleLabel: string
{
  case PLAYER = 'Player';
  case ORGANIZER = 'Organizer';
  case ADMIN = 'Admin';

  public function symfonyRole(): string
  {
    return match ($this) {
      self::PLAYER => 'ROLE_PLAYER',
      self::ORGANIZER => 'ROLE_ORGANIZER',
      self::ADMIN => 'ROLE_ADMIN'
    };
  }
}
