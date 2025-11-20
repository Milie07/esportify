<?php

namespace App\Repository;

use App\Enum\CurrentStatus;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tournament>
 *
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Tournament::class);
  }
  //    /**
  //     * @return Tournament[] Je retourne un tableau de tournois validés pour le carousel
  //     */
  public function findValidatedForCarousel(int $limit = 10): array
  {
    $status = [
      CurrentStatus::VALIDE->value,
      CurrentStatus::EN_COURS->value
    ];

    return $this->createQueryBuilder('t')
      ->andWhere('t.currentStatus IN (:status)')
      ->setParameter('status', $status)
      ->orderBy('t.startAt', 'ASC')
      ->setMaxResults($limit)
      ->getQuery()
      ->getResult();
  }

  public function findValidatedOrRunning(?string $organizerPseudo, ?string $dateAtIso, ?int $playersCountMin): array
  {
    $status = [
      CurrentStatus::VALIDE->value,
      CurrentStatus::EN_COURS->value
    ];

    $qb = $this->createQueryBuilder('t')
      ->leftJoin('t.tournamentImage', 'img')
      ->leftJoin('t.organizer', 'o')
      ->andWhere('t.currentStatus IN (:status)')
      ->setParameter('status', $status)
      ->orderBy('t.startAt', 'ASC');

    // Filtre pour l'organisateur
    if ($organizerPseudo) {
      $qb->andWhere('o.pseudo = :organizerPseudo')
        ->setParameter('organizerPseudo', $organizerPseudo);
    }

    // Filtre pour la date
    if ($dateAtIso) {
      try {
        $dateAtObj = new \DateTimeImmutable($dateAtIso);
        $qb->andWhere('t.startAt >= :dateAt')
          ->setParameter('dateAt', $dateAtObj);
      } catch (\Throwable $e) {
        // date invalide → on ignore (comme tu fais)
      }
    }

    // Filtre pour le nombre de joueurs
    if (is_int($playersCountMin)) {
      $qb->andWhere('t.capacityGauge >= :playersCountMin')
        ->setParameter('playersCountMin', $playersCountMin);
    }
    return $qb->getQuery()->getResult();
  }


  public function findValidatedFiltered(?string $organizerPseudo, ?string $dateAtIso, ?int $playersCountMin): array
  {
    $statusValue = \App\Enum\CurrentStatus::VALIDE->value;

    $qb = $this->createQueryBuilder('t')
      ->leftJoin('t.tournamentImage', 'img')
      ->leftJoin('t.tournamentRegister', 'reg') // Pour plus tard
      ->leftJoin('t.tournamentHistory', 'h')   // Pour plus tard
      ->leftJoin('t.organizer', 'o')
      ->andWhere('t.currentStatus = :status')
      ->setParameter('status', $statusValue)
      ->orderBy('t.startAt', 'ASC');

    if ($organizerPseudo) {
      $qb->andWhere('o.pseudo = :organizerPseudo')
        ->setParameter('organizerPseudo', $organizerPseudo);
    }

    if ($dateAtIso) {
      try {
        $dateAtObj = new \DateTimeImmutable($dateAtIso);
        $qb->andWhere('t.startAt >= :dateAt')
          ->setParameter('dateAt', $dateAtObj);
      } catch (\Throwable $e) {
      }
    }

    if (is_int($playersCountMin)) {
      $qb->andWhere('t.capacityGauge >= :playersCountMin')
        ->setParameter('playersCountMin', $playersCountMin);
    }

    return $qb->getQuery()->getResult();
  }

  /**
   * Retourne la liste distincte des pseudos d'organisateur pour les events validés et en cours.
   *
   * @return string[] 
   */
  public function findOrganizersForValidatedOrRunning(): array
  {
    $statuses = [
      CurrentStatus::VALIDE->value,
      CurrentStatus::EN_COURS->value
    ];

    $rows = $this->createQueryBuilder('t')
      ->select('DISTINCT o.pseudo AS pseudo')
      ->leftJoin('t.organizer', 'o')
      ->andWhere('t.currentStatus IN (:status)')
      ->setParameter('status', $statuses)
      ->orderBy('o.pseudo', 'ASC')
      ->getQuery()
      ->getScalarResult();

    return array_column($rows, 'pseudo');
  }
}
