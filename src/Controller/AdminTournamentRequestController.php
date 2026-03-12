<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Enum\CurrentStatus;
use App\Form\TournamentType;
use App\Service\OrganizerRequestsService;
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
      private OrganizerRequestsService $organizerRequestsService,
      private CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $limit = 10;
        $page = max(1, $request->query->getInt('page', 1));
        $total = $em->getRepository(Tournament::class)->count([]);
        $totalPages = max(1, (int) ceil($total / $limit));
        $page = min($page, $totalPages);

        // Un admin voit TOUS les tournois, pas seulement les siens
        $tournaments = $em->getRepository(Tournament::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            $limit,
            ($page - 1) * $limit
        );
        try {
            $messages = $this->tournamentService->getContactMessages();
            $requests = $this->tournamentService->getAllRequestsGroupedByStatus();
            $playerRequests = $this->organizerRequestsService->getAllRequestsGroupByStatus();
        } catch (\Throwable $e) {
            // Si MongoDB échoue, on affiche quand même la page
            $this->addFlash('warning', 'Erreur MongoDB : ' . $e->getMessage());
            $messages = [];
            $requests = ['pending' => [], 'validated' => [], 'refused' => [], 'stopped' => []];
            $playerRequests = ['pending' => [], 'validé' => [], 'refusé' => []];
        }

        /** @var \App\Entity\Member $user */
        $user = $this->getUser();
        $favoritesCollection = $user->getMemberAddFavorites();
        $avatarPath = $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg';

        $tournamentForm = $this->createForm(TournamentType::class, new Tournament())->createView();

        $myTournaments = $em->getRepository(Tournament::class)->findBy(
            ['organizer' => $user],
            ['createdAt' => 'DESC']
        );

        $response = $this->render('spaces/admin.html.twig', [
            'tournaments' => $tournaments,
            'messages' => $messages,
            'requestsPending' => $requests['pending'],
            'requestsValidated' => $requests['validated'],
            'requestsRefused' => $requests['refused'],
            'requestsStopped' => $requests['stopped'],
            'playerRequestsPending' => $playerRequests['pending'],
            'playerRequestsValide' => $playerRequests['validé'],
            'playerRequestsRefuse' => $playerRequests['refusé'],
            'favorites' => $favoritesCollection,
            'avatarUrl' => $avatarPath,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'tournamentForm' => $tournamentForm,
            'myTournaments' => $myTournaments,
        ]);

        $response->headers->set('Cache-Control', 'no-store');

        return $response;
    }

    public function show(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /** @var \App\Entity\Member $user */
        $user = $this->getUser();

        $tournament = $em->getRepository(Tournament::class)->find($id);
        if (!$tournament) {
            throw $this->createNotFoundException("Tournoi introuvable.");
        }

        $myTournaments = $em->getRepository(Tournament::class)->findBy(
            ['organizer' => $user],
            ['createdAt' => 'DESC']
        );

        $messages = $this->tournamentService->getContactMessages();
        $requests = $this->tournamentService->getAllRequestsGroupedByStatus();
        $playerRequests = $this->organizerRequestsService->getAllRequestsGroupByStatus();

        return $this->render('spaces/admin.html.twig', [
            'tournaments' => $myTournaments,
            'tournament' => $tournament,
            'messages' => $messages,
            'requestsPending' => $requests['pending'],
            'requestsValidated' => $requests['validated'],
            'requestsRefused' => $requests['refused'],
            'requestsStopped' => $requests['stopped'],
            'playerRequestsPending' => $playerRequests['pending'],
            'playerRequestsValide' => $playerRequests['validé'],
            'playerRequestsRefuse' => $playerRequests['refusé'],
            'favorites' => $user->getMemberAddFavorites(),
            'avatarUrl' => $user->getAvatarPath() ?: 'uploads/avatars/default-avatar.jpg',
            'tournamentForm' => $this->createForm(TournamentType::class, new Tournament())->createView(),
            'myTournaments' => $myTournaments,
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

        /** @var \App\Entity\Member $admin */
        $admin = $this->getUser();
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $this->tournamentService->validateTournament($tournament, $publicDirectory, $admin->getPseudo());

        $this->addFlash('success', 'Tournoi validé !');
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

        /** @var \App\Entity\Member $admin */
        $admin = $this->getUser();
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $this->tournamentService->refuseTournament($tournament, $publicDirectory, $admin->getPseudo());

        $this->addFlash('danger', 'Tournoi refusé');
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
