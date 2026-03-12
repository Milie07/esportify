<?php

namespace App\Controller;

use App\Service\OrganizerRequestsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response};
use Symfony\Component\Routing\Annotation\Route;

final class OrganizerRequestsController extends AbstractController
{
  public function __construct(
    private OrganizerRequestsService $organizerRequestsService,
  ) {}
  #[Route('/player/request', name: 'player_request_organizer', methods: ['POST'])]
  public function createRequest(): Response
  {
    if (!$this->isGranted('ROLE_PLAYER') || $this->isGranted('ROLE_ORGANIZER')) {
    throw $this->createAccessDeniedException();
}
      // Envoyé la demande du joueur connecté
    /** @var \App\Entity\Member|null $user */
    // au cas ou j'oublie getUser() retourne l'utilisateur connecté depuis la session Symfony
    $user = $this->getUser();

    if ($user) {
      try {
        $this->organizerRequestsService->saveRequests($user->getId(), $user->getPseudo(), $user->getEmail());
        $this->addFlash('success', 'Votre demande a été envoyé, elle sera traitée dans les plus brefs délais !');
      } catch (\Throwable $e) {
        $this->addFlash('danger', 'Erreur lors de l\'envoi : ' . $e->getMessage());
      }
      return $this->redirectToRoute('player_space');
    } else {
      $this->addFlash('warning', 'Vous devez être connecté faire cette demande.');
      return $this->redirectToRoute('app_login');
    }
  }
}
