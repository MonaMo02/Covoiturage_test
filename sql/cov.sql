-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 01 fév. 2024 à 15:06
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Structure de la table `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int NOT NULL primary key,
  `evaluateur_user_id` int NOT NULL,
  `evalue_user_id` int NOT NULL,
  `evaluation` int NOT NULL,
  `trajet_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messagerie`
--

CREATE TABLE `messagerie` (
  `id` int NOT NULL,
  `expediteur_user_id` int NOT NULL,
  `destinataire_user_id` int NOT NULL,
  `titre` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext NOT NULL
)

-- --------------------------------------------------------


--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `password` varchar(100) NOT NULL,
  `login` varchar(45) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `num_tel` varchar(10) NOT NULL,
  `matricule` varchar(10) NOT NULL,
  `compte` int DEFAULT NULL
)
--
-- Structure de la table `pilote`
--

CREATE TABLE `pilote` (
  pilote_user_id INTEGER primary key,
  voiture_id INTEGER NOT NULL, 
  voiture_marque VARCHAR(45) NOT NULL,
  voiture_annee INTEGER NOT NULL,
  voiture_modele VARCHAR(45) NOT NULL,
  voiture_couleur VARCHAR(45) NOT NULL,
  photo LONGTEXT NULL,
  CONSTRAINT fk_pilote_user
    FOREIGN KEY (pilote_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION 
  )

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `id` int NOT NULL PRIMARY KEY,
  `lieu_depart` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `places_max` int NOT NULL,
  `places_prises` int NOT NULL,
  `date` date NOT NULL,
  `pilote_user_id` int NOT NULL,
  `heure_dep` varchar(5) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `effectue` tinyint(4) NOT NULL
)



--
-- Structure de la table `trajet_passager`
--

CREATE TABLE `trajet_passager` (
  `trajet_id` int NOT NULL,
  `user_id` int NOT NULL,
  `position` varchar(45) NOT NULL,
  `nb_places` int NOT NULL,
  `proximité` decimal(10,0) NOT NULL,
  constraint pk_tp primary key (trajet_id,user_id)
) 

--
-- Déchargement des données de la table `trajet_passager`
--

INSERT INTO `trajet_passager` (`id_reserv`, `trajet_id`, `user_id`, `nb_places`) VALUES
(1, 3, 32, 1),
(2, 3, 33, 1),
(3, 3, 33, 1),
(4, 3, 32, 1),
(5, 3, 32, 1),
(6, 3, 37, 1),
(7, 3, 37, 1),
(8, 3, 37, 1),
(9, 3, 37, 1),
(10, 3, 37, 1),
(11, 3, 37, 1),
(12, 3, 37, 1),
(13, 3, 37, 1),
(14, 3, 37, 1);

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int NOT NULL primary key,
  `credit_user_id` int NOT NULL,
  `debit_user_id` int NOT NULL,
  `somme` decimal(10,0) NOT NULL
) 

-- --------------------------------------------------------




--
-- Structure de la table `ville_arrivee`
--

CREATE TABLE `ville_arrivee` (
  `nom` varchar(200) NOT NULL primary key,
  `destinationlat` varchar(30) NOT NULL,
  `destinationlon` varchar(30) NOT NULL
)


--
-- Structure de la table `ville_depart`
--

CREATE TABLE `ville_depart` (
  `nom` varchar(200) NOT NULL primary key,
  `locationlat` varchar(30) NOT NULL,
  `locationlon` varchar(30) NOT NULL
)


--
-- Index pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD FOREIGN KEY `fk_evaluateur` (`evaluateur_user_id`) REFERENCES user (`id`),
  ADD FOREIGN KEY `fk_evalue` (`evalue_user_id`) REFERENCES user (`id`),
  ADD FOREIGN KEY `fk_trajet_id` (`trajet_id`) REFERENCES trajet (`id`);

--
-- Index pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD FOREIGN KEY `fk_expediteur` (`expediteur_user_id`) REFERENCES user (`id`),
  ADD FOREIGN KEY `fk_destinataire` (`destinataire_user_id`) REFERENCES user (`id`);


--
-- Index pour la table `trajet`

--
ALTER TABLE `trajet`
  ADD constraint foreign KEY `fk_depart` (`lieu_depart`) references `ville_depart` (nom) on delete no action,
  ADD constraint foreign KEY `fk_destination` (`destination`) references `ville_arivee` (nom) on delete no action;

--
-- Index pour la table `trajet_passager`
--
ALTER TABLE `trajet_passager`
  ADD constraint foreign KEY `fk_psgr_trajet_user` (`user_id`) references user(`id`) on delete no action,
  ADD constraint foreign KEY `fk_pos_ville` (`position`) references ville_depart(`nom`) on delete no action;



--
-- AUTO_INCREMENT pour la table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pilote`
--
ALTER TABLE `pilote`
  MODIFY `voiture_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `fk_evaluateur` FOREIGN KEY (`evaluateur_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_evalue` FOREIGN KEY (`evalue_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trajet_id` FOREIGN KEY (`trajet_id`) REFERENCES `trajet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD CONSTRAINT `fk_destinataire` FOREIGN KEY (`destinataire_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_expediteur` FOREIGN KEY (`expediteur_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


--
-- Contraintes pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD CONSTRAINT `fk_depart` FOREIGN KEY (`lieu_depart`) REFERENCES `ville_depart` (`nom`),
  ADD CONSTRAINT `fk_destination` FOREIGN KEY (`destination`) REFERENCES `ville_arrivee` (`nom`);

--
-- Contraintes pour la table `trajet_passager`
--
ALTER TABLE `trajet_passager`
  ADD CONSTRAINT `fk_trajet` FOREIGN KEY (`trajet_id`) REFERENCES `trajet` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_credit` FOREIGN KEY (`credit_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_debit` FOREIGN KEY (`debit_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;