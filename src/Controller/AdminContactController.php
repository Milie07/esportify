<?php

namespace App\Controller;

use App\Service\ContactService;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final Class AdminContactController extends AbstractController
{
  public function __construct(
    private ContactService $contactService,
    private CsrfTokenManagerInterface $csrfTokenManager
  ){}

  public function delete(string $id, Request $request): Response
  {
    // Vérification du token CSRF
    $submittedToken = $request->request->get('_token');
    $token = new CsrfToken('delete-message', $submittedToken);

    if (!$this->csrfTokenManager->isTokenValid($token)) {
      throw $this->createAccessDeniedException('Token CSRF invalide');
    }

    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $this->contactService->deleteContactMessage($id);
    $this->addFlash('success', 'Message supprimé.');
    return $this->redirectToRoute('admin_dashboard');
  }
}