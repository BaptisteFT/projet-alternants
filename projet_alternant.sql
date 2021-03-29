-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 29 mars 2021 à 22:00
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_alternant`
--

-- --------------------------------------------------------

--
-- Structure de la table `api_token`
--

DROP TABLE IF EXISTS `api_token`;
CREATE TABLE IF NOT EXISTS `api_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7BA2F5EBA76ED395` (`user_id`),
  KEY `IDX_7BA2F5EB61220EA6` (`creator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contract`
--

DROP TABLE IF EXISTS `contract`;
CREATE TABLE IF NOT EXISTS `contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `social_reason` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret_number` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_street` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_city` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_email` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_civility` tinyint(1) NOT NULL,
  `representative_first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_role` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `representative_email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_social_reason` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_location_street` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_location_city` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_phone_number` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `worker_role` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_start_date` datetime NOT NULL,
  `contract_end_date` datetime NOT NULL,
  `tutor_civility` tinyint(1) NOT NULL,
  `tutor_first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tutor_last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tutor_role` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tutor_phone_number` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tutor_email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E98F2859A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contract_base64`
--

DROP TABLE IF EXISTS `contract_base64`;
CREATE TABLE IF NOT EXISTS `contract_base64` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL,
  `base64` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F3D2D73B2576E0FD` (`contract_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_info`
--

DROP TABLE IF EXISTS `job_info`;
CREATE TABLE IF NOT EXISTS `job_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goal` varchar(3000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `technology` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4C454716CB944F1A` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `is_archived` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notification_user`
--

DROP TABLE IF EXISTS `notification_user`;
CREATE TABLE IF NOT EXISTS `notification_user` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`notification_id`,`user_id`),
  KEY `IDX_35AF9D73EF1A9D84` (`notification_id`),
  KEY `IDX_35AF9D73A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `content_two` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_three` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_four` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0FCB944F1A` (`student_id`),
  KEY `IDX_6970EB0FF675F31B` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `student_token`
--

DROP TABLE IF EXISTS `student_token`;
CREATE TABLE IF NOT EXISTS `student_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D700D8A6CB944F1A` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `status` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civility` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D64941807E1D` (`teacher_id`),
  KEY `IDX_8D93D649979B1AD6` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `teacher_id`, `company_id`, `email`, `first_name`, `last_name`, `roles`, `password`, `is_active`, `status`, `civility`) VALUES
(1, NULL, NULL, 'admin@usmb', 'admin', 'usmb', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$Q3Y0MlNnMnNQakI4T3cwcw$YAz3p3OK5bY7bkqAWO6dmaCYwzYqaPAhVndEvxLafpY', 1, 'OTHER', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `work_contract`
--

DROP TABLE IF EXISTS `work_contract`;
CREATE TABLE IF NOT EXISTS `work_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `base64` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3C3AA205CB944F1A` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `api_token`
--
ALTER TABLE `api_token`
  ADD CONSTRAINT `FK_7BA2F5EB61220EA6` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_7BA2F5EBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `FK_E98F2859A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `contract_base64`
--
ALTER TABLE `contract_base64`
  ADD CONSTRAINT `FK_F3D2D73B2576E0FD` FOREIGN KEY (`contract_id`) REFERENCES `contract` (`id`);

--
-- Contraintes pour la table `job_info`
--
ALTER TABLE `job_info`
  ADD CONSTRAINT `FK_4C454716CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `notification_user`
--
ALTER TABLE `notification_user`
  ADD CONSTRAINT `FK_35AF9D73A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_35AF9D73EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_6970EB0FCB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6970EB0FF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `student_token`
--
ALTER TABLE `student_token`
  ADD CONSTRAINT `FK_D700D8A6CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64941807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8D93D649979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `work_contract`
--
ALTER TABLE `work_contract`
  ADD CONSTRAINT `FK_3C3AA205CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
