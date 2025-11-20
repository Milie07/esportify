<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\TournamentImages;
use MongoDB\Client;
use App\Enum\CurrentStatus;
use App\Service\InputSanitizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class CreateEventController extends AbstractController
{
  public function create(Request $request, InputSanitizer $san, EntityManagerInterface $em): Response
  {
    // Autorisé pour ORGANIZER + ADMIN
    if (!$this->isGranted('ROLE_ORGANIZER') && !$this->isGranted('ROLE_ADMIN')) {
    throw $this->createAccessDeniedException();
    }
    
    // Page formulaire
    if ($request->isMethod('GET')) {

      // Organisateur
      if ($this->isGranted('ROLE_ORGANIZER')) {
        return $this->render('spaces/organizer.html.twig');
      }

      // Admin
      if ($this->isGranted('ROLE_ADMIN')) {
        return $this->render('spaces/admin.html.twig');
      }
    }

    // Soumission formulaire
    if ($request->isMethod('POST')) {

      // SANITIZATION
      $title = $san->sanitize($request->request->get('title'));
      $description = $san->sanitize($request->request->get('description'));
      $tagline = $san->sanitize($request->request->get('tagline'));

      $startAt = $request->request->get('startAt');
      $endAt = $request->request->get('endAt');
      $capacityGauge = (int) $request->request->get('capacityGauge');

      /** @var UploadedFile|null $file */
      $file = $request->files->get('tournamentImage');

      // Vérification
      if (
        empty($title) || empty($description) ||
        empty($startAt) || empty($endAt) ||
        $capacityGauge <= 0 ||
        !$file instanceof UploadedFile
      ) {
        $this->addFlash('error', 'Tous les champs doivent être remplis.');

        return $this->isGranted('ROLE_ADMIN')
          ? $this->redirectToRoute('admin_dashboard')
          : $this->redirectToRoute('organizer_space');
      }

      // Dates
      $startDate = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $startAt);
      $endDate   = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $endAt);

      if (!$startDate || !$endDate) {
        $this->addFlash('error', 'Format de date invalide.');

        return $this->isGranted('ROLE_ADMIN')
          ? $this->redirectToRoute('admin_dashboard')
          : $this->redirectToRoute('organizer_space');
      }

      // Upload image
      $extension = $file->guessExtension() ?: 'bin';
      $filename = uniqid('tournament_', true) . '.' . $extension;

      $file->move(
        $this->getParameter('kernel.project_dir') . '/public/uploads/tournaments/',
        $filename
      );

      // ENTITÉS SQL
      $tImg = new TournamentImages();
      $tImg->setImageUrl('uploads/tournaments/' . $filename);
      $tImg->setCode(random_int(100000, 999999));

      $tournament = new Tournament();
      $tournament->setTitle($title);
      $tournament->setDescription($description);
      $tournament->setTagline($tagline);
      $tournament->setStartAt($startDate);
      $tournament->setEndAt($endDate);
      $tournament->setCapacityGauge($capacityGauge);
      $tournament->setCurrentStatus(CurrentStatus::EN_ATTENTE);

      /** @var \App\Entity\Member $user */
      $user = $this->getUser();
      $tournament->setOrganizer($user);
      $tournament->setCreatedAt(new \DateTimeImmutable());
      $tournament->setTournamentImage($tImg);

      $em->persist($tImg);
      $em->persist($tournament);
      $em->flush();

      // MONGODB : demandes de validation
      $client = new Client($_ENV['MONGODB_URL']);
      $collection = $client->esportify_messaging->tournament_requests;

      $collection->insertOne([
        'tournamentId'    => $tournament->getId(),
        'title'           => $tournament->getTitle(),
        'organizerId'     => $user->getId(),
        'pseudo'          => $user->getPseudo(),
        'organizerEmail'  => $user->getEmail(),
        'createdAt'       => new \MongoDB\BSON\UTCDateTime(),
        'status'          => 'new',
      ]);

      $this->addFlash('success', 'Le tournoi est créé et en attente de validation.');

      return $this->isGranted('ROLE_ADMIN')
        ? $this->redirectToRoute('admin_dashboard')
        : $this->redirectToRoute('organizer_space');
    }

    return new Response('Méthode non autorisée.', 405);
  }
}
