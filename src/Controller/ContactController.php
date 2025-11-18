<?php

namespace App\Controller;

use MongoDB\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
  public function index(Request $request): Response
  {
    // Afficher la page contact
    if ($request->getMethod() === 'GET') {
      return $this->render('contact/index.html.twig');
    }

    // Traiter le formulaire
    if ($request->getMethod() === 'POST') {
      // Si l'utilisateur est connecté
      /** @var \App\Entity\Member|null $user */
      $user = $this->getUser();

      $pseudo = $user ? $user->getPseudo() : $request->request->get('pseudo');
      $role = $user ? $user->getMemberRole()->getCode() : $request->request->get('role');

      $email = $request->request->get('email');
      $subject = $request->request->get('subject');
      $message = $request->request->get('message');

      // Connexion MongoDB
      // L'URL est dans .env (MONGODB_URL)
      $client = new Client($_ENV['MONGODB_URL']);

      // Sélection de la BD et collection
      $collection = $client->esportify_messaging->contact_messages;

      // Insertion du message
      $collection->insertOne([
        'pseudo' => $pseudo,
        'role' => $role,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'createdAt' => new \MongoDB\BSON\UTCDateTime(),
        'status' => 'new'
      ]);

      // 6. Redirection ou page de confirmation
      return $this->redirectToRoute('contact');
    }

    // 7. Par sécurité (ne devrait jamais arriver)
    return new Response('Méthode non supportée', 405);
  }
}
