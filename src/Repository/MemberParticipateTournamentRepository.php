<?php

namespace App\Repository;

use App\Entity\MemberParticipateTournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberParticipateTournament>
 *
 * @method MemberParticipateTournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberParticipateTournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberParticipateTournament[]    findAll()
 * @method MemberParticipateTournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberParticipateTournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberParticipateTournament::class);
    }

//    /**
//     * @return MemberParticipateTournament[] Returns an array of MemberParticipateTournament objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MemberParticipateTournament
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
