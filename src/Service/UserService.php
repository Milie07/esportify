<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\MemberAvatars;
use App\Repository\MemberRepository;
use App\Repository\MemberRolesRepository;
use App\Repository\MemberAvatarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MemberRepository $memberRepository,
        private MemberRolesRepository $memberRolesRepository,
        private MemberAvatarsRepository $memberAvatarsRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    /**
     * Valide les données d'inscription
     */
    public function validateRegistration(
        string $pseudo,
        string $email,
        string $password,
        string $confirmPassword,
        bool $cgu
    ): array {
        $errors = [];

        if (mb_strlen($pseudo) < 3) {
            $errors['pseudo'] = 'Le pseudo doit contenir au moins 2 caractères.';
        }
        if ($this->memberRepository->findOneBy(['pseudo' => $pseudo])) {
            $errors['pseudo'] = 'Pseudo déjà utilisé.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide.';
        }
        if ($this->memberRepository->findOneBy(['email' => $email])) {
            $errors['email'] = 'Email déjà utilisé.';
        }
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas.';
        }
        if (mb_strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.';
        }
        if (!$cgu) {
            $errors['conditions'] = 'Vous devez accepter les CGU.';
        }

        return $errors;
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $pseudo,
        string $email,
        string $password,
        int $avatarCode = 1
    ): Member {
        $user = new Member();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPseudo($pseudo);
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setMemberScore(0);

        $role = $this->memberRolesRepository->findOneBy(['code' => 'ROLE_PLAYER'])
            ?? $this->memberRolesRepository->findOneBy(['code' => 'PLAYER']);

        if (!$role) {
            throw new \RuntimeException('ROLE_PLAYER manquant dans member_roles.');
        }
        $user->setMemberRole($role);

        $avatar = $avatarCode > 0 ? $this->memberAvatarsRepository->findOneBy(['code' => $avatarCode]) : null;
        if (!$avatar) {
            $avatar = $this->memberAvatarsRepository->findOneBy(['code' => 1]);
        }
        if ($avatar) {
            $user->setMemberAvatar($avatar);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
