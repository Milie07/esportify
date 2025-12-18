<?php

namespace App\Controller;

use App\Service\TournamentStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentStatusUpdateController extends AbstractController
{
  /**
   * Route publique pour la mise à jour automatique des statuts (cron externe)
   * Nécessite un token secret dans les paramètres de requête
   */
  #[Route('/admin/update-tournaments-status', name: 'admin_update_tournament_status', methods: ['GET', 'POST'])]
  public function update(Request $request, TournamentStatusService $service): Response
  {
    // Récupérer le token depuis les paramètres de requête ou les headers
    $providedToken = $request->query->get('token') ?? $request->headers->get('X-Cron-Token');
    $expectedToken = $_ENV['CRON_SECRET_TOKEN'] ?? null;

    // Cas 1 : Accès externe avec token (GitHub Actions, cron externe)
    if ($providedToken !== null) {
      if ($expectedToken === null) {
        return new JsonResponse([
          'success' => false,
          'error' => 'CRON_SECRET_TOKEN not configured'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      if ($providedToken !== $expectedToken) {
        return new JsonResponse([
          'success' => false,
          'error' => 'Invalid token'
        ], Response::HTTP_FORBIDDEN);
      }

      // Token valide : exécuter la mise à jour
      try {
        $service->updateAllStatus();
        return new JsonResponse([
          'success' => true,
          'message' => 'Tournament statuses updated successfully',
          'timestamp' => (new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')))->format('c')
        ]);
      } catch (\Exception $e) {
        return new JsonResponse([
          'success' => false,
          'error' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    }

    // Cas 2 : Accès depuis l'interface admin (nécessite authentification)
    // Vérifier que l'utilisateur est connecté et a le rôle ADMIN
    if (!$this->isGranted('ROLE_ADMIN')) {
      throw $this->createAccessDeniedException('Accès réservé aux administrateurs.');
    }

    // Exécuter la mise à jour
    $service->updateAllStatus();
    $this->addFlash('success', 'Statuts des tournois mis à jour.');
    return $this->redirectToRoute('app_events');
  }
}
