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
}
