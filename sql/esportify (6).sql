-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2025 at 02:42 PM
-- Server version: 8.4.3
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esportify`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250922170447', '2025-09-22 17:13:15', 100);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` int UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `member_score` int NOT NULL DEFAULT '0',
  `member_avatar_id` int UNSIGNED DEFAULT NULL,
  `member_role_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `first_name`, `last_name`, `pseudo`, `email`, `password_hash`, `member_score`, `member_avatar_id`, `member_role_id`) VALUES
(1, 'Elodie', 'Marchal', 'AdminElodie', 'admin.elodie@esportify.com', '$2y$10$vnpRe9e0xMMyMS2lE1zceecbEOVsGNMiiwzn/ZynkyPemqCW7tYiK', 20, 6, 4),
(2, 'Paul', 'Martin', 'AdminPaul', 'admin.paul@esportify.com ', '$2y$10$cRGhlb/jnWXnyc0UCnWjuO7akoiWI7N5auvGubkvHymgTLZ/UvOfu', 70, 1, 4),
(3, 'Luc', 'Morel', 'LucM ', 'luc.morel@example.com ', '$2y$10$wv6JGojmOtr/vacWlndF0.3VPj0Tybwbi3hxUKPhKffDhFh8AZVxO', 30, 2, 5),
(4, 'Sonia', 'Lefevre', 'SoniaL', 'sonia.lefevre@example.com ', '$2y$10$Bfb.4bx3laWkQO1pCkhh6OelyAe9p0X2owk4EY8NsA1QuD/S1UrLC', 80, 5, 5),
(5, 'Nadia', 'Karim', 'NadiaK', 'nadia.karim@example.com ', '$2y$10$rIoEe4LrsF7wvaK3vMYhjuIYcp5DCuGsksbCpf2IlRKCT.l7sXuoS', 20, 3, 5),
(6, 'Yann', 'Meunier', 'YannM', 'yann.meunier@example.com ', '$2y$10$6UvWmCBxlzlmbJU1NzfOf.9l4J3P6XSJ1C0xH8vO/ocGztT8Is7Y.', 20, 7, 5),
(7, 'Clara', 'Pinto', 'ClaraP', 'clara.pinto@example.com ', '$2y$10$CGJNgMWWnwbqr5GJrUTyie150i3dK8C4pAMl8lfCOhAuq.dFMSVoa', 40, 6, 5),
(8, 'Hugo', 'Perret', 'HugoPR', 'hugo.perret@example.com ', '$2y$10$pr6YtS7/2fzPFS0NspDW0uYb24dpuuZ9oZlAXBVM4rivfB98vf53q', 80, 8, 5),
(9, 'Rayan', 'Benali', 'RayanB', 'rayan.benali@example.com', '$2y$10$703T6YAg6osG/QWSoPtFTuApEBWKW3wsggIq9767Yt1fSzvwcOtXG', 20, 4, 5),
(10, 'Théo', 'Bernard', 'TheoB', 'theo.bernard@example.com', '$2y$10$F2OQSTxgsTVcHofC4E8CQuavGzaT605KVRXAAAcOjhqvwtp.9Iu0G', 20, 8, 5),
(11, 'Alex', 'Durand', 'Miraak', 'alex.durand@example.com', '$2y$10$eJG79nxV3FlUjcVZtMbql.TayPOQyGC1DkyYEAnYQYbNMZiq9JFTC', 90, 6, 6),
(12, 'Emma', 'Petit', 'ShadowLynx', 'emma.petit@example.com', '$2y$10$HiEkpcrv5XFWiMIbDFtKhuVqD6zsVVTGyoa6iC6XWk4fFAqwx/IZu', 30, 3, 6),
(13, 'Hugo', 'Laurent', 'Voltix', 'hugo.laurent@example.com', '$2y$10$Eg6ufr6KFKq4VpfaWRYKR.zJiItLQ1GFNZfLb4fYaxIkX7yNwwtXy', 100, 7, 6),
(14, 'Ines', 'Robert', 'AstraNova', 'ines.robert@example.com', '$2y$10$gQ5bluKDGiw7JJsyIhm2o.PQvoDsrggKHTL7.lWXem73qN9fYoh5a', 20, 4, 6),
(15, 'Tom', 'Garcia', 'NyxZero', 'tom.garcia@example.com', '$2y$10$.cRrTlxRbUH6LX1UZ6tK4OErwR1gCl/aFJIHzTolSy/oIs26Hkyxa', 30, 1, 6),
(16, 'Zoé', 'Leroy', 'HexaFox', 'zoe.leroy@example.com', '$2y$10$ye1i6p3T8j0F6sRbGu9MVOLl3giwrUhguu.q1BvdJGibHnp/K1m8m', 70, 4, 6),
(17, 'Yanis', 'Fontaine', 'PixelWolf', 'yanis.fontaine@example.com', '$2y$10$Wh5mslgzy8mSLqY10mSVwut/RW0nQXy9wP5kBiwbBiEmo1WdjUGw6', 30, 3, 6),
(18, 'Sarah', 'Chevalier', 'NovaRift', 'sarah.chevalier@example.com', '$2y$10$BhwAl7FKA8jaauIRA.VKz.Z5Tgww8Xt6IAsFaIk5dCkGe.1.tJQ2y', 40, 5, 6),
(19, 'Jade', 'Lopez', 'Kaelix', 'jade.lopez@example.com', '$2y$10$GKW2GnSGedbxA8g/7ux53.TbAow7rXct8j3BsLg0jHv8MlvQyVrqK', 20, 4, 6),
(20, 'Lucas', 'Moreau', 'Zenithor', 'lucas.moreau@example.com', '$2y$10$cMN/YCeCiMzDUALroEXzTekdZZFg5bpS3h2RUPagoFUKqzYT/fyYi', 80, 8, 6),
(21, 'Lina', 'Fournier', 'CryoByte', 'lina.fournier@example.com', '$2y$10$9VdfbMaqGCDGGE3kFisb.uz7sdZUg9f0885VhC4.XHC/c.ngBABSC', 20, 6, 6),
(22, 'Adam', 'Girard', 'RogueNox', 'adam.girard@example.com', '$2y$10$a7n.uYyi2byl4SDfrCd9Ee/EJpKQp84FZr/jxPyyZALjh3kZeL53K', 50, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `member_add_favorites_tournament`
--

DROP TABLE IF EXISTS `member_add_favorites_tournament`;
CREATE TABLE `member_add_favorites_tournament` (
  `member_id` int UNSIGNED NOT NULL,
  `tournament_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_avatars`
--

DROP TABLE IF EXISTS `member_avatars`;
CREATE TABLE `member_avatars` (
  `member_avatar_id` int UNSIGNED NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `code` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member_avatars`
--

INSERT INTO `member_avatars` (`member_avatar_id`, `avatar_url`, `code`) VALUES
(1, 'uploads/avatars/avatar1.jpg', 1),
(2, 'uploads/avatars/avatar2.jpg', 2),
(3, 'uploads/avatars/avatar3.jpg', 3),
(4, 'uploads/avatars/avatar4.jpg', 4),
(5, 'uploads/avatars/avatar5.jpg', 5),
(6, 'uploads/avatars/avatar6.jpg', 6),
(7, 'uploads/avatars/avatar7.jpg', 7),
(8, 'uploads/avatars/avatar8.jpg', 8);

-- --------------------------------------------------------

--
-- Table structure for table `member_moderate_roles`
--

DROP TABLE IF EXISTS `member_moderate_roles`;
CREATE TABLE `member_moderate_roles` (
  `member_id` int UNSIGNED NOT NULL,
  `member_role_id` int UNSIGNED NOT NULL,
  `member_label_status` varchar(20) NOT NULL DEFAULT 'Actif',
  `assigned_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_participate_tournament`
--

DROP TABLE IF EXISTS `member_participate_tournament`;
CREATE TABLE `member_participate_tournament` (
  `member_id` int UNSIGNED NOT NULL,
  `tournament_id` int UNSIGNED NOT NULL,
  `tournament_score` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_register_tournament`
--

DROP TABLE IF EXISTS `member_register_tournament`;
CREATE TABLE `member_register_tournament` (
  `member_id` int UNSIGNED NOT NULL,
  `tournament_id` int UNSIGNED NOT NULL,
  `date_register` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_roles`
--

DROP TABLE IF EXISTS `member_roles`;
CREATE TABLE `member_roles` (
  `member_role_id` int UNSIGNED NOT NULL,
  `member_role_label` varchar(255) NOT NULL DEFAULT 'Player',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL,
  `code` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member_roles`
--

INSERT INTO `member_roles` (`member_role_id`, `member_role_label`, `created_at`, `updated_at`, `code`) VALUES
(1, 'Player', '2025-09-17 17:13:01', NULL, 'PLAYER'),
(2, 'Organizer', '2025-09-17 17:13:01', NULL, 'ORGANIZER'),
(3, 'Admin', '2025-09-17 17:13:26', NULL, 'ADMIN'),
(4, 'Admin', '2025-09-23 12:50:22', '2025-09-23 12:50:22', 'ROLE_ADMIN'),
(5, 'Organizer', '2025-09-23 12:50:22', '2025-09-23 12:50:22', 'ROLE_ORGANIZER'),
(6, 'Player', '2025-09-23 12:50:22', '2025-09-23 12:50:22', 'ROLE_PLAYER');

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE `tournament` (
  `tournament_id` int UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `start_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `end_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `capacity_gauge` int NOT NULL DEFAULT '0',
  `tagline` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `current_status` varchar(20) NOT NULL DEFAULT 'En Attente',
  `tournament_image_id` int UNSIGNED DEFAULT NULL,
  `member_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournament_id`, `title`, `description`, `start_at`, `end_at`, `capacity_gauge`, `tagline`, `created_at`, `current_status`, `tournament_image_id`, `member_id`) VALUES
(1, 'Iron Arena', 'Chaque Round est un pas de plus vers la gloire...Ou le respawn. Un contre Un où l’endurance est une vertu, la stratégie une arme, et la sueur virtuelle, une offrande. Les participants y enchaînent des rounds épiques dans des jeux de combat et de survie, jusqu’à ce que le métal plie. Préparez vos nerfs, car l’acier n’a pas d’âme.', '2025-09-24 10:00:00', '2025-09-25 15:00:00', 70, 'Le Tournois où seuls les plus solides survivent', '2025-09-17 15:21:28', 'Validé', 1, 1),
(2, 'Neon Rift', 'Bienvenue dans Neon Rift, un affontement cyber-punk en FPS stratégique en solo ou en équipe, où les néons dessinent des univers parallèles et les réflexes s’aiguisent à la vitesse de la lumière. FPS futuristes, décors glitchés, modes VR et réalité déformée — c’est un tournoi où l’on ne joue pas, on transcende.', '2025-09-30 12:00:00', '2025-09-30 20:00:00', 80, 'Fracturez le réel, entrez dans la faille.', '2025-09-17 15:21:28', 'Validé', 2, 2),
(3, 'Pixel Panic', 'En solo ou en équipe, Pixel Panic est un tournoi pixélisé, rétro-maniaque où chaque explosion en 8 bits est une déclaration de guerre. Des jeux à l’ancienne, mais une intensité bien actuelle : compétitions arcade, speedruns et chaos coloré garantis. Pour ceux qui pensent qu’un bon pixel vaut mieux qu’un mauvais shader.', '2025-09-30 15:00:00', '2025-10-02 17:00:00', 40, 'Une avalanche de pixels. Une surdose d’action.', '2025-09-17 15:26:27', 'Validé', 9, 1),
(4, 'Noob Games', 'Un joyeux chaos en solo ou en équipe où même perdre peut faire gagner. Les Noob Games sont une ode à la découverte, à la maladresse glorieuse, à la première victoire hasardeuse. Ici, pas de méta, pas d’élitisme : tout le monde a une chance, surtout ceux qui n’en ont jamais eue. On y entre noob, on en ressort joueur.', '2025-10-05 14:00:00', '2025-10-07 20:00:00', 60, 'Parce qu’il faut bien commencer quelque part !', '2025-09-17 15:26:27', 'Validé', 4, 3),
(5, 'Eclipse Masters', 'En solo ou en équipe c\'est un événement mystique et tactique. L’Eclipse Masters réunit les meilleurs stratèges autour de MOBA et jeux d’équipe. Chaque match est une danse d’ombres, chaque mouvement une menace. Dans l’obscurité, la coordination est reine.', '2025-10-15 09:30:00', '2025-10-17 14:00:00', 60, 'Quand la lumière s’éteint, seuls les maîtres brillent.', '2025-09-17 15:29:26', 'Validé', 6, 1),
(6, 'Quantum Bash', 'Tournoi rapide et brutal où chaque manche ne dure que 5 minutes. En solo ou en équipe Quantum Bash promet un univers explosif où la gravité prend des vacances. FPS à gravité inversée, puzzles temporels, affrontements dans des arènes aux règles quantiques. Vous pensiez avoir tout vu ? Pas dans cet univers.', '2025-10-20 15:00:00', '2025-10-21 15:00:00', 40, 'Détruisez les lois de la physique… et vos adversaires.', '2025-09-17 15:29:26', 'Validé', 8, 2),
(7, 'Pixel Warzone', 'Ne vous laissez pas berner par les couleurs pastels : Pixel Warzone est une zone de guerre. Jeux de type battle royale en pixel art, ambiance cartoon… mais stratégies impitoyables. Pour les joueurs qui aiment quand ça saigne... en 16 couleurs.', '2025-10-25 18:00:00', '2025-10-28 15:00:00', 100, 'Les graphismes sont mignons, mais les combats font mal.', '2025-09-17 15:31:31', 'Validé', 7, 2),
(8, 'Next Level Cup', 'Tournoi hybride mêlant énigmes interactives, défis logiques et gameplay progressif. Chaque étape vous pousse à aller plus haut, plus vite, plus malin. La Next Level Cup ne récompense pas seulement les skills, mais aussi l’évolution. Soyez prêt à muter.', '2025-10-30 15:00:00', '2025-10-31 18:30:00', 50, 'Pas juste un jeu. Une ascension.', '2025-09-17 15:31:31', 'Validé', 3, 2),
(9, 'Dernier Combo', 'Jeux de briques en solo. Dans ce tournoi dédié aux fans de Tétris, tout se joue dans l’ultime combo. Ultra technique, ultra nerveux, Dernier Combo met en scène les meilleurs fighters dans des duels millimétrés. Ici, une erreur, c’est le chaos. Une réussite, c’est l’extase.', '2025-11-01 12:00:00', '2025-11-01 20:00:00', 90, 'Une seule brique peut tout changer.', '2025-09-17 15:33:22', 'Validé', 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tournament_history`
--

DROP TABLE IF EXISTS `tournament_history`;
CREATE TABLE `tournament_history` (
  `tournament_history_id` int UNSIGNED NOT NULL,
  `tournament_id` int UNSIGNED NOT NULL,
  `member_id` int UNSIGNED NOT NULL,
  `action_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` json DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournament_images`
--

DROP TABLE IF EXISTS `tournament_images`;
CREATE TABLE `tournament_images` (
  `tournament_image_id` int UNSIGNED NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tournament_images`
--

INSERT INTO `tournament_images` (`tournament_image_id`, `image_url`) VALUES
(1, 'uploads/tournaments/ironArena1-freepik-ia_resultat.jpg'),
(2, 'uploads/tournaments/neonRift1-bert-b-b6f7WaA-NZk-unsplash_resultat.jpg'),
(3, 'uploads/tournaments/NextLevelCup.jpg'),
(4, 'uploads/tournaments/noobGames1-freepik-ia_resultat.jpg'),
(5, 'uploads/tournaments/dernierCombo1-freepik-ia_resultat.jpg'),
(6, 'uploads/tournaments/eclipseMasters1-freepik-ia_resultat.jpg'),
(7, 'uploads/tournaments/pixelWarzone1-freepik-ia_resultat.jpg'),
(8, 'uploads/tournaments/quantumBash1-freepik-ia_resultat.jpg'),
(9, 'uploads/tournaments/pixelPanic4-freepik_resultat.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `uq_member_email` (`email`),
  ADD UNIQUE KEY `uq_member_pseudo` (`pseudo`),
  ADD KEY `IDX_70E4FA78C610EDD5` (`member_avatar_id`),
  ADD KEY `IDX_70E4FA7869F79538` (`member_role_id`);

--
-- Indexes for table `member_add_favorites_tournament`
--
ALTER TABLE `member_add_favorites_tournament`
  ADD PRIMARY KEY (`member_id`,`tournament_id`),
  ADD KEY `IDX_2D4A983E7597D3FE` (`member_id`),
  ADD KEY `IDX_2D4A983E33D1A3E7` (`tournament_id`);

--
-- Indexes for table `member_avatars`
--
ALTER TABLE `member_avatars`
  ADD PRIMARY KEY (`member_avatar_id`),
  ADD UNIQUE KEY `UNIQ_F3D32B477153098` (`code`);

--
-- Indexes for table `member_moderate_roles`
--
ALTER TABLE `member_moderate_roles`
  ADD PRIMARY KEY (`member_id`,`member_role_id`),
  ADD KEY `IDX_E8D162F27597D3FE` (`member_id`),
  ADD KEY `IDX_E8D162F269F79538` (`member_role_id`);

--
-- Indexes for table `member_participate_tournament`
--
ALTER TABLE `member_participate_tournament`
  ADD PRIMARY KEY (`member_id`,`tournament_id`),
  ADD KEY `IDX_89D6C1397597D3FE` (`member_id`),
  ADD KEY `IDX_89D6C13933D1A3E7` (`tournament_id`);

--
-- Indexes for table `member_register_tournament`
--
ALTER TABLE `member_register_tournament`
  ADD PRIMARY KEY (`member_id`,`tournament_id`),
  ADD KEY `IDX_7FC19D2F7597D3FE` (`member_id`),
  ADD KEY `IDX_7FC19D2F33D1A3E7` (`tournament_id`);

--
-- Indexes for table `member_roles`
--
ALTER TABLE `member_roles`
  ADD PRIMARY KEY (`member_role_id`),
  ADD UNIQUE KEY `uq_member_roles_code` (`code`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`tournament_id`),
  ADD KEY `IDX_BD5FB8D9DD1BC388` (`tournament_image_id`),
  ADD KEY `IDX_BD5FB8D97597D3FE` (`member_id`);

--
-- Indexes for table `tournament_history`
--
ALTER TABLE `tournament_history`
  ADD PRIMARY KEY (`tournament_history_id`),
  ADD KEY `IDX_4CB58B4133D1A3E7` (`tournament_id`),
  ADD KEY `IDX_4CB58B417597D3FE` (`member_id`);

--
-- Indexes for table `tournament_images`
--
ALTER TABLE `tournament_images`
  ADD PRIMARY KEY (`tournament_image_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `member_avatars`
--
ALTER TABLE `member_avatars`
  MODIFY `member_avatar_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `member_roles`
--
ALTER TABLE `member_roles`
  MODIFY `member_role_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournament_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tournament_history`
--
ALTER TABLE `tournament_history`
  MODIFY `tournament_history_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tournament_images`
--
ALTER TABLE `tournament_images`
  MODIFY `tournament_image_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `FK_70E4FA7869F79538` FOREIGN KEY (`member_role_id`) REFERENCES `member_roles` (`member_role_id`),
  ADD CONSTRAINT `FK_70E4FA78C610EDD5` FOREIGN KEY (`member_avatar_id`) REFERENCES `member_avatars` (`member_avatar_id`) ON DELETE SET NULL;

--
-- Constraints for table `member_add_favorites_tournament`
--
ALTER TABLE `member_add_favorites_tournament`
  ADD CONSTRAINT `FK_2D4A983E33D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2D4A983E7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_moderate_roles`
--
ALTER TABLE `member_moderate_roles`
  ADD CONSTRAINT `FK_E8D162F269F79538` FOREIGN KEY (`member_role_id`) REFERENCES `member_roles` (`member_role_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E8D162F27597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_participate_tournament`
--
ALTER TABLE `member_participate_tournament`
  ADD CONSTRAINT `FK_89D6C13933D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_89D6C1397597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_register_tournament`
--
ALTER TABLE `member_register_tournament`
  ADD CONSTRAINT `FK_7FC19D2F33D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7FC19D2F7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `FK_BD5FB8D97597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `FK_BD5FB8D9DD1BC388` FOREIGN KEY (`tournament_image_id`) REFERENCES `tournament_images` (`tournament_image_id`) ON DELETE SET NULL;

--
-- Constraints for table `tournament_history`
--
ALTER TABLE `tournament_history`
  ADD CONSTRAINT `FK_4CB58B4133D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4CB58B417597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
