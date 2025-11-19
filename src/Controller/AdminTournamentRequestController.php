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
  public function index(Request $requests): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // MongoDB
    $client = new Client($_ENV['MONGODB_URL']);

    // Messages de la page Contact
    $contactCollection = $client->esportify_messaging->contact_messages;
    $messages = $contactCollection->find([], [
        'sort' => ['createdAt' => -1]
    ]);

    // Demandes de création de tournois
    $requestsCollection = $client->esportify_messaging->tournament_requests;
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

    $tournament = $em->getRepository(Tournament::class)->find($id);

    if (!$tournament) {
      throw $this->createNotFoundException("Tournoi introuvable.");
    }

     // MongoDB
    $client = new Client($_ENV['MONGODB_URL']);

    // Messages Contact
    $contactCollection = $client->esportify_messaging->contact_messages;
    $messages = $contactCollection->find([], [
        'sort' => ['createdAt' => -1]
    ]);

    // Demandes Tournois
    $requestsCollection = $client->esportify_messaging->tournament_requests;
    $requests = $requestsCollection->find(
        ['status' => 'new'],
        ['sort' => ['createdAt' => -1]]
    );

    return $this->render('spaces/admin.html.twig', [
      'messages'  => $messages,
      'requests'  => $requests,
      'tournament'=> $tournament
    ]);
  }

  public function validate(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer le tournoi SQL
        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        // Mise à jour du SQL
        $tournament->setCurrentStatus(CurrentStatus::VALIDE);
        $em->flush();

        //Mise à jour du MongoDB
        $client = new Client($_ENV['MONGODB_URL']);
        $collection = $client->esportify_messaging->tournament_requests;

        $collection->updateOne(
            ['tournamentId' => $id],
            ['$set' => ['status' => 'validated']]
        );

        // Retour admin
        $this->addFlash('success', 'Tournoi validé !');
        return $this->redirectToRoute('admin_dashboard');
    }

    public function refuse(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer le tournoi
        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        // Mise à jour du SQL
        $tournament->setCurrentStatus(CurrentStatus::REFUSE);
        $em->flush();

        // Mise à jour de MongoDB
        $client = new Client($_ENV['MONGODB_URL']);
        $collection = $client->esportify_messaging->tournament_requests;

        $collection->updateOne(
            ['tournamentId' => $id],
            ['$set' => ['status' => 'refused']]
        );

        // Retour admin
        $this->addFlash('danger', 'Tournoi refusé.');
        return $this->redirectToRoute('admin_dashboard');
    }

}
