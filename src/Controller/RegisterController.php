<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function register(Request $request): Response
    {
        // Vérification CSRF
        $csrfToken = $request->request->get('_csrf_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('register', $csrfToken))) {
            $this->addFlash('danger', 'Token CSRF invalide. Veuillez réessayer.');
            return $this->redirectToRoute('signup_form');
        }

        // Récupération des données POST
        $firstName = trim($request->request->get('firstName', ''));
        $lastName = trim($request->request->get('lastName', ''));
        $pseudo = trim($request->request->get('pseudo', ''));
        $email = trim($request->request->get('email', ''));
        $password = $request->request->get('password', '');
        $confirmPassword = $request->request->get('confirm_password', '');
        $avatarCode = (int) $request->request->get('avatar', 1);
        $conditions = $request->request->get('conditions', '');

        // Validation basique
        $errors = $this->userService->validateRegistration(
            $pseudo,
            $email,
            $password,
            $confirmPassword,
            (bool) $conditions
        );

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->addFlash('danger', $error);
            }
            return $this->redirectToRoute('signup_form');
        }

        // Création de l'utilisateur
        try {
            $this->userService->createUser(
                $firstName,
                $lastName,
                $pseudo,
                $email,
                $password,
                $avatarCode
            );

            $this->addFlash('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return $this->redirectToRoute('signup_form');
        }
    }
}
