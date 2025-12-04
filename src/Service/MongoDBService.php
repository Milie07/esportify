<?php

namespace App\Service;

use MongoDB\Client;
use MongoDB\Collection;

class MongoDBService
{
    private Client $client;
    private string $database = 'esportify';

    public function __construct(string $mongodbUrl)
    {
        $this->client = new Client($mongodbUrl);
    }

    public function getCollection(string $collectionName): Collection
    {
        return $this->client->{$this->database}->selectCollection($collectionName);
    }
}
