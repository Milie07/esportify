<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Enum\CurrentStatus;
use App\Service\TournamentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTournamentRequestController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $tournaments = $em->getRepository(Tournament::class)->findBy(
            ['organizer' => $user],
            ['createdAt' => 'DESC']
        );

        $messages = $this->tournamentService->getContactMessages();
        $requests = $this->tournamentService->getAllRequestsGroupedByStatus();

        return $this->render('spaces/admin.html.twig', [
            'tournaments' => $tournaments,
            'messages' => $messages,
            'requestsPending' => $requests['pending'],
            'requestsValidated' => $requests['validated'],
            'requestsRefused' => $requests['refused'],
            'requestsStopped' => $requests['stopped'],
        ]);
    }

    public function show(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $tournaments = $em->getRepository(Tournament::class)->findBy(
            ['organizer' => $user],
            ['createdAt' => 'DESC']
        );

        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        $messages = $this->tournamentService->getContactMessages();
        $requests = $this->tournamentService->getAllRequestsGroupedByStatus();

        return $this->render('spaces/admin.html.twig', [
            'tournaments' => $tournaments,
            'tournament' => $tournament,
            'messages' => $messages,
            'requestsPending' => $requests['pending'],
            'requestsValidated' => $requests['validated'],
            'requestsRefused' => $requests['refused'],
            'requestsStopped' => $requests['stopped'],
        ]);
    }

    public function validate(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Vérification du token CSRF
        $submittedToken = $request->request->get('_token');
        $token = new CsrfToken('validate-tournament', $submittedToken);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        // Utiliser la méthode validateTournament qui gère le déplacement de l'image
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $this->tournamentService->validateTournament($tournament, $publicDirectory);

        $this->addFlash('success', 'Tournoi validé ! L\'image a été déplacée vers le dossier permanent.');
        return $this->redirectToRoute('admin_dashboard');
    }

    public function refuse(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Vérification du token CSRF
        $submittedToken = $request->request->get('_token');
        $token = new CsrfToken('refuse-tournament', $submittedToken);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        // Utiliser la méthode refuseTournament qui gère la suppression de l'image
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $this->tournamentService->refuseTournament($tournament, $publicDirectory);

        $this->addFlash('danger', 'Tournoi refusé et image supprimée.');
        return $this->redirectToRoute('admin_dashboard');
    }

    public function stopped(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Vérification du token CSRF
        $submittedToken = $request->request->get('_token');
        $token = new CsrfToken('stop-tournament', $submittedToken);

        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        $tournament->setCurrentStatus(CurrentStatus::TERMINE);
        $em->flush();

        $this->tournamentService->updateRequestStatus($id, 'terminé');

        $this->addFlash('danger', 'Tournoi terminé.');
        return $this->redirectToRoute('admin_dashboard');
    }
}
