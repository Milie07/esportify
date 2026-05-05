<?php

namespace App\Repository;

use App\Entity\MemberAddFavoritesTournament;
use App\Entity\Tournament;
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

  public function cleanFavoritesForTournament(Tournament $tournament): void
  {
    $this->createQueryBuilder('f')
        ->delete()
        ->where('f.tournament = :t')
        ->setParameter('t', $tournament)
        ->getQuery()
        ->execute();
  }

}
