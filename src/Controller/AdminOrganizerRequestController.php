<?php

namespace App\Controller;

use App\Entity\Member;
use App\Service\OrganizerRequestsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class AdminOrganizerRequestController extends AbstractController
{
  public function __construct(
    private OrganizerRequestsService $organizerRequestsService,
    private CsrfTokenManagerInterface $csrfTokenManager
  ) {}

  public function validate(int $member_id, Request $request, EntityManagerInterface $em): Response
  {
    // Vérification du token CSRF
    $submittedToken = $request->request->get('_token');
    $token = new CsrfToken('validate-request-player', $submittedToken);

    if (!$this->csrfTokenManager->isTokenValid($token)) {
      throw $this->createAccessDeniedException('Token CSRF invalide');
    }

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $member = $em->getRepository(Member::class)->find($member_id);
    if (!$member) {
      throw $this->createNotFoundException("Utilisateur introuvable.");
    }

    /** @var \App\Entity\Member $admin */
    $admin = $this->getUser();
    $this->organizerRequestsService->validateRequest($member, $admin->getPseudo());

    $this->addFlash('success', 'La demande pour passer organisateur a été validée !');
    return $this->redirectToRoute('admin_dashboard');
  }


  public function refuse(int $member_id, Request $request): Response
  {
    // Vérification du token CSRF
    $submittedToken = $request->request->get('_token');
    $token = new CsrfToken('refuse-request-player', $submittedToken);

    if (!$this->csrfTokenManager->isTokenValid($token)) {
      throw $this->createAccessDeniedException('Token CSRF invalide');
    }

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    /** @var \App\Entity\Member $admin */
    $admin = $this->getUser();
    $this->organizerRequestsService->refuseRequest($member_id, $admin->getPseudo());
  
    $this->addFlash('danger', 'La demande pour passer organisateur a été refusée !');
    return $this->redirectToRoute('admin_dashboard');
  }
}
