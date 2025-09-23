<?php
namespace App\Controller;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    #[Route('/events', name: 'app_events', methods: ['GET'])]
    public function index(Request $request, TournamentRepository $tournamentRepository): Response
    {
        $organizer = $request->query->get('organizer') ?: null;
        $dateAt = $request->query->get('dateAt') ?: null;
        $playersCount = $request->query->get('playersCount');
        $playersCount = ($playersCount !== null && $playersCount !== '') ? (int) $playersCount : null;

        $tournaments = $tournamentRepository->findValidatedFiltered($organizer, $dateAt, $playersCount);
        $organizers = $tournamentRepository->findOrganizersForValidated();

        $tz = new \DateTimeZone('Europe/Paris');
        $now = new \DateTimeImmutable('now', $tz);

        $formatDate = function(?\DateTimeInterface $dt) use ($tz) {
            if (!$dt) return '—';
            $d = $dt instanceof \DateTimeImmutable ? $dt : \DateTimeImmutable::createFromMutable($dt);
            $d = $d->setTimezone($tz);
            return $d->format('d/m/Y H:i');
        };

        $computeTimeStatus = function(?\DateTimeInterface $start, ?\DateTimeInterface $end) use ($now) {
            if (!$start || !$end) {
                return 'Statut temporel indisponible';
            }
            $startImmutable = $start instanceof \DateTimeImmutable ? $start : \DateTimeImmutable::createFromMutable($start);
            $endImmutable   = $end instanceof \DateTimeImmutable ? $end : \DateTimeImmutable::createFromMutable($end);

            if ($startImmutable > $now) {
                $diff = $startImmutable->diff($now);
                $days = (int) $diff->days;
                $hours = (int) $diff->h;
                $result = 'Commence dans ' . $days . ' jour' . ($days > 1 ? 's' : '');
                if ($hours > 0) $result .= ' et ' . $hours . 'h';
                return $result;
            }

            if ($startImmutable <= $now && $endImmutable >= $now) {
                return 'En cours';
            }

            return 'Terminé';
        };

        $eventsData = [];
        foreach ($tournaments as $t) {
            $organizerPseudo = $t->getOrganizer() ? $t->getOrganizer()->getPseudo() : null;
            $start = $t->getStartAt();
            $end   = $t->getEndAt();

            $eventsData[] = [
                'id' => $t->getId(),
                'title' => $t->getTitle(),
                'tagline' => $t->getTagline(),
                'description' => $t->getDescription(),
                'startsAtFormatted' => $formatDate($start),
                'endsAtFormatted' => $formatDate($end),
                'startsAtIso' => $start ? $start->format(\DateTime::ATOM) : null,
                'endsAtIso' => $end ? $end->format(\DateTime::ATOM) : null,
                'capacityGauge' => $t->getCapacityGauge(),
                'organizerPseudo' => $organizerPseudo,
                'statusLabel' => $t->getCurrentStatus() ? $t->getCurrentStatus()->label() : null,
                'timeStatus' => $computeTimeStatus($start, $end),
                'imagePath' => $t->getImagePath() ?: 'build/images/jpg/default-event.jpg',
            ];
        }

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