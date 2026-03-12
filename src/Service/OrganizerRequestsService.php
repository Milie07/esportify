<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\MemberRoles;
use App\Service\MongoDBService;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\BSON\UTCDateTime;

class OrganizerRequestsService
{
  public function __construct(
    private MongoDBService $mongoDBService,
    private EntityManagerInterface $entity_manager
  ) {}

  public function saveRequests(int $memberId, string $pseudo, string $email): void
  {
    $collection = $this->mongoDBService->getCollection('organizer_requests');
    $collection->insertOne([
      'member' => $memberId,
      'pseudo' => $pseudo,
      'email' => $email,
      'createdAt' => new \MongoDB\BSON\UTCDateTime(),
      'status' => 'pending'
    ]);
  }

  /*
  * Récupère toutes les demandes par status
  * Retourne un tableau PHP via normalizeRequests()
  */
  public function getRequestsByStatus(string $status)
  {
    $collection = $this->mongoDBService->getCollection('organizer_requests');
    $cursor = $collection->find(
      ['status' => $status],
      ['sort' => ['createdAt' => -1]]
    );
    return $this->normalizeRequests($cursor);
  }

  /*
  * Retourne les différents status organisés en tableau gropé
  */
  public function getAllRequestsGroupByStatus() 
  {
    return [
            'pending' => $this->getRequestsByStatus('pending'),
            'validé' => $this->getRequestsByStatus('validé'),
            'refusé' => $this->getRequestsByStatus('refusé'),
        ];
  }

  /*
  * Convertit le format BSON en tableau PHP et convertit la date au format DateTime 
  */
  private function normalizeRequests(iterable $cursor): array
  {
    $playerRequests = [];
    foreach ($cursor as $doc) {
      $row = iterator_to_array($doc);

      if (isset($row['createdAt']) && $row['createdAt'] instanceof UTCDateTime) {
        $row['createdAt'] = $row['createdAt']->toDateTime();
      } else {
        $row['createdAt'] = null;
      }
      $playerRequests[] = $row;
    }
    return $playerRequests;
  }

  /*
  * Valide le changement de rôle -> Mets à jour SQL
  * Mets à jour MongoDB
  */
  public function validateRequest(int $memberId): void 
  {
    $member = $this->entity_manager->getRepository(Member::class)->find($memberId);
    if (!$member) {
    throw new \RuntimeException("Utilisateur introuvable.");
    }
    $organizerRole = $this->entity_manager->getRepository(MemberRoles::class)->findOneBy(['code' => 'ROLE_ORGANIZER']);

    $collection = $this->mongoDBService->getCollection('organizer_requests');


    // valide le changement de status
    $member->setMemberRole($organizerRole);
    $this->entity_manager->flush();
    
    $collection->updateOne(
      ['member' => $memberId, 'status' => 'pending'],
      ['$set' => [
        'status' => 'validé',
        'updatedAt' => new \MongoDB\BSON\UTCDateTime()
        ]
      ]);
  }

  /*
  * Refuse le changement de rôle
  * Mets à jour MongoDB
  */
  public function refuseRequest(int $memberId): void 
  {
    $collection = $this->mongoDBService->getCollection('organizer_requests');
    $collection->updateOne(
      ['member' => $memberId, 'status' => 'pending'],
      ['$set' => [
        'status' => 'refusé',
        'updatedAt' => new \MongoDB\BSON\UTCDateTime()
        ]
      ]);
  }
}
