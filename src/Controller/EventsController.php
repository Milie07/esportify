<?php

namespace App\Controller;

use App\Repository\MemberAddFavoritesTournamentRepository;
use App\Repository\TournamentRepository;
use App\Service\EventFormatterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
  public function __construct(
    private EventFormatterService $eventFormatter
  ) {}

  #[Route('/events', name: 'app_events', methods: ['GET'])]
  public function index(
    Request $request,
    TournamentRepository $tournamentRepository,
    MemberAddFavoritesTournamentRepository $memberAddFavoritesTournamentRepository
  ): Response {
    // Note: updateAllStatus() est désormais géré par un cron (voir UpdateTournamentStatusCommand)
    // Cela évite d'exécuter findAll() + flush() à chaque requête HTTP
    // Filtre de recherche (organizer, dateAt, playersCount)
    $organizer = $request->query->get('organizer') ?: null;
    $dateAt = $request->query->get('dateAt') ?: null;
    $playersCount = $request->query->get('playersCount');
    $playersCount = ($playersCount !== null && $playersCount !== '') ? (int) $playersCount : null;

    $limit = 12;
    $page = max(1, $request->query->getInt('page', 1));
    $total = $tournamentRepository->countValidatedOrRunning($organizer, $dateAt, $playersCount);
    $totalPages = max(1, (int) ceil($total / $limit));
    $page = min($page, $totalPages);
    $tournaments = $tournamentRepository->findValidatedOrRunning($organizer, $dateAt, $playersCount, $limit, ($page - 1) * $limit);
    $organizers = $tournamentRepository->findOrganizersForValidatedOrRunning();

    $eventsData = $this->eventFormatter->listTournaments($tournaments);
    
    
    // Récupération des IDs des tournois favoris de l'utilisateur connecté
    /** @var \App\Entity\Member|null $user */
    $user = $this->getUser();
    $userFavoritesIds = [];
    if ($user) {
      $favorites = $memberAddFavoritesTournamentRepository->findBy(['member' => $user]);
      $userFavoritesIds = array_map(fn($fav) => (int) $fav->getTournament()->getId(), $favorites);
    }
    
    return $this->render('events/index.html.twig', [
      'events' => $eventsData,
      'organizers' => $organizers,
      'filters' => [
        'organizer' => $organizer,
        'dateAt' => $dateAt,
        'playersCount' => $playersCount,
      ],
      'userFavoriteIds' => $userFavoritesIds,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      ]);
      }
      }
      