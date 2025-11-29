<?php

namespace App\Controller;

use App\Service\InputSanitizer;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function register(
        Request $request,
        InputSanitizer $san,
        CsrfTokenManagerInterface $csrf
    ): Response {
        $token = new CsrfToken('register', (string) $request->request->get('_csrf_token'));
        if (!$csrf->isTokenValid($token)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('signup_form');
        }

        $firstName = $san->text($request->request->get('firstName', ''), 100);
        $lastName = $san->text($request->request->get('lastName', ''), 100);
        $pseudo = $san->text($request->request->get('pseudo', ''), 100);
        $email = $san->email($request->request->get('email', ''));
        $pass = (string) $request->request->get('password', '');
        $pass2 = (string) $request->request->get('confirm_password', '');
        $cgu = $request->request->getBoolean('conditions');

        $errors = $this->userService->validateRegistration($pseudo, $email, $pass, $pass2, $cgu);

        if (!empty($errors)) {
            return $this->render('auth/signup.html.twig', [
                'errors' => $errors,
                'old' => [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'pseudo' => $pseudo,
                    'mail' => $email,
                ]
            ]);
        }

        $avatarCode = (int) $request->request->get('avatar', 0);

        try {
            $this->userService->createUser($firstName, $lastName, $pseudo, $email, $pass, $avatarCode);
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Erreur enregistrement : ' . $e->getMessage());
            return $this->redirectToRoute('signup_form');
        }

        $this->addFlash('success', 'Compte créé. Vous pouvez vous connecter.');
        return $this->redirectToRoute('app_login');
    }
}
