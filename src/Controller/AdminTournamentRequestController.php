<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Enum\CurrentStatus;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class AdminTournamentRequestController extends AbstractController
{
    /**
     * Retourne une collection MongoDB proprement
     */
    private function getMongoCollection(string $collection)
    {
        $client = new Client($_ENV['MONGODB_URL']);
        $db = $client->selectDatabase('esportify_messaging');
        return $db->selectCollection($collection);
    }

    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Messages Contact
        $contactCollection = $this->getMongoCollection('contact_messages');
        $messages = $contactCollection->find([], [
            'sort' => ['createdAt' => -1]
        ]);

        // Demandes Tournois
        $requestsCollection = $this->getMongoCollection('tournament_requests');
        $requests = $requestsCollection->find(
            ['status' => 'new'],
            ['sort' => ['createdAt' => -1]]
        );

        return $this->render('spaces/admin.html.twig', [
            'messages' => $messages,
            'requests' => $requests,
        ]);
    }

    public function show(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récup tournoi SQL
        $tournament = $em->getRepository(Tournament::class)->find($id);

        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        // Messages
        $contactCollection = $this->getMongoCollection('contact_messages');
        $messages = $contactCollection->find([], [
            'sort' => ['createdAt' => -1]
        ]);

        // Demandes
        $requestsCollection = $this->getMongoCollection('tournament_requests');
        $requests = $requestsCollection->find(
            ['status' => 'new'],
            ['sort' => ['createdAt' => -1]]
        );

        return $this->render('spaces/admin.html.twig', [
            'messages'   => $messages,
            'requests'   => $requests,
            'tournament' => $tournament
        ]);
    }

    public function validate(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // SQL
        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        $tournament->setCurrentStatus(CurrentStatus::VALIDE);
        $em->flush();

        // MongoDB
        $requestsCollection = $this->getMongoCollection('tournament_requests');

        $requestsCollection->updateOne(
            ['tournamentId' => $id],
            ['$set' => ['status' => 'validated']]
        );

        $this->addFlash('success', 'Tournoi validé !');
        return $this->redirectToRoute('admin_dashboard');
    }

    public function refuse(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // SQL
        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        $tournament->setCurrentStatus(CurrentStatus::REFUSE);
        $em->flush();

        // MongoDB
        $requestsCollection = $this->getMongoCollection('tournament_requests');

        $requestsCollection->updateOne(
            ['tournamentId' => $id],
            ['$set' => ['status' => 'refused']]
        );

        $this->addFlash('danger', 'Tournoi refusé.');
        return $this->redirectToRoute('admin_dashboard');
    }
}