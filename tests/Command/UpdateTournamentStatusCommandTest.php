<?php

namespace App\Tests\Command;

use App\Command\UpdateTournamentStatusCommand;
use App\Service\TournamentStatusService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateTournamentStatusCommandTest extends KernelTestCase
{
    public function testExecuteSuccessfully(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:update-tournament-status');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Mise à jour des statuts des tournois', $output);
        $this->assertStringContainsString('Statuts mis à jour avec succès', $output);
    }

    public function testCommandHasCorrectNameAndDescription(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:update-tournament-status');

        $this->assertEquals('app:update-tournament-status', $command->getName());
        $this->assertStringContainsString('Met à jour automatiquement les statuts des tournois', $command->getDescription());
    }
}