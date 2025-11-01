-- ========================================
-- SCHÉMA DE BASE DE DONNÉES - HOW I WIN MY HOME
-- ========================================
--
-- Ce fichier SQL initialise la base de données
-- complète de l'application How I Win My Home.
--
-- MISE À JOUR V2.1.0 (2025-08-16) :
-- - Harmonisation des noms de tables avec les modèles PHP
-- - Renommage : motivation_letters → letters
-- - Renommage : qcm_attempts → qcm_results
-- - Mise à jour des noms de colonnes dans tickets
-- - Ajout de nouvelles colonnes pour la cohérence
--
-- FONCTIONNALITÉS PRINCIPALES :
-- - Création de toutes les tables nécessaires
-- - Structure optimisée pour l'architecture MVC
-- - Relations et contraintes d'intégrité
-- - Index pour les performances des requêtes
-- - Données d'initialisation et de test
--
-- ARCHITECTURE DE LA BASE DE DONNÉES :
-- 1. Tables utilisateurs et profils
-- 2. Tables annonces immobilières et images
-- 3. Tables système de concours (tickets, QCM)
-- 4. Tables de gestion (jury, gagnants, feedback)
-- 5. Tables de suivi (notifications, audit, paramètres)
--
-- TABLES PRINCIPALES :
-- - users : Gestion des utilisateurs et authentification
-- - user_profiles : Profils étendus des utilisateurs
-- - listings : Annonces immobilières
-- - listing_images : Images des biens immobiliers
-- - tickets : Tickets de participation aux concours
-- - qcm_questions : Questions des questionnaires
-- - qcm_results : Résultats des questionnaires
-- - letters : Lettres de motivation
-- - jury_members : Membres du jury
-- - winners : Gagnants des concours
-- - feedbacks : Retours d'expérience
-- - notifications : Notifications système
-- - system_settings : Paramètres de l'application
-- - audit_logs : Journal d'audit des actions
--
-- SÉCURITÉ ET PERFORMANCES :
-- - Contraintes d'intégrité référentielle
-- - Index sur les colonnes fréquemment utilisées
-- - Chiffrement des mots de passe (bcrypt)
-- - Validation des données avec ENUM
-- - Horodatage automatique des modifications
--
-- AUTEUR : How I Win My Home Team
-- VERSION : 2.1.0
-- DATE : 2025-08-16
-- ========================================

-- ========================================
-- CONFIGURATION INITIALE DE LA BASE
-- ========================================
--
-- Configuration des paramètres MySQL
-- pour la compatibilité et les performances
--

-- Désactivation du mode auto-increment sur zéro
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- Début de la transaction pour l'intégrité
START TRANSACTION;

-- Configuration du fuseau horaire
SET time_zone = "+00:00";

-- Configuration de l'encodage des caractères
SET NAMES utf8mb4;

-- ========================================
-- TABLE USERS - UTILISATEURS DU SYSTÈME
-- ========================================
--
-- Table principale pour la gestion des utilisateurs
-- avec authentification et rôles
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `email` varchar(255) NOT NULL,                           -- Adresse email unique
  `password` varchar(255) NOT NULL,                        -- Mot de passe hashé (bcrypt)
  `role` enum('user','seller','admin','jury') NOT NULL DEFAULT 'user', -- Rôle de l'utilisateur
  `first_name` varchar(100) NOT NULL,                      -- Prénom de l'utilisateur
  `last_name` varchar(100) NOT NULL,                       -- Nom de famille de l'utilisateur
  `phone` varchar(20) DEFAULT NULL,                        -- Numéro de téléphone
  `address` text DEFAULT NULL,                             -- Adresse complète
  `city` varchar(100) DEFAULT NULL,                        -- Ville de résidence
  `postal_code` varchar(10) DEFAULT NULL,                  -- Code postal
  `country` varchar(100) DEFAULT 'France',                 -- Pays (France par défaut)
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,             -- Statut de vérification email
  `email_verified_at` timestamp NULL DEFAULT NULL,          -- Date de vérification email
  `last_login_at` timestamp NULL DEFAULT NULL,              -- Dernière connexion
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active', -- Statut du compte
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `email` (`email`),                            -- Contrainte d'unicité sur l'email
  KEY `role` (`role`),                                     -- Index sur le rôle
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `created_at` (`created_at`)                          -- Index sur la date de création
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE USER_PROFILES - PROFILS ÉTENDUS
-- ========================================
--
-- Table pour les informations détaillées
-- des profils utilisateurs
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers la table users
  `bio` text DEFAULT NULL,                                 -- Biographie de l'utilisateur
  `avatar` varchar(255) DEFAULT NULL,                      -- Chemin vers l'avatar
  `date_of_birth` date DEFAULT NULL,                       -- Date de naissance
  `gender` enum('male','female','other','prefer_not_to_say') DEFAULT NULL, -- Genre
  `occupation` varchar(255) DEFAULT NULL,                  -- Profession
  `company` varchar(255) DEFAULT NULL,                     -- Entreprise
  `website` varchar(255) DEFAULT NULL,                     -- Site web personnel
  `social_links` json DEFAULT NULL,                        -- Liens vers réseaux sociaux
  `preferences` json DEFAULT NULL,                         -- Préférences utilisateur
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `user_id` (`user_id`),                        -- Un profil par utilisateur
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE -- Contrainte référentielle
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE LISTINGS - ANNONCES IMMOBILIÈRES
-- ========================================
--
-- Table principale pour les annonces
-- immobilières et leurs caractéristiques
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers le vendeur
  `title` varchar(255) NOT NULL,                           -- Titre de l'annonce
  `description` text NOT NULL,                             -- Description complète
  `short_description` varchar(500) DEFAULT NULL,            -- Description courte
  `image` varchar(255) DEFAULT NULL,                       -- Image principale de l'annonce
  `price` decimal(12,2) NOT NULL,                          -- Prix du bien immobilier
  `prix_total` decimal(12,2) NOT NULL,                     -- Prix total du bien
  `ticket_price` enum('5','10','15','20') NOT NULL,        -- Prix d'un ticket de participation
  `prix_ticket` enum('5','10','15','20') NOT NULL,         -- Prix d'un ticket (alias)
  `tickets_needed` int(11) NOT NULL,                       -- Nombre de tickets nécessaires
  `tickets_sold` int(11) NOT NULL DEFAULT 0,               -- Nombre de tickets vendus
  `property_type` enum('apartment','house','villa','studio','loft','other') NOT NULL DEFAULT 'apartment', -- Type de bien
  `property_size` int(11) NOT NULL COMMENT 'Taille en m²', -- Surface du bien
  `rooms` int(11) NOT NULL,                            -- Nombre total de pièces
  `bedrooms` int(11) NOT NULL,                         -- Nombre de chambres
  `bathrooms` int(11) DEFAULT NULL,                        -- Nombre de salles de bain
  `floor` int(11) DEFAULT NULL,                            -- Étage du bien
  `elevator` tinyint(1) DEFAULT NULL,                      -- Présence d'un ascenseur
  `parking` tinyint(1) DEFAULT NULL,                       -- Présence d'un parking
  `garden` tinyint(1) DEFAULT NULL,                        -- Présence d'un jardin
  `balcony` tinyint(1) DEFAULT NULL,                       -- Présence d'un balcon
  `terrace` tinyint(1) DEFAULT NULL,                       -- Présence d'une terrasse
  `address` text NOT NULL,                                 -- Adresse complète du bien
  `city` varchar(100) NOT NULL,                            -- Ville du bien
  `postal_code` varchar(10) NOT NULL,                      -- Code postal du bien
  `country` varchar(100) NOT NULL DEFAULT 'France',        -- Pays du bien
  `latitude` decimal(10,8) DEFAULT NULL,                   -- Latitude géographique
  `longitude` decimal(11,8) DEFAULT NULL,                  -- Longitude géographique
  `start_date` date NOT NULL,                              -- Date de début du concours
  `end_date` date NOT NULL,                                -- Date de fin du concours
  `status` enum('draft','pending','active','paused','ended','cancelled') NOT NULL DEFAULT 'pending', -- Statut de l'annonce
  `featured` tinyint(1) NOT NULL DEFAULT 0,                -- Mise en avant de l'annonce
  `views_count` int(11) NOT NULL DEFAULT 0,                -- Nombre de vues
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `user_id` (`user_id`),                               -- Index sur le vendeur
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `property_type` (`property_type`),
  KEY `city` (`city`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `featured` (`featured`),
  KEY `created_at` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE LISTING_IMAGES - IMAGES DES ANNONCES
-- ========================================
--
-- Table pour les images associées aux annonces
--

CREATE TABLE `listing_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `listing_id` int(11) NOT NULL,                              -- Référence vers la table listings
  `filename` varchar(255) NOT NULL,                           -- Nom du fichier image
  `alt_text` varchar(255) DEFAULT NULL,                      -- Texte alternatif pour l'image
  `caption` varchar(500) DEFAULT NULL,                       -- Légende de l'image
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,                -- Indique si l'image est la principale
  `sort_order` int(11) NOT NULL DEFAULT 0,                   -- Ordre de tri des images
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `is_primary` (`is_primary`),                          -- Index sur l'image principale
  KEY `sort_order` (`sort_order`),                          -- Index sur l'ordre de tri
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE -- Contrainte référentielle
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE TICKETS - TICKETS D'ACHAT
-- ========================================
--
-- Table pour les tickets d'achat et leur statut
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers l'utilisateur
  `listing_id` int(11) NOT NULL,                            -- Référence vers l'annonce
  `numero_ticket` varchar(50) NOT NULL,                     -- Numéro de ticket unique
  `ticket_price` decimal(6,2) NOT NULL,                    -- Prix du ticket
  `status` enum('active','used','expired','cancelled') NOT NULL DEFAULT 'active', -- Statut du ticket
  `date_achat` timestamp NOT NULL DEFAULT current_timestamp(), -- Date d'achat
  `used_date` timestamp NULL DEFAULT NULL,                  -- Date d'utilisation (si applicable)
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `numero_ticket` (`numero_ticket`),              -- Contrainte d'unicité sur le numéro
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `date_achat` (`date_achat`),                         -- Index sur la date d'achat
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE QCM_QUESTIONS - QUESTIONS DU QCM
-- ========================================
--
-- Table pour les questions du questionnaire à choix multiples
--

CREATE TABLE `qcm_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `listing_id` int(11) DEFAULT NULL COMMENT 'NULL pour questions générales', -- Référence vers l'annonce (ou NULL)
  `question_text` text NOT NULL,                           -- Texte de la question
  `question_type` enum('single','multiple','true_false') NOT NULL DEFAULT 'single', -- Type de question
  `answer_a` varchar(500) DEFAULT NULL,                    -- Réponse A
  `answer_b` varchar(500) DEFAULT NULL,                    -- Réponse B
  `answer_c` varchar(500) DEFAULT NULL,                    -- Réponse C
  `answer_d` varchar(500) DEFAULT NULL,                    -- Réponse D
  `correct_answers` json NOT NULL COMMENT 'Array des bonnes réponses', -- JSON des bonnes réponses
  `explanation` text DEFAULT NULL,                         -- Explication de la réponse
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium', -- Difficulté de la question
  `points` int(11) NOT NULL DEFAULT 1,                      -- Points attribués pour la question
  `is_active` tinyint(1) NOT NULL DEFAULT 1,                -- Indique si la question est active
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `difficulty` (`difficulty`),                          -- Index sur la difficulté
  KEY `is_active` (`is_active`),                            -- Index sur l'activité
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE SET NULL -- Contrainte référentielle
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE QCM_RESULTS - RÉSULTATS DES QCM
-- ========================================
--
-- Table pour les résultats des questionnaires à choix multiples
--

CREATE TABLE `qcm_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers l'utilisateur
  `listing_id` int(11) NOT NULL,                            -- Référence vers l'annonce
  `ticket_id` int(11) NOT NULL,                             -- Référence vers le ticket
  `total_questions` int(11) NOT NULL,                       -- Nombre total de questions
  `temps_limite` int(11) NOT NULL DEFAULT 300,              -- Temps limite en secondes
  `score` decimal(5,2) DEFAULT NULL,                        -- Score obtenu
  `pourcentage` decimal(5,2) DEFAULT NULL,                  -- Pourcentage de bonnes réponses
  `bonnes_reponses` int(11) NOT NULL DEFAULT 0,             -- Nombre de bonnes réponses
  `temps_reponse` int(11) DEFAULT NULL COMMENT 'Temps en secondes', -- Temps pris pour répondre
  `detail_reponses` json DEFAULT NULL COMMENT 'Détail des réponses', -- Détail des réponses
  `status` enum('en_cours','termine','qualifie','elimine') NOT NULL DEFAULT 'en_cours', -- Statut du résultat
  `date_passage` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de passage du QCM
  `date_fin` timestamp NULL DEFAULT NULL,                   -- Date de fin du QCM
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `ticket_id` (`ticket_id`),                    -- Contrainte d'unicité sur le ticket
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `date_passage` (`date_passage`),                      -- Index sur la date de passage
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE QCM_RESPONSES - RÉPONSES AUX QUESTIONS
-- ========================================
--
-- Table pour les réponses détaillées aux questions du QCM
--

CREATE TABLE `qcm_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `attempt_id` int(11) NOT NULL,                              -- Référence vers l'essai
  `question_id` int(11) NOT NULL,                            -- Référence vers la question
  `user_answers` json NOT NULL COMMENT 'Array des réponses de l\'utilisateur', -- Réponses de l'utilisateur
  `is_correct` tinyint(1) NOT NULL,                         -- Indique si la réponse est correcte
  `points_earned` decimal(5,2) NOT NULL DEFAULT 0,          -- Points obtenus pour cette question
  `time_taken` int(11) DEFAULT NULL COMMENT 'Temps en secondes pour cette question', -- Temps pris pour cette question
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `attempt_id` (`attempt_id`),                          -- Index sur l'essai
  KEY `question_id` (`question_id`),                        -- Index sur la question
  KEY `is_correct` (`is_correct`),                          -- Index sur la correctitude
  FOREIGN KEY (`attempt_id`) REFERENCES `qcm_results` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`question_id`) REFERENCES `qcm_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE LETTERS - LETTRES DE MOTIVATION
-- ========================================
--
-- Table pour les lettres de motivation soumises
--

CREATE TABLE `letters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers l'utilisateur
  `listing_id` int(11) NOT NULL,                            -- Référence vers l'annonce
  `ticket_id` int(11) NOT NULL,                             -- Référence vers le ticket
  `contenu` text NOT NULL,                                 -- Contenu de la lettre
  `titre` varchar(255) DEFAULT NULL,                       -- Titre de la lettre
  `style` enum('formel','personnel','creatif','simple') DEFAULT 'personnel', -- Style de rédaction
  `covers_motivation` tinyint(1) DEFAULT 0,                -- Couvre la motivation personnelle
  `covers_project` tinyint(1) DEFAULT 0,                   -- Couvre le projet de vie
  `covers_financial` tinyint(1) DEFAULT 0,                 -- Couvre la situation financière
  `covers_family` tinyint(1) DEFAULT 0,                    -- Couvre la situation familiale
  `note_jury` decimal(3,1) DEFAULT NULL COMMENT 'Note sur 10', -- Note du jury
  `commentaires_jury` text DEFAULT NULL,                   -- Commentaires du jury
  `jury_member_id` int(11) DEFAULT NULL,                   -- ID du membre du jury qui a évalué
  `status` enum('brouillon','soumise','evaluee','gagnante','rejetee') NOT NULL DEFAULT 'brouillon', -- Statut de la lettre
  `version` int(11) NOT NULL DEFAULT 1,                    -- Version de la lettre
  `mots_cles` text DEFAULT NULL,                           -- Mots-clés extraits du contenu
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `date_evaluation` timestamp NULL DEFAULT NULL,            -- Date d'évaluation par le jury
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `ticket_id` (`ticket_id`),                    -- Contrainte d'unicité sur le ticket
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `date_creation` (`date_creation`),                   -- Index sur la date de création
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE JURY_MEMBERS - MEMBRES DU JURY
-- ========================================
--
-- Table pour les membres du jury
--

CREATE TABLE `jury_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers l'utilisateur
  `listing_id` int(11) NOT NULL,                            -- Référence vers l'annonce
  `role` enum('president','member','secretary') NOT NULL DEFAULT 'member', -- Rôle du membre
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date d'attribution
  `is_active` tinyint(1) NOT NULL DEFAULT 1,                -- Indique si le membre est actif
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `user_listing` (`user_id`, `listing_id`),      -- Contrainte d'unicité sur l'utilisateur et l'annonce
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `role` (`role`),                                     -- Index sur le rôle
  KEY `is_active` (`is_active`),                          -- Index sur l'activité
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE JURY_EVALUATIONS - ÉVALUATIONS DU JURY
-- ========================================
--
-- Table pour les évaluations des lettres de motivation
--

CREATE TABLE `jury_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `jury_member_id` int(11) NOT NULL,                        -- Référence vers le membre du jury
  `letter_id` int(11) NOT NULL,                             -- Référence vers la lettre
  `score` decimal(3,1) NOT NULL COMMENT 'Note sur 10', -- Note du jury
  `comments` text DEFAULT NULL,                         -- Commentaires du jury
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de l'évaluation
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `jury_letter` (`jury_member_id`, `letter_id`), -- Contrainte d'unicité sur le membre et la lettre
  KEY `letter_id` (`letter_id`),                          -- Index sur la lettre
  KEY `evaluation_date` (`evaluation_date`),                -- Index sur la date d'évaluation
  FOREIGN KEY (`jury_member_id`) REFERENCES `jury_members` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE WINNERS - GAGNANTS DES CONCOURS
-- ========================================
--
-- Table pour les gagnants des concours
--

CREATE TABLE `winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `listing_id` int(11) NOT NULL,                              -- Référence vers l'annonce
  `user_id` int(11) NOT NULL,                                -- Référence vers l'utilisateur
  `ticket_id` int(11) NOT NULL,                             -- Référence vers le ticket
  `qcm_score` decimal(5,2) NOT NULL,                       -- Score du QCM
  `letter_score` decimal(3,1) NOT NULL,                     -- Score de la lettre
  `total_score` decimal(5,2) NOT NULL,                     -- Score total
  `announced_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de l'annonce
  `status` enum('announced','contacted','confirmed','declined') NOT NULL DEFAULT 'announced', -- Statut du gagnant
  `notes` text DEFAULT NULL,                             -- Notes du gagnant
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `listing_id` (`listing_id`),                  -- Contrainte d'unicité sur l'annonce
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `ticket_id` (`ticket_id`),                          -- Index sur le ticket
  KEY `status` (`status`),                                 -- Index sur le statut
  KEY `announced_at` (`announced_at`),                    -- Index sur la date d'annonce
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE FEEDBACKS - RETOURS D'EXPÉRIENCE
-- ========================================
--
-- Table pour les retours d'expérience des utilisateurs
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `listing_id` int(11) NOT NULL,                              -- Référence vers l'annonce
  `user_id` int(11) NOT NULL,                                -- Référence vers l'utilisateur
  `type` enum('seller','winner','participant') NOT NULL, -- Type de retour
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5), -- Note de l'utilisateur
  `title` varchar(255) DEFAULT NULL,                         -- Titre du retour
  `comment` text DEFAULT NULL,                             -- Commentaire du retour
  `is_public` tinyint(1) NOT NULL DEFAULT 1,                -- Indique si le retour est public
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,                -- Indique si le retour est vérifié
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `listing_id` (`listing_id`),                          -- Index sur l'annonce
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `type` (`type`),                                     -- Index sur le type
  KEY `rating` (`rating`),                                 -- Index sur la note
  KEY `is_public` (`is_public`),                          -- Index sur la publicité
  KEY `created_at` (`created_at`),                        -- Index sur la date de création
  FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE, -- Contrainte référentielle
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE NOTIFICATIONS - NOTIFICATIONS SYSTÈME
-- ========================================
--
-- Table pour les notifications système
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) NOT NULL,                              -- Référence vers l'utilisateur
  `type` varchar(100) NOT NULL,                           -- Type de notification
  `title` varchar(255) NOT NULL,                           -- Titre de la notification
  `message` text NOT NULL,                                 -- Message de la notification
  `data` json DEFAULT NULL,                               -- Données supplémentaires (JSON)
  `is_read` tinyint(1) NOT NULL DEFAULT 0,                -- Indique si la notification est lue
  `read_at` timestamp NULL DEFAULT NULL,                  -- Date de lecture
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `type` (`type`),                                     -- Index sur le type
  KEY `is_read` (`is_read`),                              -- Index sur la lecture
  KEY `created_at` (`created_at`),                        -- Index sur la date de création
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE SYSTEM_SETTINGS - PARAMÈTRES SYSTÈME
-- ========================================
--
-- Table pour les paramètres de l'application
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `key` varchar(100) NOT NULL,                           -- Clé du paramètre
  `value` text NOT NULL,                                 -- Valeur du paramètre
  `description` varchar(500) DEFAULT NULL,                -- Description du paramètre
  `type` enum('string','integer','boolean','json','decimal') NOT NULL DEFAULT 'string', -- Type de donnée
  `is_public` tinyint(1) NOT NULL DEFAULT 0,                -- Indique si le paramètre est public
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Date de modification
  PRIMARY KEY (`id`),                                       -- Clé primaire
  UNIQUE KEY `key` (`key`)                                -- Contrainte d'unicité sur la clé
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE AUDIT_LOGS - JOURNAL D'AUDIT
-- ========================================
--
-- Table pour le journal d'audit des actions
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,                    -- Identifiant unique auto-incrémenté
  `user_id` int(11) DEFAULT NULL,                          -- Référence vers l'utilisateur (peut être NULL)
  `action` varchar(100) NOT NULL,                           -- Action effectuée (ex: 'create', 'update', 'delete')
  `table_name` varchar(100) DEFAULT NULL,                  -- Nom de la table concernée
  `record_id` int(11) DEFAULT NULL,                         -- Identifiant de l'enregistrement concerné
  `old_values` json DEFAULT NULL,                         -- Valeurs avant la modification (JSON)
  `new_values` json DEFAULT NULL,                         -- Valeurs après la modification (JSON)
  `ip_address` varchar(45) DEFAULT NULL,                  -- Adresse IP de l'utilisateur
  `user_agent` text DEFAULT NULL,                         -- Agent utilisateur (navigateur, OS, etc.)
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(), -- Date de création
  PRIMARY KEY (`id`),                                       -- Clé primaire
  KEY `user_id` (`user_id`),                                -- Index sur l'utilisateur
  KEY `action` (`action`),                                 -- Index sur l'action
  KEY `table_name` (`table_name`),                        -- Index sur le nom de la table
  KEY `created_at` (`created_at`),                        -- Index sur la date de création
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL -- Contrainte référentielle
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INSERTION DES DONNÉES INITIALES
-- ========================================
--
-- NOTE IMPORTANTE : Les données utilisateurs/admins sensibles sont dans
-- le fichier howiwinmyhome-data.sql qui n'est PAS versionné dans Git.
-- Ce fichier contient uniquement la structure des tables.
--
-- Pour ajouter des données de test, créer un fichier howiwinmyhome-data.sql
-- avec les INSERT nécessaires (ce fichier sera ignoré par Git).
--

-- Questions QCM par défaut (générales)
INSERT INTO `qcm_questions` (`question_text`, `question_type`, `answer_a`, `answer_b`, `answer_c`, `answer_d`, `correct_answers`, `explanation`, `difficulty`, `points`) VALUES
('Quelle est la capitale de la France ?', 'single', 'Londres', 'Paris', 'Berlin', 'Madrid', '["B"]', 'Paris est la capitale de la France depuis le 12ème siècle.', 'easy', 1),
('Combien de côtés a un hexagone ?', 'single', '4', '5', '6', '7', '["C"]', 'Un hexagone a 6 côtés (hexa = 6 en grec).', 'easy', 1),
('Quel est le plus grand océan du monde ?', 'single', 'Océan Atlantique', 'Océan Pacifique', 'Océan Indien', 'Océan Arctique', '["B"]', 'L\'océan Pacifique est le plus grand océan du monde.', 'medium', 1),
('En quelle année a eu lieu la Révolution française ?', 'single', '1789', '1799', '1769', '1779', '["A"]', 'La Révolution française a commencé en 1789 avec la prise de la Bastille.', 'medium', 1),
('Quel est le symbole chimique de l\'or ?', 'single', 'Ag', 'Au', 'Fe', 'Cu', '["B"]', 'Au vient du latin "aurum" qui signifie or.', 'medium', 1);

-- Paramètres système par défaut
INSERT INTO `system_settings` (`key`, `value`, `description`, `type`, `is_public`) VALUES
('site_name', 'How I Win My Home', 'Nom du site', 'string', 1),
('site_description', 'Participez à des jeux pour remporter votre futur chez-vous !', 'Description du site', 'string', 1),
('max_tickets_per_user', '10', 'Nombre maximum de tickets par utilisateur', 'integer', 1),
('qcm_time_limit', '180', 'Limite de temps pour le QCM en secondes', 'integer', 1),
('letter_min_length', '500', 'Longueur minimale des lettres de motivation', 'integer', 1),
('letter_max_length', '2000', 'Longueur maximale des lettres de motivation', 'integer', 1),
('maintenance_mode', '0', 'Mode maintenance activé', 'boolean', 0),
('registration_enabled', '1', 'Inscription des utilisateurs activée', 'boolean', 1),
('email_verification_required', '0', 'Vérification email obligatoire', 'boolean', 1);

-- ========================================
-- INDEX ET OPTIMISATIONS
-- ========================================
--
-- Création d'index pour améliorer les performances
--

-- Index composites pour améliorer les performances
CREATE INDEX `idx_listings_status_dates` ON `listings` (`status`, `start_date`, `end_date`);
CREATE INDEX `idx_tickets_user_listing` ON `tickets` (`user_id`, `listing_id`, `status`);
CREATE INDEX `idx_qcm_results_user_listing` ON `qcm_results` (`user_id`, `listing_id`, `status`);
CREATE INDEX `idx_letters_user_listing` ON `letters` (`user_id`, `listing_id`, `status`);

-- Index pour les recherches textuelles
CREATE FULLTEXT INDEX `idx_listings_search` ON `listings` (`title`, `description`, `city`);
CREATE FULLTEXT INDEX `idx_qcm_questions_search` ON `qcm_questions` (`question_text`);

-- ========================================
-- MISE À JOUR DES DONNÉES EXISTANTES
-- ========================================
-- Mettre à jour les valeurs existantes pour la cohérence
UPDATE `listings` SET `prix_total` = `price`, `prix_ticket` = `ticket_price` WHERE `prix_total` IS NULL;

COMMIT;
