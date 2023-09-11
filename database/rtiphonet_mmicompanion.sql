-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-rtiphonet.alwaysdata.net
-- Generation Time: Sep 11, 2023 at 12:40 AM
-- Server version: 10.6.14-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rtiphonet_mmicompanion`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id_task` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_finish` date NOT NULL,
  `checked` int(1) DEFAULT 0,
  `type` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `edu_group` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id_task`, `date_created`, `date_finish`, `checked`, `type`, `title`, `id_user`, `id_subject`, `edu_group`) VALUES
(44, '2023-09-03 15:55:49', '2023-09-03', 0, 'autre', 'gshfdsgsd dsfuhdsfhiyudsgf sdfuhdsfidfusg ffdgf', 5, 52, 'BUT2-TP3'),
(59, '2023-09-06 09:23:51', '2023-11-13', 0, 'devoir', 'CV Vidéo', 38, 6, 'BUT2-TP2'),
(60, '2023-09-06 09:24:54', '2023-09-13', 0, 'eval', '1984', 38, 6, 'BUT2-TP2');

-- --------------------------------------------------------

--
-- Table structure for table `informations`
--

CREATE TABLE `informations` (
  `id_infos` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `titre` varchar(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `user` varchar(100) NOT NULL,
  `group_info` varchar(10) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informations`
--

INSERT INTO `informations` (`id_infos`, `date`, `titre`, `content`, `user`, `group_info`, `id_user`) VALUES
(4, '2023-09-10 17:05:12', 'dsffdsdf', 'dfsfdsdfs', 'R. Tiphonet', 'all', 45),
(5, '2023-09-10 17:47:51', 'James Webb : le télescope du siècle !', 'Aujoyrd\'hui on va parler de quoi ? De feur et oui les amis !', 'Mon gros reuf le pied', 'all', 38);

-- --------------------------------------------------------

--
-- Table structure for table `sch_ressource`
--

CREATE TABLE `sch_ressource` (
  `id_ressource` int(11) NOT NULL,
  `code_ressource` varchar(20) NOT NULL,
  `name_subject` int(11) NOT NULL,
  `color_ressource` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sch_ressource`
--

INSERT INTO `sch_ressource` (`id_ressource`, `code_ressource`, `name_subject`, `color_ressource`) VALUES
(1, 'R1.01', 6, '#0097CF'),
(2, 'R1.02', 7, '#00CF61'),
(3, 'R1.03', 8, '#A2D100'),
(4, 'R1.04', 9, '#1989E1'),
(5, 'R1.05', 10, '#A261FF'),
(6, 'R1.06', 11, '#F3A0FF'),
(7, 'R1.07', 12, '#55A696'),
(8, 'R1.08', 13, '#557AA6'),
(9, 'R1.09', 14, '#A69A55'),
(10, 'R1.10', 15, '#A6556C'),
(11, 'R1.11', 16, '#97A655'),
(12, 'R1.12', 17, '#8555A6'),
(13, 'R1.13', 18, '#E7B9D4'),
(14, 'R1.14', 19, '#B9DEE7'),
(15, 'R1.15', 20, '#978A29'),
(16, 'R1.16', 21, '#E7B9D0'),
(17, 'R1.17', 22, '#5AA544'),
(18, 'SAE1.01', 23, '#D0CD0D'),
(19, 'SAE1.02', 24, '#D00D0D'),
(20, 'SAE1.03', 25, '#630DD0'),
(21, 'SAE1.04', 26, '#6FCE9C'),
(22, 'SAE1.05', 27, '#CEB66F'),
(23, 'SAE1.06', 28, '#CE6F87'),
(24, 'R2.01', 6, '#0097CF'),
(25, 'R2.02', 7, '#00CF61'),
(26, 'R2.03', 8, '#A2D100'),
(27, 'R2.04', 9, '#1989E1'),
(28, 'R2.05', 10, '#A261FF'),
(29, 'R2.06', 11, '#F3A0FF'),
(30, 'R2.07', 12, '#55A696'),
(31, 'R2.08', 13, '#557AA6'),
(32, 'R2.09', 14, '#A69A55'),
(33, 'R2.10', 15, '#A6556C'),
(34, 'R2.12', 16, '#97A655'),
(35, 'R2.13', 17, '#8555A6'),
(36, 'R2.14', 29, '#6555B6'),
(37, 'R2.15', 18, '#E7B9D4'),
(38, 'R2.16', 19, '#B9DEE7'),
(39, 'R2.17', 20, '#978A29'),
(40, 'R2.18', 21, '#E7B9D0'),
(41, 'R2.19', 22, '#5AA544'),
(42, 'SAE2.01', 30, '#55B6A7'),
(43, 'SAE2.02', 31, '#9755B6'),
(44, 'SAE2.03', 32, '#5597B6'),
(45, 'SAE2.04', 33, '#9181B0'),
(46, 'R2.11', 34, '#AEB081'),
(47, 'R3.01', 6, '#0097CF'),
(48, 'R3.02', 7, '#00CF61'),
(49, 'R3.03', 35, '#B09781'),
(50, 'R3.04', 9, '#1989E1'),
(51, 'R3.05', 10, '#A261FF'),
(52, 'R3.06', 36, '#B08181'),
(53, 'R3.07', 11, '#F3A0FF'),
(54, 'R3.08', 12, '#55A696'),
(55, 'R3.09', 37, '#64BDAD'),
(56, 'R3.10', 14, '#A69A55'),
(57, 'R3.11', 38, '#1F6987'),
(58, 'R3.12', 39, '#DE6424'),
(59, 'R3.devw.13', 47, '#624DC2'),
(60, 'R3.14', 41, '#6A64BD'),
(61, 'R3.15', 19, '#B9DEE7'),
(62, 'R3.16', 20, '#978A29'),
(63, 'R3.17', 21, '#E7B9D0'),
(64, 'R3.18', 22, '#5AA544'),
(66, 'SAE3.03-devw', 48, '#F37979'),
(67, 'R3.crea.13', 40, '#57727A'),
(68, 'SAE3.01-crea', 42, '#26871F'),
(70, 'SAE3.03-crea', 48, '#F37979'),
(71, 'SAE3.02', 43, '#CFA34A'),
(72, 'SAE3.03-strat', 52, '#1C8343'),
(73, 'SAE3.01-devw', 59, '#831C81'),
(74, 'SAE3.01-strat', 58, '#831C5F');

-- --------------------------------------------------------

--
-- Table structure for table `sch_subject`
--

CREATE TABLE `sch_subject` (
  `id_subject` int(11) NOT NULL,
  `name_subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sch_subject`
--

INSERT INTO `sch_subject` (`id_subject`, `name_subject`) VALUES
(6, 'Anglais'),
(7, 'Anglais renforcé'),
(8, 'Ergonomie et accessibilité'),
(9, 'Culture numérique'),
(10, 'Stratégie de communication'),
(11, 'Expression, communication et rhétorique'),
(12, 'Ecriture multimédia et narration'),
(13, 'Production graphique'),
(14, 'Culture artistique'),
(15, 'Production audio et vidéo'),
(16, 'Intégration'),
(17, 'Développement web'),
(18, 'Hébergement'),
(19, 'Représentation et traitement de l\'information'),
(20, 'Gestion de projet'),
(21, 'Economie, gestion et droit du numérique'),
(22, 'Projet personnel et professionnel'),
(23, 'Auditer une communication numérique'),
(24, 'Recommandation de communication numérique'),
(25, 'Produire les éléments d\'une communication visuelle'),
(26, 'Production audio et vidéo'),
(27, 'Produire un site web'),
(28, 'Gérer un projet de communication numérique'),
(29, 'Système d\'information'),
(30, '[SAE] Exploration des usages du numérique'),
(31, '[SAE] Concevoir un produit ou un service et sa communication'),
(32, '[SAE] Concevoir un site web avec une source de données'),
(33, '[SAE] Construire sa présence en ligne'),
(34, 'Gestion de contenus'),
(35, 'Design d\'expérience'),
(36, 'Référencement'),
(37, 'Création et design interactif (UI) '),
(38, 'Audiovisuel et Motion design'),
(39, 'Développement Front et intégration'),
(40, 'Gestion de contenus avancée'),
(41, 'Déploiement de services'),
(42, '[SAE] Intégrer des interfaces utilisateurs au sein d’un système\nd’information'),
(43, '[SAE] Produire des contenus pour une communication plurimédia'),
(44, '[SAE] Concevoir des visualisations de données pour le web et un support\nanimé\n'),
(45, '[SAE] Créer pour une campagne de communication visuelle'),
(46, '[SAE] Produire du contenu multimédia'),
(47, 'Développement Back'),
(48, '[SAE] Concevoir des visualisations de données pour le web et une\napplication interactive'),
(49, 'Développement front'),
(50, 'Développer pour le web'),
(51, '[SAE] Concevoir un dispositif interactif'),
(52, '[SAE] Concevoir des visualisations de données pour le web et dans\nun contexte d’une communication print et/ou sur les réseaux sociaux'),
(53, 'Stratégie de Com / Webmarketing'),
(54, 'Storytelling'),
(55, 'Gestion de contenus spécialisée'),
(56, '[SAE] Mettre en place une solution ecommerce et la stratégie\nassociée\n'),
(57, '[SAE] Développer une communication sur les médias sociaux'),
(58, '[SAE] Intégrer une expérience utilisateur au sein d’un système\nd’information\n'),
(59, '[SAE] Développer des parcours utilisateur au sein d\'un système\nd\'information\n');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `p256dh` varchar(255) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `edu_mail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `endpoint`, `p256dh`, `auth`, `created_at`, `edu_mail`) VALUES
(4, 'https://fcm.googleapis.com/fcm/send/fp4ZCtsQHDI:APA91bGRMKg5-_5A6an_COJgAmwAU4bGy1CLUgapUuv1VrEzCly4-aP_0FjHbvwc1e589WeNlZip_kb1djDsWQIuzw8aoqQnk4MnFYO-AnhXxZ7ARfYxm-UR6j04Qj3VBYe8Xo0oDE-_', 'BNWRjCk2OfwhcBDDnr5XW5af5ibjslMpfZ9O-2-NMVAcuCD-tBfHrBvH4UbXRtpQ8x8yzmxV7bZgMxvjs6nax7g', 'a5Kv8C0xOuNPi0-VeIuc6Q', '2023-06-20 10:39:39', 'admin@admin.fr'),
(6, 'https://fcm.googleapis.com/fcm/send/cTDxlJxAKKQ:APA91bGHUivYgBEJJO8qMp1EbxJFWg2efSWdVbwVtbtUcR7Vn2T-ubUknczv-F7WTpD3rj5reWA1FX49FxsVLJg9mWVoekgakLjP80S2Dsbh6poosJjczh10L2KYmnfmnUFOyJDr9I_-', 'BJbpVH3XOBfyVYlISxjseCCIclSd8O5M10q0DB-QKSO1Wq24He-q78CIXKiqGEpfUtbZ2EJGrNfrpwrnjBqmTrI', 'B-Gfu0f2RAsRCdFCF7z_fQ', '2023-07-02 19:15:55', 'admin@admin.fr'),
(7, 'https://wns2-par02p.notify.windows.com/w/?token=BQYAAAANuMyPxakKXxLLGhacktbIxl%2bPVcqsYN2j%2bCowtYy9WU5jvbzXiR6oBffvKMWMAV%2bJbMgYzbxAx83FMhjuNXV2ZJNo%2f3uHMoJLY4TFA4vhpagLP7hfFt%2fRV%2fXMJ1NxqgDTxog6S9QpVhoFoEDir74lQY%2fF5uzoroStO%2fKyJIzzNw8K6yrnpINC9Zd', 'BCzmcZjP2IRN5jWBASVEwfu5aZGJ-AbO15NWZcCSIVXdHRkiJKk_jcd7zAwK4MPVKHCrPD5Zg4YtpNfzqeWWWhE', '8EMDpNsiu3ABnpyncsTTRw', '2023-07-03 11:08:23', 'admin@admin.fr'),
(8, 'https://wns2-par02p.notify.windows.com/w/?token=BQYAAAANuMyPxakKXxLLGhacktbIxl%2bPVcqsYN2j%2bCowtYy9WU5jvbzXiR6oBffvKMWMAV%2bJbMgYzbxAx83FMhjuNXV2ZJNo%2f3uHMoJLY4TFA4vhpagLP7hfFt%2fRV%2fXMJ1NxqgDTxog6S9QpVhoFoEDir74lQY%2fF5uzoroStO%2fKyJIzzNw8K6yrnpINC9Zd', 'BCzmcZjP2IRN5jWBASVEwfu5aZGJ-AbO15NWZcCSIVXdHRkiJKk_jcd7zAwK4MPVKHCrPD5Zg4YtpNfzqeWWWhE', '8EMDpNsiu3ABnpyncsTTRw', '2023-07-03 11:35:43', 'admin@admin.fr');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `edu_mail` varchar(255) NOT NULL,
  `edu_group` varchar(255) NOT NULL,
  `role` text NOT NULL DEFAULT 'eleve',
  `pp_link` varchar(255) NOT NULL DEFAULT './../assets/img/profil-1.svg',
  `score` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `tuto_agenda` tinyint(1) NOT NULL,
  `verification_code_mail` varchar(255) NOT NULL,
  `verification_code_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `pname`, `name`, `password`, `edu_mail`, `edu_group`, `role`, `pp_link`, `score`, `active`, `tuto_agenda`, `verification_code_mail`, `verification_code_pass`) VALUES
(5, 'admin1', '1565', '$2y$10$qTcjnhui2uVfdfCwVdztqOiUP1I0qu9D8Ju7SqWlEsEyLhPDdqT2S', 'admin@admin.fr', 'BUT2-TP3', 'eleve', 'https://ui-avatars.com/api/?background=random&name=Raphael', 1260, 1, 0, '6548594895645648947894', 'a6cbb4a2d24cc5ac8c965a88158f24d2'),
(38, 'Arnaud', 'Graciet', '$2y$10$9Uq83oODpJLfet5bOL/1.ukBz4t8Jasx2BJkqOpOLekXisg5jVyxi', 'arnaud.graciet@etu.univ-poitiers.fr', 'BUT2-TP2', 'chef', './../uploads/IMG_1046 2.jpg', 2090, 1, 1, '', ''),
(42, 'Clara ', 'Cormier', '$2y$10$Ly32WOvFSmTYM/g087pIjeqSB0DnCvMA/EhdB0fOgELI8xhRPFkY6', 'clara.cormier@etu.univ-poitiers.fr', 'BUT2-TP2', 'eleve', 'https://ui-avatars.com/api/?background=56b8d6&color=004a5a&bold=true&name=Clara +Cormier&rounded=true&size=128', 0, 1, 0, '', ''),
(43, 'prof', 'prof', '$2y$10$yJ18gO5awI8N/kxs56zWceTWBfNBPnChza8uM3r0y/9CxJCk48dhq', 'prof@prof.com', 'prof', 'prof', './../assets/img/profil-1.svg', 0, 1, 0, '', ''),
(45, 'Raphaël', 'Tiphonet', '$2y$10$s6U0sja52adUwbAaxmsVvuNtrRRd52NKo8hXjNpAeSk6qVaJt/ynq', 'raphael.tiphonet@etu.univ-poitiers.fr', 'BUT2-TP3', 'eleve, BDE', 'https://ui-avatars.com/api/?background=56b8d6&color=004a5a&bold=true&name=Raphaël+Tiphonet&rounded=true&size=128', 0, 1, 1, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `fk3` (`id_user`),
  ADD KEY `fk4` (`id_subject`);

--
-- Indexes for table `informations`
--
ALTER TABLE `informations`
  ADD PRIMARY KEY (`id_infos`),
  ADD KEY `fk_name25` (`id_user`);

--
-- Indexes for table `sch_ressource`
--
ALTER TABLE `sch_ressource`
  ADD PRIMARY KEY (`id_ressource`),
  ADD KEY `fk_name` (`name_subject`);

--
-- Indexes for table `sch_subject`
--
ALTER TABLE `sch_subject`
  ADD PRIMARY KEY (`id_subject`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk2` (`edu_mail`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `mail_edu` (`edu_mail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id_task` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `informations`
--
ALTER TABLE `informations`
  MODIFY `id_infos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sch_ressource`
--
ALTER TABLE `sch_ressource`
  MODIFY `id_ressource` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `sch_subject`
--
ALTER TABLE `sch_subject`
  MODIFY `id_subject` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `fk3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk4` FOREIGN KEY (`id_subject`) REFERENCES `sch_subject` (`id_subject`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `informations`
--
ALTER TABLE `informations`
  ADD CONSTRAINT `fk_name25` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sch_ressource`
--
ALTER TABLE `sch_ressource`
  ADD CONSTRAINT `fk_name` FOREIGN KEY (`name_subject`) REFERENCES `sch_subject` (`id_subject`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`edu_mail`) REFERENCES `users` (`edu_mail`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
