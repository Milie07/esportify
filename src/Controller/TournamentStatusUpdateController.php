<?php

namespace App\Controller;

use App\Service\TournamentStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TournamentStatusUpdateController extends AbstractController
{
  #[Route('/admin/update-tournaments-status', name: 'admin_update_tournament_status')]
  public function update(TournamentStatusService $service)
  {
    $service->updateAllStatus();
    $this->addFlash('success', 'Statuts des tournois mis à jour.');
    return $this->redirectToRoute('app_events');
  }
}
