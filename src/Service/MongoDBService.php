<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBService
{
    private Client $client;
    private string $database = 'esportify_messaging';

    public function __construct()
    {
        $this->client = new Client($_ENV['MONGODB_URL']);
    }

    public function getCollection(string $collectionName): Collection
    {
        return $this->client->{$this->database}->selectCollection($collectionName);
    }
}
