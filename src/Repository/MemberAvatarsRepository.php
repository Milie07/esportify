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
}
