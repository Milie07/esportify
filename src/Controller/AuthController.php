<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{  Request, Response};
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Config\Framework\RateLimiterConfig;

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
	public function forgotPassword(): Response
	{
		return $this->render('auth/password.html.twig');
	}
	public function modifProfile(): Response
	{
		return $this->render('auth/modifProfile.html.twig');
	}
  public function login(
    AuthenticationUtils $authenticationUtils,
    Request $request,
    RateLimiterFactory $loginLimiter): Response
  {
    // Créer un Rate-Limiting par IP
    $limiter = $loginLimiter->create($request->getClientIp());
    // Vérifier si la limite est atteinte
    if (false === $limiter->consume(1)->isAccepted()) {
      throw new TooManyRequestsHttpException(
        'Trop de tentatives de connexion. Veuillez réessayer plus tard.'
      );
    }
    
      // get the login error if there is one
      $error = $authenticationUtils->getLastAuthenticationError();
      // last username entered by the user
      $lastUsername = $authenticationUtils->getLastUsername();

      return $this->render('auth/signin.html.twig', [
          'last_username' => $lastUsername,
          'error'         => $error,
      ]);
  }
}