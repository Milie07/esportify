<?php

namespace App\Service;

use App\Enum\CurrentStatus;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;

class TournamentStatusService
{
  public function __construct(
    private TournamentRepository $repo,
    private EntityManagerInterface $em
  ) {}

  public function updateAllStatus(): void
  {
    $tournaments = $this->repo->findAll();
    $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

    foreach ($tournaments as $t) {

      $start = $t->getStartAt();
      $end   = $t->getEndAt();

      if (!$start || !$end) continue;

      // Convertir en immutable TZ Paris
      $start = ($start instanceof \DateTimeImmutable) ? $start : \DateTimeImmutable::createFromMutable($start);
      $end   = ($end instanceof \DateTimeImmutable) ? $end : \DateTimeImmutable::createFromMutable($end);

      // Statut Terminé 
      if ($end < $now) {
        if ($t->getCurrentStatus() !== CurrentStatus::TERMINE) {
          $t->setCurrentStatus(CurrentStatus::TERMINE);
        }
        continue;
      }

      // Statut En cours
      if ($start <= $now && $end >= $now) {
        if ($t->getCurrentStatus() !== CurrentStatus::EN_COURS) {
          $t->setCurrentStatus(CurrentStatus::EN_COURS);
        }
        continue;
      }

      // Statut Validé
      if ($start > $now && $t->getCurrentStatus() === CurrentStatus::VALIDE) {
        // rien à changer
        continue;
      }

      // Si en attente ou refusé → NE PAS toucher
    }

    $this->em->flush();
  }
}
