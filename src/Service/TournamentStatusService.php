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

      // Récupération des dates
      /** @var \DateTimeImmutable|null $start */
      /** @var \DateTimeImmutable|null $end */
      $start = $t->getStartAt();
      $end   = $t->getEndAt();

      // Si une date est absente : ne rien faire
      if ($start === null || $end === null) {
        continue;
      }

      if (!$start instanceof \DateTimeImmutable) {
        $start = \DateTimeImmutable::createFromMutable($start);
      }
      if (!$end instanceof \DateTimeImmutable) {
        $end = \DateTimeImmutable::createFromMutable($end);
      }

      // --- TERMINÉ ---
      if ($end < $now) {
        if ($t->getCurrentStatus() !== CurrentStatus::TERMINE) {
          $t->setCurrentStatus(CurrentStatus::TERMINE);
        }
        continue;
      }

      // --- EN COURS ---
      if ($start <= $now && $now < $end) {
        if ($t->getCurrentStatus() !== CurrentStatus::EN_COURS) {
          $t->setCurrentStatus(CurrentStatus::EN_COURS);
        }
        continue;
      }

      // --- VALIDÉ (évènement futur déjà validé) ---
      if ($start > $now && $t->getCurrentStatus() === CurrentStatus::VALIDE) {
        continue;
      }

      // Sinon, EN_ATTENTE ou REFUSE -> on ne touche pas
    }

    $this->em->flush();
  }
}
