<?php

namespace App\Controller;

use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

class ContactController extends AbstractController
{
    public function __construct(
        private ContactService $contactService
    ) {
    }

    public function index(Request $request): Response
    {
        if ($request->getMethod() === 'GET') {
            return $this->render('contact/index.html.twig');
        }

        if ($request->getMethod() === 'POST') {
            /** @var \App\Entity\Member|null $user */
            $user = $this->getUser();

            $pseudo = $user ? $user->getPseudo() : $request->request->get('pseudo');
            $role = $user ? $user->getMemberRole()->getCode() : $request->request->get('role');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            $this->contactService->saveContactMessage($pseudo, $role, $email, $subject, $message);

            return $this->redirectToRoute('contact');
        }

        return new Response('Méthode non supportée', 405);
    }
}
