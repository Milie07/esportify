<?php
namespace App\Controller\Api;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventApiController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function index(TournamentRepository $tournamentRepository): Response
    {
        $events = $tournamentRepository->findValidatedOrRunning();
        
        return $this->render('events/index.html.twig', ['events' => $events,]);
    }    
}