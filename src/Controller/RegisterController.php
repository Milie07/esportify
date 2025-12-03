<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\RegistrationFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function register(Request $request): Response
    {
        $member = new Member();
        $form = $this->createForm(RegistrationFormType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $avatarCode = (int) $form->get('avatar')->getData();

            try {
                $this->userService->createUser(
                    $member->getFirstName(),
                    $member->getLastName(),
                    $member->getPseudo(),
                    $member->getEmail(),
                    $plainPassword,
                    $avatarCode
                );

                $this->addFlash('success', 'Compte créé. Vous pouvez vous connecter.');
                return $this->redirectToRoute('app_login');
            } catch (\Throwable $e) {
                $this->addFlash('danger', 'Erreur enregistrement : ' . $e->getMessage());
                return $this->redirectToRoute('signup_form');
            }
        }

        return $this->render('auth/signup.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
