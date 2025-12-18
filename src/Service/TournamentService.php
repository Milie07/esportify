<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\Tournament;
use App\Entity\TournamentImages;
use App\Enum\CurrentStatus;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TournamentService
{
    public function __construct(
        private MongoDBService $mongoDBService,
        private EntityManagerInterface $entityManager,
        private FileUploadService $fileUploadService
    ) {
    }
    /**
     * Récupère toutes les demandes de tournois par statut
     */
    public function getRequestsByStatus(string $status): array
    {
        $collection = $this->mongoDBService->getCollection('tournament_requests');
        $cursor = $collection->find(
            ['status' => $status],
            ['sort' => ['createdAt' => -1]]
        );

        return $this->enrichRequests($cursor);
    }

    /**
     * Récupère toutes les demandes organisées par statut
     */
    public function getAllRequestsGroupedByStatus(): array
    {
        return [
            'pending' => $this->getRequestsByStatus('new'),
            'validated' => $this->getRequestsByStatus('validé'),
            'refused' => $this->getRequestsByStatus('refusé'),
            'stopped' => $this->getRequestsByStatus('terminé'),
        ];
    }

    /**
     * Récupère tous les messages de contact
     */
    public function getContactMessages(): array
    {
        $collection = $this->mongoDBService->getCollection('contact_messages');
        $cursor = $collection->find(
            [],
            ['sort' => ['createdAt' => -1]]
        );

        return $this->normalizeMessages($cursor);
    }

    /**
     * Met à jour le statut d'une demande de tournoi
     */
    public function updateRequestStatus(int $tournamentId, string $status): void
    {
        $collection = $this->mongoDBService->getCollection('tournament_requests');
        $collection->updateOne(
            ['tournamentId' => $tournamentId],
            ['$set' => ['status' => $status]]
        );
    }

    /**
     * Convertit un curseur Mongo de demandes en tableau enrichi (image SQL incluse)
     * Optimisé : fetch batch des tournois pour éviter N+1 queries
     */
    private function enrichRequests(iterable $cursor): array
    {
        // 1. Collecter tous les IDs de tournois depuis MongoDB
        $requests = iterator_to_array($cursor);
        $tournamentIds = array_unique(array_map(fn($req) => $req['tournamentId'], $requests));

        // 2. Récupérer tous les tournois en UNE SEULE requête SQL
        $tournaments = [];
        if (!empty($tournamentIds)) {
            $tournamentEntities = $this->entityManager
                ->getRepository(Tournament::class)
                ->findBy(['id' => $tournamentIds]);

            // 3. Créer un tableau associatif [id => Tournament] pour lookup rapide
            foreach ($tournamentEntities as $tournament) {
                $tournaments[$tournament->getId()] = $tournament;
            }
        }

        // 4. Enrichir chaque demande avec l'image du tournoi
        $out = [];
        foreach ($requests as $req) {
            $row = iterator_to_array($req);

            // Normaliser createdAt
            if (isset($row['createdAt']) && $row['createdAt'] instanceof UTCDateTime) {
                $row['createdAt'] = $row['createdAt']->toDateTime();
            } else {
                $row['createdAt'] = null;
            }

            // Récupérer l'image depuis le tournoi (pas de requête SQL supplémentaire)
            $imageUrl = null;
            $tournamentId = $req['tournamentId'];
            if (isset($tournaments[$tournamentId])) {
                $tournament = $tournaments[$tournamentId];
                if ($tournament->getTournamentImage()) {
                    $imageUrl = $tournament->getTournamentImage()->getImageUrl();
                }
            }

            $row['imageUrl'] = $imageUrl;
            $out[] = $row;
        }

        return $out;
    }

    /**
     * Convertit les messages contact en tableau normalisé
     */
    private function normalizeMessages(iterable $cursor): array
    {
        $out = [];

        foreach ($cursor as $doc) {
            $row = iterator_to_array($doc);

            if (isset($row['createdAt']) && $row['createdAt'] instanceof UTCDateTime) {
                $row['createdAt'] = $row['createdAt']->toDateTime();
            } else {
                $row['createdAt'] = null;
            }

            $out[] = $row;
        }

        return $out;
    }

    /**
     * Crée un nouveau tournoi
     * L'image est uploadée dans le dossier "pending" tant que le tournoi n'est pas validé
     */
    public function createTournament(
        string $title,
        string $description,
        string $tagline,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        int $capacityGauge,
        UploadedFile $file,
        Member $organizer,
        string $uploadDirectory
    ): Tournament {
        // Uploader l'image dans le dossier pending avec compression
        // isPending = true car le tournoi n'est pas encore validé
        $imageRelativePath = $this->fileUploadService->uploadTournamentImage(
            $file,
            $uploadDirectory,
            isPending: true
        );

        // Créer l'entité TournamentImages avec le chemin en pending
        $tImg = new TournamentImages();
        $tImg->setImageUrl($imageRelativePath);
        $tImg->setCode(random_int(100000, 999999));

        // Créer le tournoi
        $tournament = new Tournament();
        $tournament->setTitle($title);
        $tournament->setDescription($description);
        $tournament->setTagline($tagline);
        $tournament->setStartAt($startDate);
        $tournament->setEndAt($endDate);
        $tournament->setCapacityGauge($capacityGauge);
        $tournament->setCurrentStatus(CurrentStatus::EN_ATTENTE);
        $tournament->setOrganizer($organizer);
        $tournament->setCreatedAt(new \DateTimeImmutable());
        $tournament->setTournamentImage($tImg);

        $this->entityManager->persist($tImg);
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();

        $this->createTournamentRequest($tournament, $organizer);

        return $tournament;
    }

    /**
     * Valide un tournoi : change son statut et déplace l'image de pending vers permanent
     */
    public function validateTournament(Tournament $tournament, string $publicDirectory): void
    {
        // Changer le statut
        $tournament->setCurrentStatus(CurrentStatus::VALIDE);

        // Déplacer l'image de pending vers permanent si elle existe
        $tournamentImage = $tournament->getTournamentImage();
        if ($tournamentImage) {
            $currentPath = $tournamentImage->getImageUrl();

            // Vérifier si l'image est dans pending
            if (str_contains($currentPath, '/pending/')) {
                try {
                    $newPath = $this->fileUploadService->moveToPermanent($currentPath, $publicDirectory);
                    $tournamentImage->setImageUrl($newPath);
                    $this->entityManager->persist($tournamentImage);
                } catch (\RuntimeException $e) {
                    // Si l'image n'existe pas ou ne peut pas être déplacée, continuer sans erreur
                    // Le tournoi sera validé mais conservera son chemin actuel
                }
            }
        }

        $this->entityManager->flush();

        // Mettre à jour le statut dans MongoDB
        $this->updateRequestStatus($tournament->getId(), 'validé');
    }

    /**
     * Refuse un tournoi : change son statut et supprime l'image
     */
    public function refuseTournament(Tournament $tournament, string $publicDirectory): void
    {
        // Changer le statut
        $tournament->setCurrentStatus(CurrentStatus::REFUSE);

        // Supprimer l'image si elle existe
        $tournamentImage = $tournament->getTournamentImage();
        if ($tournamentImage) {
            $imagePath = $tournamentImage->getImageUrl();

            try {
                $this->fileUploadService->deleteTournamentImage($imagePath, $publicDirectory);
            } catch (\RuntimeException $e) {
                // Si l'image n'existe pas ou ne peut pas être supprimée, continuer sans erreur
            }

            // Supprimer la relation avec l'image
            $tournament->setTournamentImage(null);
            $this->entityManager->remove($tournamentImage);
        }

        $this->entityManager->flush();

        // Mettre à jour le statut dans MongoDB
        $this->updateRequestStatus($tournament->getId(), 'refusé');
    }

    /**
     * Crée une demande de validation de tournoi dans MongoDB
     */
    private function createTournamentRequest(Tournament $tournament, Member $organizer): void
    {
        $collection = $this->mongoDBService->getCollection('tournament_requests');

        $collection->insertOne([
            'tournamentId' => $tournament->getId(),
            'title' => $tournament->getTitle(),
            'organizerId' => $organizer->getId(),
            'pseudo' => $organizer->getPseudo(),
            'organizerEmail' => $organizer->getEmail(),
            'createdAt' => new UTCDateTime(),
            'status' => 'new',
        ]);
    }
}
