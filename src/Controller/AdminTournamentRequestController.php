<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Enum\CurrentStatus;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class AdminTournamentRequestController extends AbstractController
{
  private function getMongoCollection(string $collection)
  {
    $client = new Client($_ENV['MONGODB_URL']);
    return $client->esportify_messaging->selectCollection($collection);
  }

  /**
   * Convertit un curseur Mongo de demandes en tableau enrichi (image SQL incluse)
   */
  private function enrichRequests(iterable $cursor, EntityManagerInterface $em): array
  {
    $out = [];

    foreach ($cursor as $req) {

      $tournament = $em->getRepository(Tournament::class)->find($req['tournamentId']);

      $imageUrl = null;
      if ($tournament && $tournament->getTournamentImage()) {
        $imageUrl = $tournament->getTournamentImage()->getImageUrl();
      }

      // BSONDocument -> array
      $row = iterator_to_array($req);

      // Normalisation de createdAt si présent
      if (isset($row['createdAt']) && $row['createdAt'] instanceof UTCDateTime) {
        $row['createdAt'] = $row['createdAt']->toDateTime();
      } else {
        $row['createdAt'] = null;
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

  public function index(Request $request, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Tournois SQL créés par l’admin
    $user = $this->getUser();
    $tournaments = $em->getRepository(Tournament::class)->findBy(
      ['organizer' => $user],
      ['createdAt' => 'DESC']
    );

    // Messages contact (curseur -> tableau)
    $messagesCursor = $this->getMongoCollection('contact_messages')->find(
      [],
      ['sort' => ['createdAt' => -1]]
    );
    $messages = $this->normalizeMessages($messagesCursor);

    // Demandes tournois
    $requests = $this->getMongoCollection('tournament_requests');

    $pendingCursor   = $requests->find(['status' => 'new'],     ['sort' => ['createdAt' => -1]]);
    $validatedCursor = $requests->find(['status' => 'validé'],  ['sort' => ['createdAt' => -1]]);
    $refusedCursor   = $requests->find(['status' => 'refusé'],  ['sort' => ['createdAt' => -1]]);
    $stoppedCursor   = $requests->find(['status' => 'terminé'], ['sort' => ['createdAt' => -1]]);

    $pending   = $this->enrichRequests($pendingCursor,   $em);
    $validated = $this->enrichRequests($validatedCursor, $em);
    $refused   = $this->enrichRequests($refusedCursor,   $em);
    $stopped   = $this->enrichRequests($stoppedCursor,   $em);

    return $this->render('spaces/admin.html.twig', [
      'tournaments'        => $tournaments,
      'messages'           => $messages,
      'requestsPending'    => $pending,
      'requestsValidated'  => $validated,
      'requestsRefused'    => $refused,
      'requestsStopped'    => $stopped,
    ]);
  }

  public function show(int $id, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Tournois créés par l’admin
    $user = $this->getUser();
    $tournaments = $em->getRepository(Tournament::class)->findBy(
      ['organizer' => $user],
      ['createdAt' => 'DESC']
    );

    // Tournoi à afficher
    $tournament = $em->getRepository(Tournament::class)->find($id);
    if (!$tournament) {
      throw $this->createNotFoundException("Tournoi introuvable.");
    }

    // Messages
    $messagesCursor = $this->getMongoCollection('contact_messages')->find([], ['sort' => ['createdAt' => -1]]);
    $messages = $this->normalizeMessages($messagesCursor);

    // Demandes mongo
    $requests = $this->getMongoCollection('tournament_requests');

    $requestsPending   = $this->enrichRequests($requests->find(['status' => 'new'],     ['sort' => ['createdAt' => -1]]), $em);
    $requestsValidated = $this->enrichRequests($requests->find(['status' => 'validé'],  ['sort' => ['createdAt' => -1]]), $em);
    $requestsRefused   = $this->enrichRequests($requests->find(['status' => 'refusé'],  ['sort' => ['createdAt' => -1]]), $em);
    $requestsStopped   = $this->enrichRequests($requests->find(['status' => 'terminé'], ['sort' => ['createdAt' => -1]]), $em);

    return $this->render('spaces/admin.html.twig', [
      'tournaments'        => $tournaments,   // ← AJOUT ESSENTIEL
      'tournament'         => $tournament,
      'messages'           => $messages,
      'requestsPending'    => $requestsPending,
      'requestsValidated'  => $requestsValidated,
      'requestsRefused'    => $requestsRefused,
      'requestsStopped'    => $requestsStopped,
    ]);
  }


  public function validate(int $id, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $tournament = $em->getRepository(Tournament::class)->find($id);
    if (!$tournament) {
      throw $this->createNotFoundException("Tournoi introuvable.");
    }

    $tournament->setCurrentStatus(CurrentStatus::VALIDE);
    $em->flush();

    $this->getMongoCollection('tournament_requests')->updateOne(
      ['tournamentId' => $id],
      ['$set' => ['status' => 'validé']]
    );

    $this->addFlash('success', 'Tournoi validé !');
    return $this->redirectToRoute('admin_dashboard');
  }

  public function refuse(int $id, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $tournament = $em->getRepository(Tournament::class)->find($id);
    if (!$tournament) {
      throw $this->createNotFoundException("Tournoi introuvable.");
    }

    $tournament->setCurrentStatus(CurrentStatus::REFUSE);
    $em->flush();

    $this->getMongoCollection('tournament_requests')->updateOne(
      ['tournamentId' => $id],
      ['$set' => ['status' => 'refusé']]
    );

    $this->addFlash('danger', 'Tournoi refusé.');
    return $this->redirectToRoute('admin_dashboard');
  }

  public function stopped(int $id, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $tournament = $em->getRepository(Tournament::class)->find($id);
    if (!$tournament) {
      throw $this->createNotFoundException("Tournoi introuvable.");
    }

    $tournament->setCurrentStatus(CurrentStatus::TERMINE);
    $em->flush();

    $this->getMongoCollection('tournament_requests')->updateOne(
      ['tournamentId' => $id],
      ['$set' => ['status' => 'terminé']]
    );

    $this->addFlash('danger', 'Tournoi terminé.');
    return $this->redirectToRoute('admin_dashboard');
  }
}
