<?php
namespace App\Controller;

use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class HomeController extends AbstractController
{
	public function index(TournamentRepository $tournamentRepository): Response
	{
		$carouselTournaments = $tournamentRepository->findValidatedForCarousel(10);

        return $this->render('home/index.html.twig', [
            'carouselTournaments' => $carouselTournaments,
        ]);
	}
}