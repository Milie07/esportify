<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SpaceController extends AbstractController
{
	public function playerSpace(): Response
	{
		return $this->render('spaces/player.html.twig');
	}
	public function organizerSpace(): Response
	{
		return $this->render('spaces/organizer.html.twig');
	}
	public function adminDashboard(): Response
	{
		return $this->render('spaces/admin.html.twig');
	}
}