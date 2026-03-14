<?php

namespace App\Service;

class ContactService
{
    public function __construct(
        private MongoDBService $mongoDBService
    ) {
}
    /**
     * Sauvegarde un message de contact dans MongoDB
     */
    public function saveContactMessage(
        ?string $pseudo,
        string $role,
        string $email,
        string $subject,
        string $message,
    ): void {
        $collection = $this->mongoDBService->getCollection('contact_messages');
        $collection->insertOne([
            'pseudo' => $pseudo,
            'role' => $role,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'createdAt' => new \MongoDB\BSON\UTCDateTime(),
            'status' => 'new'
        ]);
    }
    public function deleteContactMessage(string $id): void
  {
    $collection = $this->mongoDBService->getCollection('contact_messages');
    $collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
  }
}
