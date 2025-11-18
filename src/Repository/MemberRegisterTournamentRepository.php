<?php

namespace App\Repository;

use App\Entity\MemberRegisterTournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberRegisterTournament>
 *
 * @method MemberRegisterTournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberRegisterTournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberRegisterTournament[]    findAll()
 * @method MemberRegisterTournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRegisterTournamentRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, MemberRegisterTournament::class);
  }
}
