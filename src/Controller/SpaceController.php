<?php
namespace App\Controller;

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

	public function organizerSpace(): Response
	{
    $this->denyAccessUnlessGranted('ROLE_ORGANIZER');
    return $this->render('spaces/organizer.html.twig');
	}
    
	public function adminDashboard(): Response
	{
    // Connexion MongoDB
    $client = new Client($_ENV['MONGODB_URL']);
    $collection = $client->esportify_messaging->contact_messages;
    
    // Récupérer les messages (triés en ordre décroissant)
    $messages = $collection->find([], [
      'sort' => ['createdAt' => -1]
    ]);
    
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    return $this->render('spaces/admin.html.twig', [
      'messages' => $messages,
    ]);
	}
}