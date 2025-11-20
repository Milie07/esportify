-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: esportify
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20251112155204','2025-11-20 20:24:33',2751),('DoctrineMigrations\\Version20251112180314','2025-11-20 20:24:36',123);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member` (
  `member_id` int unsigned NOT NULL AUTO_INCREMENT,
  `member_avatar_id` int unsigned DEFAULT NULL,
  `member_role_id` int unsigned NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_score` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `uq_member_pseudo` (`pseudo`),
  UNIQUE KEY `uq_member_email` (`email`),
  KEY `IDX_70E4FA78C610EDD5` (`member_avatar_id`),
  KEY `IDX_70E4FA7869F79538` (`member_role_id`),
  CONSTRAINT `FK_70E4FA7869F79538` FOREIGN KEY (`member_role_id`) REFERENCES `member_roles` (`member_role_id`),
  CONSTRAINT `FK_70E4FA78C610EDD5` FOREIGN KEY (`member_avatar_id`) REFERENCES `member_avatars` (`member_avatar_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,6,1,'Elodie','Marchal','ElodieAdmin','admin.elodie@esportify.com','$2y$13$aYu9tn7y6L7mMxBnuluCu.1p8YyNaBtIY1cJWmhQ5yDL/Cx3bv4.e',90),(2,1,1,'Raphaël','Malassis','RaphAdmin','admin.raph@esportify.com','$2y$13$IcECFZUY2L2T4VBqziE.5ODOuL79a4HHrDfaU7aXHR4xkRZexUjai',120),(3,4,2,'Hugo','Perret','HugoOrga','orga.hugo@esportify.com','$2y$13$eVD85eeKoftF/X/x0XbdyuC6Qkfz1YGFZ89YGLy/eMhu5p00p8L6u',80),(4,2,2,'Alex','Durand','AlexOrga','orga.alex@esportify.com','$2y$13$lldUwug90fszg7De0z2ZX..fYqEAGpidV1FvcdmJ3QqrfiVUi2Oxy',90),(5,8,3,'Tom','Garcia','TomPlayer','play.tom@esportify.com','$2y$13$1iSHvKGu18BFQURsYIBgCenliDnuaJt2HN8TDC7CBkT2nu8FFYh2m',30),(6,1,3,'Ines','Garcia','InesPlayer','play.ines@esportify.com','$2y$13$IwO1Wy6NZn.rXsviVhj5XeQL595gljoia6wc2QGWDvE6lMWY05GRi',10),(7,5,3,'Nicolas','Malassis','NicoPlayer','play.nico@esportify.com','$2y$13$r4dWJmG9QeP9cvs0Rcfftuu6npv8HFAAmcJUbI1O8Oa8hMAgjP.F6',100),(8,5,3,'Emma','Corrompt','EmmaPlayer','play.emma@esportify.com','$2y$13$AsjtV2bItZK7aWqiibASWOiyl6ERoOut4F.sGWpIjFfDfl6vxgu62',40),(9,7,3,'Nais','Malassis','NaisPlayer','play.nais@esportify.com','$2y$13$F0D/.L.Uq4BQRHMwYWs1FOvlqhHwkS9FU/uYG.g4VGOf0dgF59C26',30);
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_add_favorites_tournament`
--

DROP TABLE IF EXISTS `member_add_favorites_tournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_add_favorites_tournament` (
  `member_id` int unsigned NOT NULL,
  `tournament_id` int unsigned NOT NULL,
  PRIMARY KEY (`member_id`,`tournament_id`),
  KEY `IDX_2D4A983E7597D3FE` (`member_id`),
  KEY `IDX_2D4A983E33D1A3E7` (`tournament_id`),
  CONSTRAINT `FK_2D4A983E33D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2D4A983E7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_add_favorites_tournament`
--

LOCK TABLES `member_add_favorites_tournament` WRITE;
/*!40000 ALTER TABLE `member_add_favorites_tournament` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_add_favorites_tournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_avatars`
--

DROP TABLE IF EXISTS `member_avatars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_avatars` (
  `member_avatar_id` int unsigned NOT NULL AUTO_INCREMENT,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` int NOT NULL,
  PRIMARY KEY (`member_avatar_id`),
  UNIQUE KEY `UNIQ_F3D32B477153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_avatars`
--

LOCK TABLES `member_avatars` WRITE;
/*!40000 ALTER TABLE `member_avatars` DISABLE KEYS */;
INSERT INTO `member_avatars` VALUES (1,'uploads/avatars/avatar1.jpg',1),(2,'uploads/avatars/avatar2.jpg',2),(3,'uploads/avatars/avatar3.jpg',3),(4,'uploads/avatars/avatar4.jpg',4),(5,'uploads/avatars/avatar5.jpg',5),(6,'uploads/avatars/avatar6.jpg',6),(7,'uploads/avatars/avatar7.jpg',7),(8,'uploads/avatars/avatar8.jpg',8);
/*!40000 ALTER TABLE `member_avatars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_moderate_roles`
--

DROP TABLE IF EXISTS `member_moderate_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_moderate_roles` (
  `member_id` int unsigned NOT NULL,
  `member_role_id` int unsigned NOT NULL,
  `member_label_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Actif',
  `assigned_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`member_id`,`member_role_id`),
  KEY `IDX_E8D162F27597D3FE` (`member_id`),
  KEY `IDX_E8D162F269F79538` (`member_role_id`),
  CONSTRAINT `FK_E8D162F269F79538` FOREIGN KEY (`member_role_id`) REFERENCES `member_roles` (`member_role_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_E8D162F27597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_moderate_roles`
--

LOCK TABLES `member_moderate_roles` WRITE;
/*!40000 ALTER TABLE `member_moderate_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_moderate_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_participate_tournament`
--

DROP TABLE IF EXISTS `member_participate_tournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_participate_tournament` (
  `member_id` int unsigned NOT NULL,
  `tournament_id` int unsigned NOT NULL,
  `tournament_score` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`,`tournament_id`),
  KEY `IDX_89D6C1397597D3FE` (`member_id`),
  KEY `IDX_89D6C13933D1A3E7` (`tournament_id`),
  CONSTRAINT `FK_89D6C13933D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_89D6C1397597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_participate_tournament`
--

LOCK TABLES `member_participate_tournament` WRITE;
/*!40000 ALTER TABLE `member_participate_tournament` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_participate_tournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_register_tournament`
--

DROP TABLE IF EXISTS `member_register_tournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_register_tournament` (
  `member_id` int unsigned NOT NULL,
  `tournament_id` int unsigned NOT NULL,
  `date_register` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`member_id`,`tournament_id`),
  KEY `IDX_7FC19D2F7597D3FE` (`member_id`),
  KEY `IDX_7FC19D2F33D1A3E7` (`tournament_id`),
  CONSTRAINT `FK_7FC19D2F33D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7FC19D2F7597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_register_tournament`
--

LOCK TABLES `member_register_tournament` WRITE;
/*!40000 ALTER TABLE `member_register_tournament` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_register_tournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_roles`
--

DROP TABLE IF EXISTS `member_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member_roles` (
  `member_role_id` int unsigned NOT NULL AUTO_INCREMENT,
  `member_role_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Player',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`member_role_id`),
  UNIQUE KEY `uq_member_roles_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_roles`
--

LOCK TABLES `member_roles` WRITE;
/*!40000 ALTER TABLE `member_roles` DISABLE KEYS */;
INSERT INTO `member_roles` VALUES (1,'Admin','2025-11-20 20:24:48',NULL,'ROLE_ADMIN'),(2,'Organizer','2025-11-20 20:24:48',NULL,'ROLE_ORGANIZER'),(3,'Player','2025-11-20 20:24:48',NULL,'ROLE_PLAYER');
/*!40000 ALTER TABLE `member_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament` (
  `tournament_id` int unsigned NOT NULL AUTO_INCREMENT,
  `tournament_image_id` int unsigned DEFAULT NULL,
  `member_id` int unsigned NOT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `end_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `capacity_gauge` int NOT NULL DEFAULT '0',
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `current_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En Attente',
  PRIMARY KEY (`tournament_id`),
  KEY `IDX_BD5FB8D9DD1BC388` (`tournament_image_id`),
  KEY `IDX_BD5FB8D97597D3FE` (`member_id`),
  CONSTRAINT `FK_BD5FB8D97597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  CONSTRAINT `FK_BD5FB8D9DD1BC388` FOREIGN KEY (`tournament_image_id`) REFERENCES `tournament_images` (`tournament_image_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament`
--

LOCK TABLES `tournament` WRITE;
/*!40000 ALTER TABLE `tournament` DISABLE KEYS */;
INSERT INTO `tournament` VALUES (1,3,3,'Iron Arena','Chaque Round est un pas de plus vers la gloire...Ou le respawn. Un contre Un où l’endurance est une vertu, la stratégie une arme, et la sueur virtuelle, une offrande. Les participants y enchaînent des rounds épiques dans des jeux de combat et de survie, jusqu’à ce que le métal plie. Préparez vos nerfs, car l’acier n’a pas d’âme.','2025-11-12 10:00:00','2025-11-14 10:00:00',70,'Le Tournois où seuls les plus solides survivent','2025-11-01 10:00:00','En Cours'),(2,4,4,'Neon Rift','Bienvenue dans Neon Rift, un affontement cyber-punk en FPS stratégique en solo ou en équipe, où les néons dessinent des univers parallèles et les réflexes s’aiguisent à la vitesse de la lumière. FPS futuristes, décors glitchés, modes VR et réalité déformée — c’est un tournoi où l’on ne joue pas, on transcende.','2025-11-25 10:00:00','2025-11-26 10:00:00',80,'Fracturez le réel, entrez dans la faille.','2025-11-10 10:00:00','Validé'),(3,7,4,'Pixel Panic','En solo ou en équipe, Pixel Panic est un tournoi pixélisé, rétro-maniaque où chaque explosion en 8 bits est une déclaration de guerre. Des jeux à l’ancienne, mais une intensité bien actuelle : compétitions arcade, speedruns et chaos coloré garantis. Pour ceux qui pensent qu’un bon pixel vaut mieux qu’un mauvais shader.','2025-11-30 10:00:00','2025-12-01 10:00:00',40,'Une avalanche de pixels. Une surdose d’action.','2025-11-20 10:00:00','Validé'),(4,6,1,'Noob Games','Un joyeux chaos en solo ou en équipe où même perdre peut faire gagner. Les Noob Games sont une ode à la découverte, à la maladresse glorieuse, à la première victoire hasardeuse. Ici, pas de méta, pas d’élitisme : tout le monde a une chance, surtout ceux qui n’en ont jamais eue. On y entre noob, on en ressort joueur.','2025-12-10 10:00:00','2025-12-15 10:00:00',60,'Parce qu’il faut bien commencer quelque part !','2025-12-01 10:00:00','Validé'),(5,2,3,'Eclipse Masters','En solo ou en équipe c\'est un événement mystique et tactique. L’Eclipse Masters réunit les meilleurs stratèges autour de MOBA et jeux d’équipe. Chaque match est une danse d’ombres, chaque mouvement une menace. Dans l’obscurité, la coordination est reine.','2025-12-16 10:00:00','2025-12-17 10:00:00',60,'Quand la lumière s’éteint, seuls les maîtres brillent.','2025-12-01 10:00:00','Validé'),(6,9,1,'Quantum Bash','Tournoi rapide et brutal où chaque manche ne dure que 5 minutes. En solo ou en équipe Quantum Bash promet un univers explosif où la gravité prend des vacances. FPS à gravité inversée, puzzles temporels, affrontements dans des arènes aux règles quantiques. Vous pensiez avoir tout vu ? Pas dans cet univers.','2025-12-18 10:00:00','2025-12-18 20:00:00',40,'Détruisez les lois de la physique… et vos adversaires.','2025-12-10 10:00:00','Validé'),(7,8,4,'Pixel Warzone','Ne vous laissez pas berner par les couleurs pastels : Pixel Warzone est une zone de guerre. Jeux de type battle royale en pixel art, ambiance cartoon… mais stratégies impitoyables. Pour les joueurs qui aiment quand ça saigne... en 16 couleurs.','2025-12-27 10:00:00','2025-12-28 20:00:00',100,'Les graphismes sont mignons, mais les combats font mal.','2025-12-15 10:00:00','Validé'),(8,5,4,'Next Level Cup','Tournoi hybride mêlant énigmes interactives, défis logiques et gameplay progressif. Chaque étape vous pousse à aller plus haut, plus vite, plus malin. La Next Level Cup ne récompense pas seulement les skills, mais aussi l’évolution. Soyez prêt à muter.','2026-01-04 10:00:00','2026-01-04 20:00:00',50,'Pas juste un jeu. Une ascension.','2025-12-25 10:00:00','Validé'),(9,1,3,'Dernier Combo','Jeux de briques en solo. Dans ce tournoi dédié aux fans de Tétris, tout se joue dans l’ultime combo. Ultra technique, ultra nerveux, Dernier Combo met en scène les meilleurs fighters dans des duels millimétrés. Ici, une erreur, c’est le chaos. Une réussite, c’est l’extase.','2026-01-10 12:00:00','2026-01-11 18:00:00',90,'Une seule brique peut tout changer.','2025-01-02 10:00:00','Validé');
/*!40000 ALTER TABLE `tournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_history`
--

DROP TABLE IF EXISTS `tournament_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_history` (
  `tournament_history_id` int unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` int unsigned NOT NULL,
  `member_id` int unsigned NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `action_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` json DEFAULT NULL,
  `to_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tournament_history_id`),
  KEY `IDX_4CB58B4133D1A3E7` (`tournament_id`),
  KEY `IDX_4CB58B417597D3FE` (`member_id`),
  CONSTRAINT `FK_4CB58B4133D1A3E7` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4CB58B417597D3FE` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_history`
--

LOCK TABLES `tournament_history` WRITE;
/*!40000 ALTER TABLE `tournament_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournament_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournament_images`
--

DROP TABLE IF EXISTS `tournament_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournament_images` (
  `tournament_image_id` int unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` int NOT NULL,
  PRIMARY KEY (`tournament_image_id`),
  UNIQUE KEY `UNIQ_84B5403A77153098` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournament_images`
--

LOCK TABLES `tournament_images` WRITE;
/*!40000 ALTER TABLE `tournament_images` DISABLE KEYS */;
INSERT INTO `tournament_images` VALUES (1,'uploads\\tournaments\\dernierCombo1-freepik-ia_resultat.jpg',1),(2,'uploads\\tournaments\\eclipseMasters1-freepik-ia_resultat.jpg',2),(3,'uploads\\tournaments\\ironArena1-freepik-ia_resultat.jpg',3),(4,'uploads\\tournaments\\neonRift1-bert-b-b6f7WaA-NZk-unsplash_resultat.jpg',4),(5,'uploads\\tournaments\\NextLevelCup.jpg',5),(6,'uploads\\tournaments\\noobGames1-freepik-ia_resultat.jpg',6),(7,'uploads\\tournaments\\pixelPanic4-freepik_resultat.jpg',7),(8,'uploads\\tournaments\\pixelWarzone1-freepik-ia_resultat.jpg',8),(9,'uploads\\tournaments\\quantumBash1-freepik-ia_resultat.jpg',9);
/*!40000 ALTER TABLE `tournament_images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-20 19:31:10
