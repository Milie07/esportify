<?php
namespace App\Controller\Api;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventApiController extends AbstractController
{
    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $events = $tournamentRepository->findAll();

        $eventsData = [];
        foreach ($events as $event) {
            $eventsData[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'tagline' => $event->getTagline(),
                'description' => $event->getDescription(),
                'startsAt' => $event->getStartAt()?->format(\DateTime::ATOM),
                'endsAt' => $event->getEndAt()?->format(\DateTime::ATOM),
                'capacityGauge' => $event->getCapacityGauge(),
                'organizerPseudo' => $event->getOrganizer()?->getPseudo(),
                'status' => $event->getCurrentStatus()->label(),
                'imagePath' => $event->getImagePath() ?: 'build/images/jpg/default-event.jpg',
            ];
        }

        return $this->json($eventsData);
    }    
}