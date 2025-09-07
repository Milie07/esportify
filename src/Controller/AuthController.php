<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
	public function signin(): Response
	{
		return $this->render('auth/signin.html.twig');
	}
	public function signup(): Response
	{
		return $this->render('auth/signup.html.twig');
	}
}