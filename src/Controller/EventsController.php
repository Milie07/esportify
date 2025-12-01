<?php

namespace App\Controller;

use App\Repository\TournamentRepository;
use App\Service\EventFormatterService;
use App\Service\TournamentStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    public function __construct(
        private EventFormatterService $eventFormatter
    ) {
    }

    #[Route('/events', name: 'app_events', methods: ['GET'])]
    public function index(
        Request $request,
        TournamentRepository $tournamentRepository,
        TournamentStatusService $statusService
    ): Response {
        $statusService->updateAllStatus();

        $organizer = $request->query->get('organizer') ?: null;
        $dateAt = $request->query->get('dateAt') ?: null;
        $playersCount = $request->query->get('playersCount');
        $playersCount = ($playersCount !== null && $playersCount !== '') ? (int) $playersCount : null;

        $tournaments = $tournamentRepository->findValidatedOrRunning($organizer, $dateAt, $playersCount);
        $organizers = $tournamentRepository->findOrganizersForValidatedOrRunning();

        $eventsData = $this->eventFormatter->formatTournaments($tournaments);

        return $this->render('events/index.html.twig', [
            'events' => $eventsData,
            'organizers' => $organizers,
            'filters' => [
                'organizer' => $organizer,
                'dateAt' => $dateAt,
                'playersCount' => $playersCount,
            ],
        ]);
    }
}
