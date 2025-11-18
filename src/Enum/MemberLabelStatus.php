<?php

namespace App\Enum;

enum MemberLabelStatus: string
{
  case ACTIF = 'Actif';
  case BANNI = 'Banni';
  case NON_ACTIF = 'Non-Actif';
}
