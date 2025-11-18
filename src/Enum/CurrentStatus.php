<?php

namespace App\Enum;

enum CurrentStatus: string
{
  case EN_ATTENTE = 'En Attente';
  case VALIDE = 'Validé';
  case EN_COURS = 'En Cours';
  case REFUSE = 'Refusé';
  case TERMINE = 'Terminé';

  public function isValide(): bool
  {
    return $this === self::VALIDE;
  }

  public function label(): string
  {
    return $this->value;
  }
}
