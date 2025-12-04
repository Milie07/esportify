<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Service\TournamentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};

final class CreateEventController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private EntityManagerInterface $entityManager
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

        $data = ['tournamentForm' => $form->createView()];

        if ($this->isGranted('ROLE_ADMIN')) {
            // Pour l'admin, récupérer tous les tournois
            $data['tournaments'] = $this->entityManager->getRepository(Tournament::class)->findBy(
                [],
                ['createdAt' => 'DESC']
            );
            try {
                $data['messages'] = $this->tournamentService->getContactMessages();
                $requests = $this->tournamentService->getAllRequestsGroupedByStatus();
                $data['requestsPending'] = $requests['pending'];
                $data['requestsValidated'] = $requests['validated'];
                $data['requestsRefused'] = $requests['refused'];
                $data['requestsStopped'] = $requests['stopped'];
            } catch (\Throwable $e) {
                $data['messages'] = [];
                $data['requestsPending'] = [];
                $data['requestsValidated'] = [];
                $data['requestsRefused'] = [];
                $data['requestsStopped'] = [];
            }
        } else {
            // Pour l'organisateur, ajouter ses tournois et avatar
            /** @var \App\Entity\Member $user */
            $user = $this->getUser();
            $data['tournaments'] = $this->entityManager->getRepository(Tournament::class)->findBy(
                ['organizer' => $user],
                ['createdAt' => 'DESC']
            );
            $data['avatarUrl'] = $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg';
        }

        return $this->render($template, $data);
    }
}
