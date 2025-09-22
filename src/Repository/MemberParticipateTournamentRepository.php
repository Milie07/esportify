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

}
