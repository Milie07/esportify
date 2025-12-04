<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Service\TournamentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class CreateEventController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService
    ) {
    }

    public function create(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ORGANIZER') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\Member $user */
            $user = $this->getUser();
            $file = $form->get('tournamentImage')->getData();
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/tournaments/';

            try {
                $this->tournamentService->createTournament(
                    $tournament->getTitle(),
                    $tournament->getDescription(),
                    $tournament->getTagline(),
                    $tournament->getStartAt(),
                    $tournament->getEndAt(),
                    $tournament->getCapacityGauge(),
                    $file,
                    $user,
                    $uploadDirectory
                );

                $this->addFlash('success', 'Le tournoi est créé et en attente de validation.');
            } catch (\Throwable $e) {
                $this->addFlash('danger', 'Erreur lors de la création du tournoi : ' . $e->getMessage());
            }

            return $this->isGranted('ROLE_ADMIN')
                ? $this->redirectToRoute('admin_dashboard')
                : $this->redirectToRoute('organizer_space');
        }

        // Affichage du formulaire (GET)
        $template = $this->isGranted('ROLE_ADMIN') ? 'spaces/admin.html.twig' : 'spaces/organizer.html.twig';

        return $this->render($template, [
            'tournamentForm' => $form->createView(),
        ]);
    }
}
