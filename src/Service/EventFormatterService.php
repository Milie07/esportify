<?php

namespace App\Service;

use App\Entity\Tournament;

class EventFormatterService
{
    private \DateTimeZone $timezone;
    private \DateTimeImmutable $now;

    public function __construct()
    {
        $this->timezone = new \DateTimeZone('Europe/Paris');
        $this->now = new \DateTimeImmutable('now', $this->timezone);
    }

    /**
     * Formate une date pour l'affichage
     */
    public function formatDate(?\DateTimeInterface $dt): string
    {
        if (!$dt) {
            return '—';
        }

        $d = $dt instanceof \DateTimeImmutable ? $dt : \DateTimeImmutable::createFromMutable($dt);
        $d = $d->setTimezone($this->timezone);

        return $d->format('d/m/Y H:i');
    }

    /**
     * Calcule le statut temporel d'un événement
     */
    public function computeTimeStatus(?\DateTimeInterface $start, ?\DateTimeInterface $end): string
    {
        if (!$start || !$end) {
            return 'Statut temporel indisponible';
        }

        $startImmutable = $start instanceof \DateTimeImmutable ? $start : \DateTimeImmutable::createFromMutable($start);
        $endImmutable = $end instanceof \DateTimeImmutable ? $end : \DateTimeImmutable::createFromMutable($end);

        if ($startImmutable > $this->now) {
            $diff = $startImmutable->diff($this->now);
            $days = (int) $diff->days;
            $hours = (int) $diff->h;
            $result = 'Commence dans ' . $days . ' jour' . ($days > 1 ? 's' : '');
            if ($hours > 0) {
                $result .= ' et ' . $hours . 'h';
            }
            return $result;
        }

        if ($startImmutable <= $this->now && $endImmutable >= $this->now) {
            return 'En cours';
        }

        return 'Terminé';
    }

    /**
     * Formate un tournoi pour l'affichage
     */
    public function formatTournament(Tournament $tournament): array
    {
        $organizerPseudo = $tournament->getOrganizer() ? $tournament->getOrganizer()->getPseudo() : null;
        $start = $tournament->getStartAt();
        $end = $tournament->getEndAt();

        return [
            'id' => $tournament->getId(),
            'title' => $tournament->getTitle(),
            'tagline' => $tournament->getTagline(),
            'description' => $tournament->getDescription(),
            'startsAtFormatted' => $this->formatDate($start),
            'endsAtFormatted' => $this->formatDate($end),
            'startsAtIso' => $start ? $start->format(\DateTime::ATOM) : null,
            'endsAtIso' => $end ? $end->format(\DateTime::ATOM) : null,
            'capacityGauge' => $tournament->getCapacityGauge(),
            'organizerPseudo' => $organizerPseudo,
            'statusLabel' => $tournament->getCurrentStatus()->label(),
            'timeStatus' => $this->computeTimeStatus($start, $end),
            'imagePath' => $tournament->getImagePath() ?: 'build/images/jpg/default-event.jpg',
        ];
    }

    /**
     * Formate une liste de tournois
     */
    public function formatTournaments(array $tournaments): array
    {
        return array_map(
            fn(Tournament $t) => $this->formatTournament($t),
            $tournaments
        );
    }
}
