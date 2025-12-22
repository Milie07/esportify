<?php
namespace App\Service;

use App\Entity\Member;
use App\Entity\MemberAddFavoritesTournament;
use App\Repository\MemberAddFavoritesTournamentRepository;
use App\Repository\TournamentRepository;

use Doctrine\ORM\EntityManagerInterface;

class FavoriteEventService
{
    public function __construct(private EntityManagerInterface $entityManager, private TournamentRepository $tournamentRepository, private MemberAddFavoritesTournamentRepository $memberAddFavoritesTournamentRepository)
    {
    }

    public function addFavoriteEvent(Member $member, string $tournamentId): void
    {
      $tournament = $this->tournamentRepository->find($tournamentId);

      if (!$tournament) {
        throw new \InvalidArgumentException('Tournoi introuvable.');
      }

      $existingFavorite = $this->memberAddFavoritesTournamentRepository->findOneBy(['member' => $member, 'tournament' => $tournament]);
      
      if ($existingFavorite) {
        throw new \InvalidArgumentException('Cet évènement est déjà dans vos favoris.');
      } 

      $favoriteEvent = new MemberAddFavoritesTournament();
      $favoriteEvent->setMember($member);
      $favoriteEvent->setTournament($tournament);

      $this->entityManager->persist($favoriteEvent);
      $this->entityManager->flush();
    }

    public function removeFavoriteEvent(Member $member, string $tournamentId): void
    {
      $tournament = $this->tournamentRepository->find($tournamentId);

      if (!$tournament) {
        throw new \InvalidArgumentException('Tournoi introuvable.');
      }

      $removingFavorite = $this->memberAddFavoritesTournamentRepository->findOneBy(['member' => $member, 'tournament' => $tournament]);
      
      if (!$removingFavorite) {
        throw new \InvalidArgumentException('Cet évènement n\'est pas dans vos favoris.');
      }

      $this->entityManager->remove($removingFavorite);
      $this->entityManager->flush();
    }
  }
  


