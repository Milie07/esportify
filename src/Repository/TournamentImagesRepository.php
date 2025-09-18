<?php

namespace App\Repository;

use App\Entity\TournamentImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TournamentImages>
 *
 * @method TournamentImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentImages[]    findAll()
 * @method TournamentImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentImages::class);
    }

//    /**
//     * @return TournamentImages[] Returns an array of TournamentImages objects
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

//    public function findOneBySomeField($value): ?TournamentImages
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
