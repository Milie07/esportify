<?php

namespace App\Controller;

use App\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use MongoDB\Client;

class SpaceController extends AbstractController
{
  public function playerSpace(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_PLAYER');
    /** @var \App\Entity\Member $user */
    $user = $this->getUser();
    $avatarPath = $user?->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg';

    return $this->render('spaces/player.html.twig', [
      'user' => $user,
      'avatarUrl' => $avatarPath,
    ]);
  }

  public function organizerSpace(EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ORGANIZER');

    $user = $this->getUser();

    $tournaments = $em->getRepository(Tournament::class)->findBy(

      ['organizer' => $user],
      ['createdAt' => 'DESC']
    );

    return $this->render('spaces/organizer.html.twig', [
      'tournaments' => $tournaments
    ]);
  }

  public function adminDashboard(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // Connexion MongoDB
    $client = new Client($_ENV['MONGODB_URL']);

    $messages = $client->esportify_messaging->contact_messages->find(
      [],
      ['sort' => ['createdAt' => -1]]
    );

    $requests = $client->esportify_messaging->tournament_requests->find(
      [], 
      ['sort' => ['createdAt' => -1]]);


    return $this->render('spaces/admin.html.twig', [
      'messages' => $messages,
      'requests' => $requests,
    ]);
  }
}
