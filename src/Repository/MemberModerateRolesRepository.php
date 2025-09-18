<?php

namespace App\Repository;

use App\Entity\MemberModerateRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberModerateRoles>
 *
 * @method MemberModerateRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberModerateRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberModerateRoles[]    findAll()
 * @method MemberModerateRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberModerateRolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberModerateRoles::class);
    }

//    /**
//     * @return MemberModerateRoles[] Returns an array of MemberModerateRoles objects
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

//    public function findOneBySomeField($value): ?MemberModerateRoles
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
