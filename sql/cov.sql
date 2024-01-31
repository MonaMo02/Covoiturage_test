-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 jan. 2024 à 15:52
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cov`
--

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL,
  `evaluateur_user_id` int(11) NOT NULL,
  `evalue_user_id` int(11) NOT NULL,
  `evaluation` int(11) NOT NULL,
  `trajet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messagerie`
--

CREATE TABLE `messagerie` (
  `id` int(11) NOT NULL,
  `expediteur_user_id` int(11) NOT NULL,
  `destinataire_user_id` int(11) NOT NULL,
  `titre` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pilote`
--

CREATE TABLE pilote (
  pilote_user_id INTEGER primary key,
  voiture_id INTEGER NOT NULL AUTOINCREMENT, 
  voiture_marque VARCHAR(45) NOT NULL,
  voiture_annee INTEGER NOT NULL,
  voiture_modele VARCHAR(45) NOT NULL,
  voiture_couleur VARCHAR(45) NOT NULL,
  photo LONGTEXT NULL,
  CONSTRAINT fk_pilote_user
    FOREIGN KEY (pilote_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION 
    ON UPDATE NO ACTION)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;;


-- Déchargement des données de la table `pilote`
--

INSERT INTO `pilote` (`pilote_user_id`, `voiture_id`, `voiture_marque`, `voiture_annee`, `voiture_modele`, `voiture_couleur`, `photo`) VALUES
(31, 0, 'suzuki', 1999, 'maruti', 'blue', '../photo_voiture/chaitaroo.png');

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `id` int(11) NOT NULL PRIMARY KEY,
  `lieu_depart` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `places_max` int(11) NOT NULL,
  `places_prises` int(11) NOT NULL,
  `date` date NOT NULL,
  `pilote_user_id` int(11) NOT NULL,
  `heure_dep` varchar(5) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `effectue` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajet`
--

INSERT INTO `trajet` (`id`, `lieu_depart`, `destination`, `places_max`, `places_prises`, `date`, `pilote_user_id`, `heure_dep`, `prix`, `effectue`) VALUES
(2, 'CNAS - CASNOS - CAMSP, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', 'Château Saint Anton, 2A, Paseo Alcalde Francisco Vázquez, A Mestranza, Vieille Ville, La Corogne, La Corogne, Galice, 15001, Espagne', 3, 2, '2024-01-01', 31, '12:00', 100, 0),
(3, 'Société générale Algérie, 1, Rue Rezig Kadda, Cité EPLF, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16042, Algérie', 'Château Saint Anton, 2A, Paseo Alcalde Francisco Vázquez, A Mestranza, Vieille Ville, La Corogne, La Corogne, Galice, 15001, Espagne', 3, 0, '2024-01-01', 31, '12:00', 200, 0),
(4, 'Bab Ezzouar, Algiers, Algeria', 'Château Saint Anton, 2A, Paseo Alcalde Francisco Vázquez, A Mestranza, Vieille Ville, La Corogne, La Corogne, Galice, 15001, Espagne', 22, 0, '2024-01-01', 31, '12:00', 232, 0),
(5, 'Cité Universitaire pour filles Baya Hocine (CUB4), Cité Smail Yefsah, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', 'Château Saint Anton, 2A, Paseo Alcalde Francisco Vázquez, A Mestranza, Vieille Ville, La Corogne, La Corogne, Galice, 15001, Espagne', 2, 0, '2024-01-01', 31, '12:00', 100, 0);

-- --------------------------------------------------------

--
-- Structure de la table `trajet_passager`
--

CREATE TABLE `trajet_passager` (
  `trajet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position` varchar(45) NOT NULL,
  `nb_places` int(11) NOT NULL,
  `proximité` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `credit_user_id` int(11) NOT NULL,
  `debit_user_id` int(11) NOT NULL,
  `somme` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `login` varchar(45) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `num_tel` varchar(10) NOT NULL,
  `matricule` varchar(10) NOT NULL,
  `compte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `password`, `login`, `nom`, `prenom`, `email`, `num_tel`, `matricule`, `compte`) VALUES
(1, '33ab50d3b65184d0bfb5dad05bdbe109', 'Florian', 'Culie', 'Florian', 'florian.culie@utt.fr', '0667856578', '1919078654', NULL),
(2, '64a43b6ca15d128ac6a0679b39bc9c07', 'patel', 'Patelli', 'Alexandre', 'alexandre.patelli@utt.fr', '0768946578', '2121078654', NULL),
(3, 'b50c7ccad3f17803d32f7892c3eca7fa', 'roiloth', 'Rollin', 'Francois', 'francois_rollin@laposte.net', '0563354378', '2020794354', NULL),
(4, 'b282869b01636acd9846eeb06728f9f2', 'kaamelott', 'Astier', 'Alexandre', 'alexandre.astier@wanadoo.com', '0664548490', '1919310985', NULL),
(5, '993614ef6850173f7f372d227a98532b', 'petitpedestre', 'Astier', 'Simon', 'simon-astier@wanadoo.com', '0654749248', '1919320578', NULL),
(6, 'dc6420a6744bf33b7e52ab5e076d6c0f', 'mouallez', 'Lescure', 'Pierre', 'pierre.lescure@free.fr', '0665984535', '2121350785', NULL),
(7, '9d6a586365f6047f187922e42f7012cc', 'chatonbleu', 'Justin', 'Julie', 'lamideschatons@gmail.com', '0676362610', '1919310857', NULL),
(8, 'd6ba0682d75eb986237fb6b594f8a31f', 'joey', 'Leblanc', 'Matt', 'matt_leblanc@sfr.com', '0553768690', '2222318690', NULL),
(9, 'b7e1509f89fb4e58f750856a642f6e98', 'mamar', 'Tnani', 'Aymar', 'aymar.tnani@utt.fr', '0768983823', '2020359890', NULL),
(10, '931ed1920da0c0aab4cb7088ecf3d804', 'robyneterie', 'Roby', 'Maxime', 'roby.maxime@gmail.com', '0676369278', '1919378796', NULL),
(26, 'dc6420a6744bf33b7e52ab5e076d6c0f', 'mouallez', 'Lescure', 'Pierre', 'pierre.lescure@free.fr', '0665984535', '2121350785', NULL),
(27, '9d6a586365f6047f187922e42f7012cc', 'chatonbleu', 'Justin', 'Julie', 'lamideschatons@gmail.com', '0676362610', '1919310857', NULL),
(28, 'd6ba0682d75eb986237fb6b594f8a31f', 'joey', 'Leblanc', 'Matt', 'matt_leblanc@sfr.com', '0553768690', '2222318690', NULL),
(29, 'b7e1509f89fb4e58f750856a642f6e98', 'mamar', 'Tnani', 'Aymar', 'aymar.tnani@utt.fr', '0768983823', '2020359890', NULL),
(30, '931ed1920da0c0aab4cb7088ecf3d804', 'robyneterie', 'Roby', 'Maxime', 'roby.maxime@gmail.com', '0676369278', '1919378796', NULL),
(31, '202cb962ac59075b964b07152d234b70', 'chaitaroo', 'fodil cherif chaima', 'chaima', 'eylarichi@gmail.com', '+336720722', '12345263', 0),
(32, '202cb962ac59075b964b07152d234b70', 'pow', 'pow', 'bbies', 'powbbies@gmail.com', '+213672072', '120031694', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ville_arrivee`
--

CREATE TABLE `ville_arrivee` (
  `nom` varchar(200) NOT NULL,
  `destinationlat` varchar(30) NOT NULL,
  `destinationlon` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ville_arrivee`
--

INSERT INTO `ville_arrivee` (`nom`, `destinationlat`, `destinationlon`) VALUES
('Château Saint Anton, 2A, Paseo Alcalde Francisco Vázquez, A Mestranza, Vieille Ville, La Corogne, La Corogne, Galice, 15001, Espagne', '43.36568965', ' -8.387522781184902');

-- --------------------------------------------------------

--
-- Structure de la table `ville_depart`
--

CREATE TABLE `ville_depart` (
  `nom` varchar(200) NOT NULL,
  `locationlat` varchar(30) NOT NULL,
  `locationlon` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ville_depart`
--

INSERT INTO `ville_depart` (`nom`, `locationlat`, `locationlon`) VALUES
('Bab Ezzouar, Algiers, Algeria', '36.714081', '3.188828'),
('Binarytech, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', '36.7159845', '3.1879194'),
('Cité Universitaire pour filles Baya Hocine (CUB4), Cité Smail Yefsah, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', '36.71314365', '3.1892323871532398'),
('CNAS - CASNOS - CAMSP, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', '36.716109', '3.1872579'),
('Société générale Algérie, 1, Rue Rezig Kadda, Cité EPLF, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16042, Algérie', '36.7148763', '3.1891634');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`),
  ADD FOREIGN KEY `fk_evaluateur` (`evaluateur_user_id`) REFERENCES user (`id`),
  ADD FOREIGN KEY `fk_evalue` (`evalue_user_id`) REFERENCES user (`id`),
  ADD FOREIGN KEY `fk_trajet_id` (`trajet_id`) REFERENCES trajet (`id`);

--
-- Index pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD PRIMARY KEY (`id`),
  ADD FOREIGN KEY `fk_expediteur` (`expediteur_user_id`),
  ADD FOREIGN KEY `fk_destinataire` (`destinataire_user_id`);

--
-- Index pour la table `pilote`
--
ALTER TABLE `pilote`
  ADD PRIMARY KEY (`pilote_user_id`);

--
-- Index pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_depart` (`lieu_depart`),
  ADD KEY `fk_destination` (`destination`);

--
-- Index pour la table `trajet_passager`
--
ALTER TABLE `trajet_passager`
  ADD PRIMARY KEY (`trajet_id`,`user_id`),
  ADD KEY `fk_psgr_trajet_user` (`user_id`),
  ADD KEY `fk_pos_ville` (`position`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_credit` (`credit_user_id`),
  ADD KEY `fk_debit` (`debit_user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ville_arrivee`
--
ALTER TABLE `ville_arrivee`
  ADD PRIMARY KEY (`nom`);

--
-- Index pour la table `ville_depart`
--
ALTER TABLE `ville_depart`
  ADD PRIMARY KEY (`nom`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
-- Contraintes pour la table `pilote`
--
ALTER TABLE `pilote`
  ADD CONSTRAINT `fk_pilote_user` FOREIGN KEY (`pilote_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `fk_pos_ville` FOREIGN KEY (`position`) REFERENCES `ville_depart` (`nom`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_psgr_trajet` FOREIGN KEY (`trajet_id`) REFERENCES `trajet` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_psgr_trajet_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_credit` FOREIGN KEY (`credit_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_debit` FOREIGN KEY (`debit_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
