<?php
namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberRoles;
use App\Repository\MemberRepository;
use App\Repository\MemberRolesRepository;
use App\Repository\MemberAvatarsRepository; 
use App\Entity\MemberAvatars;   
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
        $name    = $san->text($request->request->get('name', ''), 100);
        $surname = $san->text($request->request->get('surname', ''), 100);
        $pseudo  = $san->text($request->request->get('pseudo', ''), 100);
        $email   = $san->email($request->request->get('mail', ''));
        $pass    = (string) $request->request->get('password', '');
        $pass2   = (string) $request->request->get('confirm_password', '');
        $cgu     = $request->request->getBoolean('conditions'); 

        dump([
            'cgu' => $cgu,
            'email' => $email,
            'pseudo' => $pseudo,
            'pass' => $pass,
            'pass2' => $pass2,
            'token_valid' => $csrf->isTokenValid($token)
        ]);

        // Validations serveur
        if (!$cgu) { 
            $this->addFlash('danger', 'CGU obligatoires.');      
            return $this->redirectToRoute('signup_form'); 
        }
        if (!$email) { 
            $this->addFlash('danger', 'Email invalide.');        
            return $this->redirectToRoute('signup_form'); 
        }
        if (mb_strlen($pseudo) < 2) { 
            $this->addFlash('danger', 'Pseudo trop court.');     
            return $this->redirectToRoute('signup_form'); 
        }
        if ($pass !== $pass2) { 
            $this->addFlash('danger', 'Mots de passe différents.'); 
            return $this->redirectToRoute('signup_form'); 
        }
        if (mb_strlen($pass) < 8) { 
            $this->addFlash('danger', 'Mot de passe trop court.');  
            return $this->redirectToRoute('signup_form'); 
        }
        if ($memberRepo->findOneBy(['email' => $email]) || $memberRepo->findOneBy(['pseudo' => $pseudo])) {
            $this->addFlash('danger', 'Email ou pseudo déjà utilisé.');
            return $this->redirectToRoute('signup_form');
        }

        // Création + rôle par défaut
        $user = new Member();
        $user->setFirstName($name);
        $user->setLastName($surname);
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
            $this->addFlash('danger', 'Erreur enregistrement : '.$e->getMessage()); 
            return $this->redirectToRoute('signup_form'); 
        } 
        $this->addFlash('success', 'Compte créé. Vous pouvez vous connecter.'); 
        return $this->redirectToRoute('app_login'); 
    } 
}