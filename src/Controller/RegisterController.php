<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberRoles;
use App\Entity\MemberAvatars;
use App\Repository\MemberRepository;
use App\Repository\MemberRolesRepository;
use App\Repository\MemberAvatarsRepository;
use App\Service\InputSanitizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class RegisterController extends AbstractController
{
  public function register(
    Request $request,
    InputSanitizer $san,
    MemberRepository $memberRepo,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $hasher,
    CsrfTokenManagerInterface $csrf,
    MemberRolesRepository $memberRoleRepo,
    MemberAvatarsRepository $avatarRepo
  ): Response {
    // CSRF
    $token = new CsrfToken('register', (string) $request->request->get('_csrf_token'));
    if (!$csrf->isTokenValid($token)) {
      $this->addFlash('danger', 'Jeton CSRF invalide.');
      return $this->redirectToRoute('signup_form');
    }

    // Lecture + nettoyage
    $firstName = $san->text($request->request->get('firstName', ''), 100);
    $lastName = $san->text($request->request->get('lastName', ''), 100);
    $pseudo  = $san->text($request->request->get('pseudo', ''), 100);
    $email   = $san->email($request->request->get('email', ''));
    $pass    = (string) $request->request->get('password', '');
    $pass2   = (string) $request->request->get('confirm_password', '');
    $cgu     = $request->request->getBoolean('conditions');

    // Validations serveur
    $errors = [];
    if (mb_strlen($pseudo) < 3) {
      $errors['pseudo'] = 'Le pseudo doit contenir au moins 2 caractères.';
    }
    if ($memberRepo->findOneBy(['pseudo' => $pseudo])) {
      $errors['pseudo'] = 'Pseudo déjà utilisé.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Adresse email invalide.';
    }
    if ($memberRepo->findOneBy(['email' => $email])) {
      $errors['email'] = 'Email déjà utilisé.';
    }
    if ($pass !== $pass2) {
      $errors['confirm_password'] = 'Les mots de passe ne correspondent pas.';
    }
    if (mb_strlen($pass) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $pass)) {
      $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.';
    }
    if (!$cgu) {
      $errors['conditions'] = 'Vous devez accepter les CGU.';
    }

    if (!empty($errors)) {
      return $this->render('auth/signup.html.twig', [
        'errors' => $errors,
        'old' => [
          'firstName' => $firstName,
          'lastName' => $lastName,
          'pseudo' => $pseudo,
          'mail' => $email,
        ]
      ]);
    }

    // Création + rôle par défaut
    $user = new Member();
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setPseudo($pseudo);
    $user->setEmail($email);
    $user->setPassword($hasher->hashPassword($user, $pass));
    $user->setMemberScore(0);

    // rôle principal
    $role = $memberRoleRepo->findOneBy(['code' => 'ROLE_PLAYER'])
      ?? $memberRoleRepo->findOneBy(['code' => 'PLAYER']);

    if (!$role) {
      throw new \RuntimeException('ROLE_PLAYER manquant dans member_roles.');
    }
    $user->setMemberRole($role);

    $avatarCode = (int) $request->request->get('avatar', 0);
    $avatar = $avatarCode > 0 ? $avatarRepo->findOneBy(['code' => $avatarCode]) : null;
    if (!$avatar) {
      $avatar = $avatarRepo->findOneBy(['code' => 1]);
    }
    if ($avatar) {
      $user->setMemberAvatar($avatar);
    }

    // Écriture BDD 

    try {
      $em->persist($user);
      $em->flush();
    } catch (\Throwable $e) {
      $this->addFlash('danger', 'Erreur enregistrement : ' . $e->getMessage());
      return $this->redirectToRoute('signup_form');
    }
    $this->addFlash('success', 'Compte créé. Vous pouvez vous connecter.');
    return $this->redirectToRoute('app_login');
  }
}
