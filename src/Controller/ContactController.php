<?php

namespace App\Controller;

use App\Form\ContactType;
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
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\Member|null $user */
            $user = $this->getUser();

            // Si l'utilisateur est connecté, on utilise ses infos
            $pseudo = $user ? $user->getPseudo() : $form->get('pseudo')->getData();
            $role = $user ? $user->getMemberRole()->getCode() : $form->get('role')->getData();
            $email = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();

            try {
                $this->contactService->saveContactMessage($pseudo, $role, $email, $subject, $message);
                $this->addFlash('success', 'Message envoyé !');
            } catch (\Throwable $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi du message : ' . $e->getMessage());
            }

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
