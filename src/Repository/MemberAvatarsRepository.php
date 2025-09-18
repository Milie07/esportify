<?php

namespace App\Repository;

use App\Entity\MemberAvatars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberAvatars>
 *
 * @method MemberAvatars|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberAvatars|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberAvatars[]    findAll()
 * @method MemberAvatars[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberAvatarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberAvatars::class);
    }

//    /**
//     * @return MemberAvatars[] Returns an array of MemberAvatars objects
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

//    public function findOneBySomeField($value): ?MemberAvatar
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
