<?php

namespace App\Controller;

use App\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SpaceController extends AbstractController
{
  public function playerSpace(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_PLAYER');

    /** @var \App\Entity\Member $user */
    $user = $this->getUser();
    $avatarPath = $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg';

    // Afficher les favoris
    $favoritesCollection = $user->getMemberAddFavorites();

    return $this->render('spaces/player.html.twig', [
      'user' => $user,
      'avatarUrl' => $avatarPath,
      'favorites' => $favoritesCollection,
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
    
    // Afficher les favoris
    $favoritesCollection = $user->getMemberAddFavorites();
    return $this->render('spaces/organizer.html.twig', [
<<<<<<< Updated upstream
      'tournaments' => $tournaments
=======
      'tournaments' => $tournaments,
      'avatarUrl' => $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg',
      'favorites' => $favoritesCollection,
>>>>>>> Stashed changes
    ]);
  }

}
  
