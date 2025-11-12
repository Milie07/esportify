<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\MemberRoles;
use App\Entity\MemberAvatars;
use App\Entity\TournamentImages;
use App\Entity\Tournament;
use App\Enum\MemberRoleLabel;
use App\Enum\CurrentStatus;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {  
        // --- Rôles des membres ---
        $rolesData = [
            ['member_role_label' => MemberRoleLabel::ADMIN, 'code' => 'ROLE_ADMIN'],
            ['member_role_label' => MemberRoleLabel::ORGANIZER, 'code' => 'ROLE_ORGANIZER'],
            ['member_role_label' => MemberRoleLabel::PLAYER, 'code' => 'ROLE_PLAYER'],
        ];

        $roles = [];
        foreach ($rolesData as $roleData) {
            $role = new MemberRoles();
            $role->setMemberRoleLabel($roleData['member_role_label']);
            $role->setCode($roleData['code']);
            $manager->persist($role);
            $roles[$roleData['code']] = $role;
        }

        // --- Avatars ---
        $avatarsData = [
            ['avatar_url' => 'uploads/avatars/avatar1.jpg', 'code' => 1],
            ['avatar_url' => 'uploads/avatars/avatar2.jpg', 'code' => 2],
            ['avatar_url' => 'uploads/avatars/avatar3.jpg', 'code' => 3],
            ['avatar_url' => 'uploads/avatars/avatar4.jpg', 'code' => 4],
            ['avatar_url' => 'uploads/avatars/avatar5.jpg', 'code' => 5],
            ['avatar_url' => 'uploads/avatars/avatar6.jpg', 'code' => 6],
            ['avatar_url' => 'uploads/avatars/avatar7.jpg', 'code' => 7],
            ['avatar_url' => 'uploads/avatars/avatar8.jpg', 'code' => 8],
        ];

        $avatars = [];
        foreach ($avatarsData as $avatarData) {
            $avatar = new MemberAvatars();
            $avatar->setAvatarUrl($avatarData['avatar_url']);
            $avatar->setCode($avatarData['code']);
            $manager->persist($avatar);
            $avatars[$avatarData['code']] = $avatar;
        }

        // --- Membres ---
        $membersData = [
            ['first_name' => 'Elodie', 'last_name' => 'Marchal', 'pseudo' => 'ElodieAdmin', 'email' => 'admin.elodie@esportify.com', 'password_hash' => 'AdminElodie2025', 'member_score' => 20, 'avatar' => 6, 'role' => 'ROLE_ADMIN'],
            ['first_name' => 'Hugo', 'last_name' => 'Perret', 'pseudo' => 'HugoOrga', 'email' => 'orga.hugo@esportify.com', 'password_hash' => 'OrgaHugo2025', 'member_score' => 80, 'avatar' => 4, 'role' => 'ROLE_ORGANIZER'],
            ['first_name' => 'Alex', 'last_name' => 'Durand', 'pseudo' => 'AlexOrga', 'email' => 'orga.alex@esportify.com', 'password_hash' => 'OrgaAlex2025', 'member_score' => 90, 'avatar' => 2, 'role' => 'ROLE_ORGANIZER'],
            ['first_name' => 'Tom', 'last_name' => 'Garcia', 'pseudo' => 'TomPlayer', 'email' => 'play.tom@esportify.com', 'password_hash' => 'PlayTom2025', 'member_score' => 30, 'avatar' => 8, 'role' => 'ROLE_PLAYER'],
            ['first_name' => 'Ines', 'last_name' => 'Garcia', 'pseudo' => 'InesPlayer', 'email' => 'play.ines@esportify.com', 'password_hash' => 'PlayInes2025', 'member_score' => 10, 'avatar' => 1, 'role' => 'ROLE_PLAYER'],
            ['first_name' => 'Raphael', 'last_name' => 'Malassis', 'pseudo' => 'RaphPlayer', 'email' => 'play.raph@esportify.com', 'password_hash' => 'PlayRaph2025', 'member_score' => 100, 'avatar' => 3, 'role' => 'ROLE_PLAYER'],
            ['first_name' => 'Emma', 'last_name' => 'Corrompt', 'pseudo' => 'EmmaPlayer', 'email' => 'play.emma@esportify.com', 'password_hash' => 'PlayEmma2025', 'member_score' => 40, 'avatar' => 5, 'role' => 'ROLE_PLAYER'],
            ['first_name' => 'Nais', 'last_name' => 'Malassis', 'pseudo' => 'NaisPlayer', 'email' => 'play.nais@esportify.com', 'password_hash' => 'PlayNais2025', 'member_score' => 30, 'avatar' => 7, 'role' => 'ROLE_PLAYER'],
        ];

        $members = [];
        foreach ($membersData as $memberData) {
            $member = new Member();
            $member->setFirstName($memberData['first_name']);
            $member->setLastName($memberData['last_name']);
            $member->setPseudo($memberData['pseudo']);
            $member->setEmail($memberData['email']);
            $hashedPassword = $this->hasher->hashPassword($member, $memberData['password_hash']);
            $member->setPassword($hashedPassword);
            $member->setMemberScore($memberData['member_score']);
            $member->setMemberAvatar($avatars[$memberData['avatar']]);
            $member->setMemberRole($roles[$memberData['role']]);
            $manager->persist($member);
            $members[$memberData['pseudo']] = $member;
        }

        // --- Images des Tournois ---
        $tournamentImageData = [
          ['image_url' => 'uploads\tournaments\dernierCombo1-freepik-ia_resultat.jpg', 'code' => 1],
          ['image_url' => 'uploads\tournaments\eclipseMasters1-freepik-ia_resultat.jpg', 'code' => 2],
          ['image_url' => 'uploads\tournaments\ironArena1-freepik-ia_resultat.jpg', 'code' => 3],
          ['image_url' => 'uploads\tournaments\neonRift1-bert-b-b6f7WaA-NZk-unsplash_resultat.jpg', 'code' => 4],
          ['image_url' => 'uploads\tournaments\NextLevelCup.jpg', 'code' => 5],
          ['image_url' => 'uploads\tournaments\noobGames1-freepik-ia_resultat.jpg', 'code' => 6],
          ['image_url' => 'uploads\tournaments\pixelPanic4-freepik_resultat.jpg', 'code' => 7],
          ['image_url' => 'uploads\tournaments\pixelWarzone1-freepik-ia_resultat.jpg', 'code' => 8],
          ['image_url' => 'uploads\tournaments\quantumBash1-freepik-ia_resultat.jpg', 'code' => 9],
        ];

        $tournamentImages = [];
        foreach($tournamentImageData as $imageData) {
          $image = new TournamentImages();
          $image->setImageUrl($imageData['image_url']);
          $image->setCode($imageData['code']);
          $manager->persist($image);
          $tournamentImages[$imageData['code']] = $image;
        }

        // --- Tournois ---
        $tournamentData = [
          ['title' => 'Iron Arena', 'description' => "Chaque Round est un pas de plus vers la gloire...Ou le respawn. Un contre Un où l’endurance est une vertu, la stratégie une arme, et la sueur virtuelle, une offrande. Les participants y enchaînent des rounds épiques dans des jeux de combat et de survie, jusqu’à ce que le métal plie. Préparez vos nerfs, car l’acier n’a pas d’âme.", 'start_at' => '2025-11-12 10:00:00', 'end_at' => '2025-11-14 10:00:00', 'capacity_gauge' => 70, 'tagline' => "Le Tournois où seuls les plus solides survivent", 'created_at' => '2025-11-01 10:00:00', 'current_status' => CurrentStatus::EN_COURS, 'tournamentImage' => 3, 'member' => 'HugoOrga'],
          
          ['title' => 'Neon Rift', 'description' => "Bienvenue dans Neon Rift, un affontement cyber-punk en FPS stratégique en solo ou en équipe, où les néons dessinent des univers parallèles et les réflexes s’aiguisent à la vitesse de la lumière. FPS futuristes, décors glitchés, modes VR et réalité déformée — c’est un tournoi où l’on ne joue pas, on transcende.", 'start_at' => '2025-11-25 10:00:00', 'end_at' => '2025-11-26 10:00:00',  'capacity_gauge' => 80, 'tagline' => "Fracturez le réel, entrez dans la faille.", 'created_at' => '2025-11-10 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 4, 'member' => 'AlexOrga'],
          
          ['title' => 'Pixel Panic', 'description' => "En solo ou en équipe, Pixel Panic est un tournoi pixélisé, rétro-maniaque où chaque explosion en 8 bits est une déclaration de guerre. Des jeux à l’ancienne, mais une intensité bien actuelle : compétitions arcade, speedruns et chaos coloré garantis. Pour ceux qui pensent qu’un bon pixel vaut mieux qu’un mauvais shader.", 'start_at' => '2025-11-30 10:00:00', 'end_at' => '2025-12-01 10:00:00', 'capacity_gauge' => 40, 'tagline' => "Une avalanche de pixels. Une surdose d’action.", 'created_at' => '2025-11-20 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 7, 'member' => 'AlexOrga'],
          
          ['title' => 'Noob Games', 'description' => "Un joyeux chaos en solo ou en équipe où même perdre peut faire gagner. Les Noob Games sont une ode à la découverte, à la maladresse glorieuse, à la première victoire hasardeuse. Ici, pas de méta, pas d’élitisme : tout le monde a une chance, surtout ceux qui n’en ont jamais eue. On y entre noob, on en ressort joueur.", 'start_at' => '2025-12-10 10:00:00', 'end_at' => '2025-12-15 10:00:00', 'capacity_gauge' => 60, 'tagline' => "Parce qu’il faut bien commencer quelque part !", 'created_at' => '2025-12-01 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 6, 'member' => 'ElodieAdmin'],
          
          ['title' => 'Eclipse Masters', 'description' => "En solo ou en équipe c'est un événement mystique et tactique. L’Eclipse Masters réunit les meilleurs stratèges autour de MOBA et jeux d’équipe. Chaque match est une danse d’ombres, chaque mouvement une menace. Dans l’obscurité, la coordination est reine.", 'start_at' => '2025-12-16 10:00:00', 'end_at' => '2025-12-17 10:00:00', 'capacity_gauge' => 60, 'tagline' => "Quand la lumière s’éteint, seuls les maîtres brillent.", 'created_at' => '2025-12-01 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 2, 'member' => 'HugoOrga'],
          
          ['title' => 'Quantum Bash', 'description' => "Tournoi rapide et brutal où chaque manche ne dure que 5 minutes. En solo ou en équipe Quantum Bash promet un univers explosif où la gravité prend des vacances. FPS à gravité inversée, puzzles temporels, affrontements dans des arènes aux règles quantiques. Vous pensiez avoir tout vu ? Pas dans cet univers.", 'start_at' => '2025-12-18 10:00:00', 'end_at' => '2025-12-18 20:00:00','capacity_gauge' => 40, 'tagline' => "Détruisez les lois de la physique… et vos adversaires.", 'created_at' => '2025-12-10 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 9, 'member' => 'ElodieAdmin'],
          
          ['title' => 'Pixel Warzone', 'description' => "Ne vous laissez pas berner par les couleurs pastels : Pixel Warzone est une zone de guerre. Jeux de type battle royale en pixel art, ambiance cartoon… mais stratégies impitoyables. Pour les joueurs qui aiment quand ça saigne... en 16 couleurs.", 'start_at' => '2025-12-27 10:00:00', 'end_at' => '2025-12-28 20:00:00', 'capacity_gauge' => 100, 'tagline' => "Les graphismes sont mignons, mais les combats font mal.", 'created_at' => '2025-12-15 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 8, 'member' => 'AlexOrga'],
          
          ['title' => 'Next Level Cup', 'description' => "Tournoi hybride mêlant énigmes interactives, défis logiques et gameplay progressif. Chaque étape vous pousse à aller plus haut, plus vite, plus malin. La Next Level Cup ne récompense pas seulement les skills, mais aussi l’évolution. Soyez prêt à muter.", 'start_at' => '2026-01-04 10:00:00', 'end_at' => '2026-01-04 20:00:00', 'capacity_gauge' => 50, 'tagline' => "Pas juste un jeu. Une ascension.", 'created_at' => '2025-12-25 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 5, 'member' => 'AlexOrga'],
          
          ['title' => 'Dernier Combo', 'description' => "Jeux de briques en solo. Dans ce tournoi dédié aux fans de Tétris, tout se joue dans l’ultime combo. Ultra technique, ultra nerveux, Dernier Combo met en scène les meilleurs fighters dans des duels millimétrés. Ici, une erreur, c’est le chaos. Une réussite, c’est l’extase.", 'start_at' => '2026-01-10 12:00:00', 'end_at' => '2026-01-11 18:00:00', 'capacity_gauge' => 90, 'tagline' => "Une seule brique peut tout changer.", 'created_at' => '2025-01-02 10:00:00', 'current_status' => CurrentStatus::VALIDE, 'tournamentImage' => 1, 'member' => 'HugoOrga'],
        ];

        foreach($tournamentData as $tournamentData) {
          $tournament = new Tournament();
          $tournament->setTitle($tournamentData['title']);
          $tournament->setDescription($tournamentData['description']);
          $tournament->setStartAt(new \DateTimeImmutable($tournamentData['start_at']));
          $tournament->setEndAt(new \DateTimeImmutable($tournamentData['end_at']));
          $tournament->setCapacityGauge($tournamentData['capacity_gauge']);
          $tournament->setTagline($tournamentData['tagline']);
          $tournament->setCreatedAt(new \DateTimeImmutable($tournamentData['created_at']));
          $tournament->setCurrentStatus($tournamentData['current_status']);
          $tournament->setTournamentImage($tournamentImages[$tournamentData['tournamentImage']]);
          $tournament->setOrganizer($members[$tournamentData['member']]);
          $manager->persist($tournament);
        }

        $manager->flush();
        echo "Fixtures exécutées.\n";
    }
}
