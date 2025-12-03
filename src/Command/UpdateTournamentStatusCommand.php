<?php

namespace App\Command;

use App\Service\TournamentStatusService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-tournament-status',
    description: 'Met à jour automatiquement les statuts des tournois (VALIDÉ → EN_COURS → TERMINÉ)',
)]
class UpdateTournamentStatusCommand extends Command
{
    public function __construct(
        private TournamentStatusService $statusService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Mise à jour des statuts des tournois...');

        $this->statusService->updateAllStatus();

        $io->success('Statuts mis à jour avec succès.');

        return Command::SUCCESS;
    }
}
