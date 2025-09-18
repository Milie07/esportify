<?php

namespace App\Repository;

use App\Entity\TournamentHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TournamentHistory>
 *
 * @method TournamentHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentHistory[]    findAll()
 * @method TournamentHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentHistory::class);
    }

//    /**
//     * @return TournamentHistory[] Returns an array of TournamentHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TournamentHistory
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
