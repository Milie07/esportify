<?php
namespace App\Controller;

use App\Entity\Member;
use App\Service\FavoriteEventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteEventController extends AbstractController
{
    public function __construct(
        private FavoriteEventService $favoriteEventService
    ) 
    {
    }

    #[Route('/favorite/event/add/{tournamentId}', name: 'add_favorite_event')]
    public function addFavoriteEvent(string $tournamentId): RedirectResponse
    {
      // Ajouter un tournois en favoris pour l'utilisateur connecté
      /** @var \App\Entity\Member|null $user */
      $user = $this->getUser();

      if ($user) {
        try {
            $this->favoriteEventService->addFavoriteEvent($user, $tournamentId);
            $this->addFlash('success', 'Évènement ajouté aux favoris !');
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Erreur lors de l\'ajout aux favoris : ' . $e->getMessage());
        }
        return $this->redirectToRoute('events');
      } else {
          $this->addFlash('warning', 'Vous devez être connecté pour ajouter un évènement aux favoris.');
          return $this->redirectToRoute('app_login');
      }
    }

    #[Route('/favorite/event/remove/{tournamentId}', name: 'remove_favorite_event')]
    public function removeFavoriteEvent(string $tournamentId): RedirectResponse
    {
      // Retirer un tournois en favoris pour l'utilisateur connecté
      /** @var \App\Entity\Member|null $user */
      $user = $this->getUser();

      if ($user) {
        try {
            $this->favoriteEventService->removeFavoriteEvent($user, $tournamentId);
            $this->addFlash('success', 'Évènement retiré des favoris !');
        } catch (\Throwable $e) {
            $this->addFlash('danger', 'Erreur lors du retrait des favoris : ' . $e->getMessage());
        }
        $route = match(true) {
            $this->isGranted('ROLE_ADMIN')     => 'admin_dashboard',
            $this->isGranted('ROLE_ORGANIZER') => 'organizer_space',
            default                            => 'player_space',
        };
        return $this->redirectToRoute($route);
      } else {
          $this->addFlash('warning', 'Vous devez être connecté pour gérer vos favoris.');
          return $this->redirectToRoute('app_login');
      }
    }
}



