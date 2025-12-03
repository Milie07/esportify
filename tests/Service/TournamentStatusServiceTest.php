<?php

namespace App\Tests\Service;

use App\Entity\Tournament;
use App\Enum\CurrentStatus;
use App\Repository\TournamentRepository;
use App\Service\TournamentStatusService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TournamentStatusServiceTest extends TestCase
{
    public function testUpdateAllStatusChangesValidToEnCours(): void
    {
        // Créer un tournoi avec statut VALIDE et date de début passée
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getCurrentStatus')->willReturn(CurrentStatus::VALIDE);
        $tournament->method('getStartAt')->willReturn(new \DateTimeImmutable('-1 hour'));
        $tournament->method('getEndAt')->willReturn(new \DateTimeImmutable('+1 hour'));
        $tournament->expects($this->once())
            ->method('setCurrentStatus')
            ->with(CurrentStatus::EN_COURS);

        // Mock du repository
        $repository = $this->createMock(TournamentRepository::class);
        $repository->method('findAll')
            ->willReturn([$tournament]);

        // Mock de l'EntityManager
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $service = new TournamentStatusService($repository, $em);
        $service->updateAllStatus();
    }

    public function testUpdateAllStatusChangesEnCoursToTermine(): void
    {
        // Créer un tournoi avec statut EN_COURS et date de fin passée
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getCurrentStatus')->willReturn(CurrentStatus::EN_COURS);
        $tournament->method('getStartAt')->willReturn(new \DateTimeImmutable('-2 hours'));
        $tournament->method('getEndAt')->willReturn(new \DateTimeImmutable('-1 hour'));
        $tournament->expects($this->once())
            ->method('setCurrentStatus')
            ->with(CurrentStatus::TERMINE);

        $repository = $this->createMock(TournamentRepository::class);
        $repository->method('findAll')
            ->willReturn([$tournament]);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $service = new TournamentStatusService($repository, $em);
        $service->updateAllStatus();
    }

    public function testUpdateAllStatusDoesNothingForFutureTournaments(): void
    {
        // Créer un tournoi avec statut VALIDE mais date de début future
        $tournament = $this->createMock(Tournament::class);
        $tournament->method('getCurrentStatus')->willReturn(CurrentStatus::VALIDE);
        $tournament->method('getStartAt')->willReturn(new \DateTimeImmutable('+1 hour'));
        $tournament->method('getEndAt')->willReturn(new \DateTimeImmutable('+2 hours'));
        $tournament->expects($this->never())->method('setCurrentStatus');

        $repository = $this->createMock(TournamentRepository::class);
        $repository->method('findAll')
            ->willReturn([$tournament]);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $service = new TournamentStatusService($repository, $em);
        $service->updateAllStatus();
    }
}