<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\TournamentImages;
use MongoDB\Client;
use App\Enum\CurrentStatus;
use App\Service\InputSanitizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class CreateEventController extends AbstractController
{
  public function create(Request $request, InputSanitizer $san, EntityManagerInterface $em): Response 
  {
    $this->denyAccessUnlessGranted('ROLE_ORGANIZER');

    if ($request->getMethod() === 'GET') {
      return $this->render('spaces/organizer.html.twig');
    }

    if ($request->getMethod() === 'POST') {
      $title = $san->sanitize($request->request->get('title'));
      $description = $san->sanitize($request->request->get('description'));
      $tagline = $san->sanitize($request->request->get('tagline'));
      $startAt = $request->request->get('startAt');
      $endAt = $request->request->get('endAt');
      $capacityGauge = (int) $request->request->get('capacityGauge');
      
      /** @var UploadedFile|null $file */
      $file = $request->files->get('tournamentImage');

      if (empty($title) || empty($description) || empty($startAt) || empty($endAt) || $capacityGauge <= 0 || empty($file)) {
        $this->addFlash('error', 'Tous les champs doivent être remplis');
        return $this->redirectToRoute('organizer_space');
      }

    if  (!\DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $startAt) || !\DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $endAt)) {
          $this->addFlash('error', 'Format de date invalide.');
          return $this->redirectToRoute('organizer_space');
    }

    // Gestion de l'upload de l'image
    $filename = uniqid().'.'.$file->guessExtension();

    $file->move(
        $this->getParameter('kernel.project_dir') . '/public/uploads/tournaments/',
        $filename
    );

    // Image de l'event
    $tImg = new TournamentImages();
    $tImg->setImageUrl('uploads/tournaments/'.$filename);
    $tImg->setCode(random_int(100000, 999999));

    $tournament = new Tournament();
    $tournament->setTitle($title);
    $tournament->setDescription($description);
    $tournament->setTagline($tagline);
    $tournament->setStartAt(new \DateTimeImmutable($startAt));
    $tournament->setEndAt(new \DateTimeImmutable($endAt));
    $tournament->setCapacityGauge($capacityGauge);
    $tournament->setCurrentStatus(CurrentStatus::EN_ATTENTE);
    $tournament->setOrganizer($this->getUser());
    $tournament->setCreatedAt(new \DateTimeImmutable());
    $tournament->setTournamentImage($tImg);

    $em->persist($tournament);
    $em->persist($tImg);
    $em->flush();

    //MongoDb
    $client = new \MongoDB\Client($_ENV['MONGODB_URL']);
    $collection = $client->esportify_messaging->tournament_requests;

    $user = $this->getUser();

    $collection->insertOne([
      'tournamentId' => $tournament->getId(),
      'title' => $tournament->getTitle(),
      'organizerId' => $user->getId(),
      'pseudo' => $user->getPseudo(),
      'organizerEmail' => $user->getEmail(),
      'createdAt' => new \MongoDB\BSON\UTCDateTime(),
      'status' => 'new',
    ]);

    $this->addFlash('Success', 'Le tournoi est crée. Il est maintenant en attente de validation par un admin');
    return $this->redirectToRoute('organizer_space');
    }
  return new Response('Méthode non autorisée.', 405);
  }
}
