<?php

namespace App\Repository;

use App\Entity\MemberAddFavoritesTournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberAddFavoritesTournament>
 *
 * @method MemberAddFavoritesTournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberAddFavoritesTournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberAddFavoritesTournament[]    findAll()
 * @method MemberAddFavoritesTournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberAddFavoritesTournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberAddFavoritesTournament::class);
    }
}
