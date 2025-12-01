<?php

namespace App\Controller;

use App\Service\ContactService;
use App\Service\InputSanitizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

class ContactController extends AbstractController
{
  public function __construct(
    private ContactService $contactService,
    private InputSanitizer $sanitizer
  ) {}

  public function index(Request $request): Response
  {
    if ($request->getMethod() === 'GET') {
      return $this->render('contact/index.html.twig');
    }

    if ($request->getMethod() === 'POST') {
      /** @var \App\Entity\Member|null $user */
      $user = $this->getUser();
      // Sanitization des inputs
      $pseudo = $user
        ? $user->getPseudo()
        : $this->sanitizer->text($request->request->get('pseudo', ''), 100);
      $role = $user
        ? $user->getMemberRole()->getCode()
        : $this->sanitizer->text($request->request->get('role', ''), 50);
      $email = $this->sanitizer->email($request->request->get('email', ''));
      $subject = $this->sanitizer->text($request->request->get('subject', ''), 200);
      $message = $this->sanitizer->textarea($request->request->get('message', ''), 5000);

      // Validation
      if (empty($email) || empty($message)) {
        $this->addFlash('error', 'Email et message requis');
        return $this->redirectToRoute('contact');
      }

      $this->contactService->saveContactMessage($pseudo, $role, $email, $subject, $message);
      $this->addFlash('success', 'Message envoyé !');
      return $this->redirectToRoute('contact');
    }

    return new Response('Méthode non supportée', 405);
  }
}
