<?php

namespace App\Controller;

use App\Service\InputSanitizer;
use App\Service\TournamentService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CreateEventController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService
    ) {
    }

    public function create(Request $request, InputSanitizer $san, CsrfTokenManagerInterface $csrf): Response
    {
        if (!$this->isGranted('ROLE_ORGANIZER') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('GET')) {
            if ($this->isGranted('ROLE_ORGANIZER')) {
                return $this->render('spaces/organizer.html.twig');
            }

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->render('spaces/admin.html.twig');
            }
        }

        if ($request->isMethod('POST')) {
            // Validation CSRF
            $token = new CsrfToken('create_tournament', $request->request->get('_csrf_token'));
            if (!$csrf->isTokenValid($token)) {
                $this->addFlash('error', 'Token CSRF invalide. Veuillez réessayer.');
                return $this->isGranted('ROLE_ADMIN')
                    ? $this->redirectToRoute('admin_dashboard')
                    : $this->redirectToRoute('organizer_space');
            }

            $title = $san->sanitize($request->request->get('title'));
            $description = $san->sanitize($request->request->get('description'));
            $tagline = $san->sanitize($request->request->get('tagline'));
            $startAt = $request->request->get('startAt');
            $endAt = $request->request->get('endAt');
            $capacityGauge = (int) $request->request->get('capacityGauge');

            /** @var UploadedFile|null $file */
            $file = $request->files->get('tournamentImage');

            if (
                empty($title) || empty($description) ||
                empty($startAt) || empty($endAt) ||
                $capacityGauge <= 0 ||
                !$file instanceof UploadedFile
            ) {
                $this->addFlash('error', 'Tous les champs doivent être remplis.');

                return $this->isGranted('ROLE_ADMIN')
                    ? $this->redirectToRoute('admin_dashboard')
                    : $this->redirectToRoute('organizer_space');
            }

            $startDate = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $startAt);
            $endDate = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $endAt);

            if (!$startDate || !$endDate) {
                $this->addFlash('error', 'Format de date invalide.');

                return $this->isGranted('ROLE_ADMIN')
                    ? $this->redirectToRoute('admin_dashboard')
                    : $this->redirectToRoute('organizer_space');
            }

            /** @var \App\Entity\Member $user */
            $user = $this->getUser();
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/tournaments/';

            $this->tournamentService->createTournament(
                $title,
                $description,
                $tagline,
                $startDate,
                $endDate,
                $capacityGauge,
                $file,
                $user,
                $uploadDirectory
            );

            $this->addFlash('success', 'Le tournoi est créé et en attente de validation.');

            return $this->isGranted('ROLE_ADMIN')
                ? $this->redirectToRoute('admin_dashboard')
                : $this->redirectToRoute('organizer_space');
        }

        return new Response('Méthode non autorisée.', 405);
    }
}
