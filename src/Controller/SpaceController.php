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
    $avatarPath = $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg';

    return $this->render('spaces/player.html.twig', [
      'user' => $user,
      'avatarUrl' => $avatarPath,
    ]);
  }

  public function organizerSpace(EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ORGANIZER');

    /** @var \App\Entity\Member $user */
    $user = $this->getUser();

    $tournaments = $em->getRepository(Tournament::class)->findBy(
      ['organizer' => $user],
      ['createdAt' => 'DESC']
    );

    return $this->render('spaces/organizer.html.twig', [
      'tournaments' => $tournaments,
      'avatarUrl' => $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg'
    ]);
  }
}
  
