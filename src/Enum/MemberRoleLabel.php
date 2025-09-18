<?php

namespace App\Enum;

enum MemberRoleLabel: string
{
    case PLAYER = 'Player';
    case ORGANIZER = 'Organizer';
    case ADMIN = 'Admin';
}