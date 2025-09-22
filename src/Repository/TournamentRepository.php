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
//     * @return Tournament[] Retourne un tableau de tournois validés pour le carousel
//     */
    public function findValidatedForCarousel(int $limit = 10): array
    {
        $statusValue = CurrentStatus::VALIDE->value;
        return $this->findBy(
            ['currentStatus' => $statusValue],
            ['startAt' => 'ASC'],
            $limit
        );
    }

    public function findValidated(int $limit = 10): array
    {
        $statusValue = CurrentStatus::VALIDE->value;
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.currentStatus = :status')
            ->setParameter('status', $statusValue)
            ->orderBy('t.startAt', 'ASC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
/**
     * Retourne les événements validés, filtrés si nécessaire.
     *
     * @param string|null $organizerPseudo
     * @param string|null $dateAtIso        // format attendu: "YYYY-MM-DDTHH:MM" ou ISO
     * @param int|null    $playersCountMin  // capacité minimale (capacityGauge)
     * @return Tournament[]
     */
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
                // DateTimeImmutable accepte "YYYY-MM-DDTHH:MM"
                $dateAtObj = new \DateTimeImmutable($dateAtIso);
                $qb->andWhere('t.startAt >= :dateAt')
                ->setParameter('dateAt', $dateAtObj);
            } catch (\Throwable $e) {
                // si la date ne se parse pas, on ignore le filtre
            }
        }

        if (is_int($playersCountMin)) {
            // filtre sur capacityGauge (capacité totale autorisée)
            $qb->andWhere('t.capacityGauge >= :playersCountMin')
            ->setParameter('playersCountMin', $playersCountMin);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne la liste distincte des pseudos d'organisateur pour les events validés.
     *
     * @return string[] tableau de pseudos triés
     */
    public function findOrganizersForValidated(): array
    {
        $statusValue = \App\Enum\CurrentStatus::VALIDE->value;

        $qb = $this->createQueryBuilder('t')
            ->select('DISTINCT o.pseudo AS pseudo')
            ->leftJoin('t.organizer', 'o')
            ->andWhere('t.currentStatus = :status')
            ->setParameter('status', $statusValue)
            ->orderBy('o.pseudo', 'ASC');

        $rows = $qb->getQuery()->getScalarResult();
        // getScalarResult renvoie un tableau de ['pseudo' => '...']
        return array_map(fn($r) => $r['pseudo'], $rows);
    }
}
