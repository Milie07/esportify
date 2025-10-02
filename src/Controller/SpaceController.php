<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
		return $this->render('spaces/admin.html.twig');
	}
}