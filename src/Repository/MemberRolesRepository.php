<?php

namespace App\Repository;

use App\Entity\MemberRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberRoles>
 *
 * @method MemberRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberRoles[]    findAll()
 * @method MemberRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRolesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberRoles::class);
    }

//    /**
//     * @return MemberRoles[] Returns an array of MemberRoles objects
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

    public function findOneBySomeField($code): ?MemberRoles
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.code = :code')
            ->setParameter('code', strtoupper($code))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }    
}
