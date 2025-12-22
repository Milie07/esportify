<?php

namespace App\Command;

use App\Service\MongoDBService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-mongo',
    description: 'Test MongoDB connection and insert a test document'
)]
class TestMongoCommand extends Command
{
    public function __construct(
        private MongoDBService $mongoDBService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln('Testing MongoDB connection...');

            // Test de connexion
            $collection = $this->mongoDBService->getCollection('test_connection');

            // Insertion d'un document de test
            $result = $collection->insertOne([
                'test' => 'Connection test from CLI',
                'timestamp' => new \MongoDB\BSON\UTCDateTime()
            ]);

            $output->writeln('<info>✓ MongoDB connection successful!</info>');
            $output->writeln('Inserted document ID: ' . $result->getInsertedId());

            // Comptage des documents dans contact_messages
            $contactCollection = $this->mongoDBService->getCollection('contact_messages');
            $contactCount = $contactCollection->countDocuments();
            $output->writeln('Contact messages count: ' . $contactCount);

            // Comptage des documents dans tournament_requests
            $tournamentsCollection = $this->mongoDBService->getCollection('tournament_requests');
            $tournamentsCount = $tournamentsCollection->countDocuments();
            $output->writeln('Tournament requests count: ' . $tournamentsCount);

            // Le test est réussi, pas besoin de lister les collections
            $output->writeln("\n✓ All tests passed!");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln('<error>✗ MongoDB connection failed!</error>');
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            $output->writeln('<error>File: ' . $e->getFile() . ':' . $e->getLine() . '</error>');

            return Command::FAILURE;
        }
    }
}
