-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : ven. 09 déc. 2022 à 20:44
-- Version du serveur : 10.6.5-MariaDB
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blogs`
--

-- --------------------------------------------------------

--
-- Structure de la table `blogpost`
--

DROP TABLE IF EXISTS `blogpost`;
CREATE TABLE IF NOT EXISTS `blogpost` (
  `idPost` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `chapo` text NOT NULL,
  `content` longtext NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `dateCreation` datetime NOT NULL DEFAULT current_timestamp(),
  `dateMiseAJour` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `idAuthor` int(11) NOT NULL,
  PRIMARY KEY (`idPost`),
  KEY `Fk_author` (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idCategorie` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `categorieblogpost`
--

DROP TABLE IF EXISTS `categorieblogpost`;
CREATE TABLE IF NOT EXISTS `categorieblogpost` (
  `idcategorie` int(11) NOT NULL,
  `idblogpost` int(11) NOT NULL,
  PRIMARY KEY (`idcategorie`,`idblogpost`),
  KEY `FK_blogpost` (`idblogpost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `idComment` int(11) NOT NULL AUTO_INCREMENT,
  `contenu` text DEFAULT NULL,
  `datePublication` datetime DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `isValide` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idComment`),
  KEY `iduser` (`idUser`),
  KEY `idPost` (`idPost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `idimage` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idimage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `imageblogpost`
--

DROP TABLE IF EXISTS `imageblogpost`;
CREATE TABLE IF NOT EXISTS `imageblogpost` (
  `idimage` int(11) NOT NULL,
  `idblogpost` int(11) NOT NULL,
  PRIMARY KEY (`idimage`,`idblogpost`),
  KEY `FK_blogpost` (`idblogpost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `Fk_author` FOREIGN KEY (`idAuthor`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `categorieblogpost`
--
ALTER TABLE `categorieblogpost`
  ADD CONSTRAINT `FK_blogpost` FOREIGN KEY (`idblogpost`) REFERENCES `blogpost` (`idPost`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_categorie` FOREIGN KEY (`idcategorie`) REFERENCES `categorie` (`idCategorie`);

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `Commentaires_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_BLOGPOST_COMMENTAIRE` FOREIGN KEY (`idPost`) REFERENCES `blogpost` (`idPost`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `imageblogpost`
--
ALTER TABLE `imageblogpost`
  ADD CONSTRAINT `FK_blogpost_image` FOREIGN KEY (`idblogpost`) REFERENCES `blogpost` (`idPost`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_image` FOREIGN KEY (`idimage`) REFERENCES `image` (`idimage`) ON DELETE CASCADE;
COMMIT;

INSERT INTO `users` VALUES (1,'admin','admin','admin@exemple.com',1,'$2y$10$4gW3RMR6F0voR5Z4aYxe2ed/uXRwG.mfoyizVfDcIfrcXr.lkFGiK');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

INSERT INTO `categorie` (`libelle`) values ('Astronomie'), ('Informatique'), ('Jeux vidéo'), ('Sport'), ('Autre');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
