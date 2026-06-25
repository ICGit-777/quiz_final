-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 22 juin 2026 à 13:20
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quiz_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--

CREATE TABLE `connexions` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_connexion` datetime DEFAULT CURRENT_TIMESTAMP,
  `adresse_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deconnexions`
--

CREATE TABLE `deconnexions` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_deconnexion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_a` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_b` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_c` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse_d` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonne_reponse` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` enum('facile','moyen','difficile') COLLATE utf8mb4_unicode_ci DEFAULT 'moyen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `question`, `reponse_a`, `reponse_b`, `reponse_c`, `reponse_d`, `bonne_reponse`, `categorie`, `niveau`) VALUES
(1, 'Quelle est la capitale de la France ?', 'Paris', 'Londres', 'Berlin', 'Madrid', 'A', 'Geographie', 'facile'),
(2, 'Quelle est la capitale de l Italie ?', 'Madrid', 'Rome', 'Athenes', 'Lisbonne', 'B', 'Geographie', 'facile'),
(3, 'Quelle est la capitale de l Espagne ?', 'Madrid', 'Barcelone', 'Seville', 'Valence', 'A', 'Geographie', 'facile'),
(4, 'Quelle est la capitale de l Allemagne ?', 'Munich', 'Hambourg', 'Berlin', 'Francfort', 'C', 'Geographie', 'facile'),
(5, 'Quelle est la capitale du Portugal ?', 'Porto', 'Lisbonne', 'Faro', 'Coimbra', 'B', 'Geographie', 'facile'),
(6, 'Quelle est la capitale du Canada ?', 'Toronto', 'Vancouver', 'Ottawa', 'Montreal', 'C', 'Geographie', 'moyen'),
(7, 'Quelle est la capitale de l Australie ?', 'Sydney', 'Melbourne', 'Canberra', 'Perth', 'C', 'Geographie', 'moyen'),
(8, 'Quelle est la capitale du Japon ?', 'Osaka', 'Kyoto', 'Tokyo', 'Yokohama', 'C', 'Geographie', 'facile'),
(9, 'Quelle est la capitale de la Russie ?', 'Saint-Petersbourg', 'Moscou', 'Kiev', 'Minsk', 'B', 'Geographie', 'facile'),
(10, 'Quel est le plus grand ocean du monde ?', 'Atlantique', 'Indien', 'Arctique', 'Pacifique', 'D', 'Geographie', 'moyen'),
(11, 'Quel est le plus long fleuve du monde ?', 'Amazone', 'Nil', 'Mississippi', 'Yangtse', 'B', 'Geographie', 'moyen'),
(12, 'Quel est le plus grand desert du monde ?', 'Sahara', 'Gobi', 'Antarctique', 'Kalahari', 'C', 'Geographie', 'difficile'),
(13, 'Combien de continents y a-t-il sur Terre ?', '5', '6', '7', '8', 'C', 'Geographie', 'facile'),
(14, 'Quel pays a la forme d une botte ?', 'Espagne', 'Grece', 'Italie', 'Portugal', 'C', 'Geographie', 'facile'),
(15, 'Quelle montagne est la plus haute du monde ?', 'K2', 'Everest', 'Kilimandjaro', 'Mont Blanc', 'B', 'Geographie', 'facile'),
(16, 'Combien font 5 x 6 ?', '11', '30', '56', '25', 'B', 'Mathematiques', 'facile'),
(17, 'Combien font 12 + 8 ?', '18', '20', '22', '24', 'B', 'Mathematiques', 'facile'),
(18, 'Combien font 9 x 9 ?', '81', '72', '99', '88', 'A', 'Mathematiques', 'facile'),
(19, 'Combien font 100 / 4 ?', '20', '25', '30', '50', 'B', 'Mathematiques', 'facile'),
(20, 'Quelle est la racine carree de 64 ?', '6', '7', '8', '9', 'C', 'Mathematiques', 'moyen'),
(21, 'Combien font 15 x 3 ?', '35', '40', '45', '50', 'C', 'Mathematiques', 'facile'),
(22, 'Quel est le resultat de 2 puissance 5 ?', '16', '32', '64', '128', 'B', 'Mathematiques', 'moyen'),
(23, 'Combien de cotes a un hexagone ?', '5', '6', '7', '8', 'B', 'Mathematiques', 'facile'),
(24, 'Quel est le nombre pi arrondi a deux decimales ?', '3.14', '3.41', '3.12', '3.16', 'A', 'Mathematiques', 'facile'),
(25, 'Combien font 7 x 8 ?', '54', '56', '58', '64', 'B', 'Mathematiques', 'facile'),
(26, 'Quel est le plus petit nombre premier ?', '0', '1', '2', '3', 'C', 'Mathematiques', 'moyen'),
(27, 'Combien font 144 / 12 ?', '10', '11', '12', '13', 'C', 'Mathematiques', 'facile'),
(28, 'Quel est le perimetre d un carre de cote 5 ?', '10', '15', '20', '25', 'C', 'Mathematiques', 'moyen'),
(29, 'Combien font 3 x 3 x 3 ?', '9', '18', '27', '36', 'C', 'Mathematiques', 'facile'),
(30, 'Combien de minutes y a-t-il dans 2 heures ?', '60', '90', '120', '150', 'C', 'Mathematiques', 'facile'),
(31, 'Qui a peint la Joconde ?', 'Van Gogh', 'Leonard de Vinci', 'Picasso', 'Monet', 'B', 'Art', 'moyen'),
(32, 'Qui a peint la Nuit Etoilee ?', 'Monet', 'Van Gogh', 'Renoir', 'Degas', 'B', 'Art', 'moyen'),
(33, 'Qui a sculpte le David ?', 'Michel-Ange', 'Donatello', 'Raphael', 'Bernini', 'A', 'Art', 'difficile'),
(34, 'Dans quel pays se trouve le musee du Louvre ?', 'Italie', 'France', 'Espagne', 'Royaume-Uni', 'B', 'Art', 'facile'),
(35, 'Quel artiste est connu pour le cubisme ?', 'Salvador Dali', 'Pablo Picasso', 'Claude Monet', 'Henri Matisse', 'B', 'Art', 'moyen'),
(36, 'Quel mouvement artistique est associe a Monet ?', 'Cubisme', 'Impressionnisme', 'Surrealisme', 'Baroque', 'B', 'Art', 'moyen'),
(37, 'Qui a peint Guernica ?', 'Salvador Dali', 'Joan Miro', 'Pablo Picasso', 'Diego Velazquez', 'C', 'Art', 'difficile'),
(38, 'Dans quelle ville se trouve la Tour Eiffel ?', 'Lyon', 'Marseille', 'Paris', 'Nice', 'C', 'Art', 'facile'),
(39, 'Quel style architectural est caracterise par des arcs et voutes ?', 'Gothique', 'Roman', 'Moderne', 'Baroque', 'B', 'Art', 'difficile'),
(40, 'Quel peintre s est coupe l oreille ?', 'Monet', 'Van Gogh', 'Cezanne', 'Manet', 'B', 'Art', 'moyen'),
(41, 'Quel langage est utilise pour le style des pages web ?', 'HTML', 'CSS', 'PHP', 'SQL', 'B', 'Informatique', 'facile'),
(42, 'Que signifie HTML ?', 'HyperText Markup Language', 'HighText Machine Language', 'Hyperlink Text Markup Language', 'Home Tool Markup Language', 'A', 'Informatique', 'moyen'),
(43, 'Quel langage est principalement utilise pour l intelligence artificielle ?', 'Java', 'Python', 'C++', 'Ruby', 'B', 'Informatique', 'moyen'),
(44, 'Que signifie SQL ?', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', 'Sequential Query Language', 'A', 'Informatique', 'moyen'),
(45, 'Quel symbole est utilise pour les commentaires en PHP sur une ligne ?', '//', '#', '/* */', 'A et B sont corrects', 'D', 'Informatique', 'difficile'),
(46, 'Quelle entreprise a cree le systeme d exploitation Windows ?', 'Apple', 'Google', 'Microsoft', 'IBM', 'C', 'Informatique', 'facile'),
(47, 'Quel est le langage de programmation cree par Guido van Rossum ?', 'Java', 'Python', 'JavaScript', 'C#', 'B', 'Informatique', 'moyen'),
(48, 'Que signifie CPU ?', 'Central Process Unit', 'Central Processing Unit', 'Computer Personal Unit', 'Central Processor Utility', 'B', 'Informatique', 'moyen'),
(49, 'Quel protocole est utilise pour envoyer des emails ?', 'HTTP', 'FTP', 'SMTP', 'SSH', 'C', 'Informatique', 'difficile'),
(50, 'Quelle balise HTML permet de creer un lien ?', '<link>', '<a>', '<href>', '<url>', 'B', 'Informatique', 'facile'),
(51, 'Quel logiciel est utilise pour gerer des bases de donnees MySQL via une interface web ?', 'phpMyAdmin', 'Notepad', 'XAMPP', 'FileZilla', 'A', 'Informatique', 'facile'),
(52, 'Quel format de fichier est utilise pour les feuilles de style ?', '.html', '.css', '.js', '.php', 'B', 'Informatique', 'facile'),
(53, 'Quelle fonction PHP permet de se connecter a une base de donnees avec PDO ?', 'connect()', 'new PDO()', 'mysql_connect()', 'open()', 'B', 'Informatique', 'difficile'),
(54, 'Quel est le successeur du protocole HTTP avec chiffrement ?', 'HTTPS', 'FTPS', 'HTTP2', 'SSH', 'A', 'Informatique', 'moyen'),
(55, 'Quelle extension de fichier est utilisee pour les scripts PHP ?', '.html', '.php', '.css', '.sql', 'B', 'Informatique', 'facile'),
(56, 'Qui a ecrit Les Miserables ?', 'Victor Hugo', 'Emile Zola', 'Honore de Balzac', 'Gustave Flaubert', 'A', 'Litterature', 'moyen'),
(57, 'Qui a ecrit Roméo et Juliette ?', 'Charles Dickens', 'William Shakespeare', 'Oscar Wilde', 'Jane Austen', 'B', 'Litterature', 'facile'),
(58, 'Quel auteur a ecrit Harry Potter ?', 'J.R.R. Tolkien', 'J.K. Rowling', 'C.S. Lewis', 'Roald Dahl', 'B', 'Litterature', 'facile'),
(59, 'Qui a ecrit Le Petit Prince ?', 'Antoine de Saint-Exupery', 'Albert Camus', 'Jean-Paul Sartre', 'Marcel Proust', 'A', 'Litterature', 'moyen'),
(60, 'Quel est le premier roman de la saga Game of Thrones ?', 'Le Trone de Fer', 'La Bataille des Rois', 'Le Donjon Rouge', 'L Hiver Vient', 'A', 'Litterature', 'difficile'),
(61, 'Qui a ecrit 1984 ?', 'Aldous Huxley', 'George Orwell', 'Ray Bradbury', 'Philip K. Dick', 'B', 'Litterature', 'moyen'),
(62, 'Quel auteur francais a ecrit Germinal ?', 'Victor Hugo', 'Emile Zola', 'Gustave Flaubert', 'Stendhal', 'B', 'Litterature', 'difficile'),
(63, 'Qui a ecrit Don Quichotte ?', 'Miguel de Cervantes', 'Federico Garcia Lorca', 'Pablo Neruda', 'Gabriel Garcia Marquez', 'A', 'Litterature', 'difficile'),
(64, 'Quel est le nom de famille du personnage Sherlock dans les romans de Conan Doyle ?', 'Watson', 'Holmes', 'Moriarty', 'Lestrade', 'B', 'Litterature', 'facile'),
(65, 'Quel ecrivain a ecrit Notre-Dame de Paris ?', 'Victor Hugo', 'Alexandre Dumas', 'Emile Zola', 'Stendhal', 'A', 'Litterature', 'moyen'),
(66, 'Quel est l element chimique dont le symbole est O ?', 'Or', 'Oxygene', 'Osmium', 'Argent', 'B', 'Sciences', 'facile'),
(67, 'Quelle planete est la plus proche du Soleil ?', 'Venus', 'Mercure', 'Mars', 'Terre', 'B', 'Sciences', 'facile'),
(68, 'Combien y a-t-il de planetes dans le systeme solaire ?', '7', '8', '9', '10', 'B', 'Sciences', 'facile'),
(69, 'Quel est le symbole chimique de l eau ?', 'O2', 'H2O', 'CO2', 'HO2', 'B', 'Sciences', 'facile'),
(70, 'Quelle est la vitesse de la lumiere en km/s (approximativement) ?', '150 000', '300 000', '450 000', '600 000', 'B', 'Sciences', 'difficile'),
(71, 'Quel organe pompe le sang dans le corps humain ?', 'Le foie', 'Le coeur', 'Les poumons', 'Le rein', 'B', 'Sciences', 'facile'),
(72, 'Quel gaz les plantes absorbent-elles pour la photosynthese ?', 'Oxygene', 'Azote', 'Dioxyde de carbone', 'Hydrogene', 'C', 'Sciences', 'moyen'),
(73, 'Quelle est l unite de mesure de la force ?', 'Watt', 'Newton', 'Joule', 'Pascal', 'B', 'Sciences', 'moyen'),
(74, 'Combien d os y a-t-il dans le corps humain adulte ?', '186', '206', '226', '246', 'B', 'Sciences', 'difficile'),
(75, 'Quelle est la formule chimique du sel de table ?', 'NaCl', 'KCl', 'CaCl2', 'NaOH', 'A', 'Sciences', 'moyen'),
(76, 'Qui a developpe la theorie de la relativite ?', 'Isaac Newton', 'Albert Einstein', 'Niels Bohr', 'Galileo Galilei', 'B', 'Sciences', 'moyen'),
(77, 'Quel est le plus grand organe du corps humain ?', 'Le foie', 'Le cerveau', 'La peau', 'Le coeur', 'C', 'Sciences', 'moyen'),
(78, 'Combien de chromosomes possede un etre humain ?', '23', '46', '44', '48', 'B', 'Sciences', 'difficile'),
(79, 'Quelle force maintient les planetes en orbite autour du Soleil ?', 'Magnetisme', 'Gravite', 'Friction', 'Pression', 'B', 'Sciences', 'facile'),
(80, 'Quel scientifique a formule la theorie de l evolution ?', 'Charles Darwin', 'Gregor Mendel', 'Louis Pasteur', 'Isaac Newton', 'A', 'Sciences', 'moyen'),
(81, 'En quelle annee a eu lieu la Revolution francaise ?', '1789', '1799', '1804', '1815', 'A', 'Histoire', 'moyen'),
(82, 'Qui etait le premier president des Etats-Unis ?', 'Thomas Jefferson', 'George Washington', 'Abraham Lincoln', 'John Adams', 'B', 'Histoire', 'moyen'),
(83, 'En quelle annee a debute la Seconde Guerre mondiale ?', '1937', '1939', '1941', '1945', 'B', 'Histoire', 'moyen'),
(84, 'Qui etait l empereur des Francais sacre en 1804 ?', 'Louis XVI', 'Napoleon Bonaparte', 'Charles de Gaulle', 'Louis XIV', 'B', 'Histoire', 'moyen'),
(85, 'En quelle annee le mur de Berlin est-il tombe ?', '1985', '1987', '1989', '1991', 'C', 'Histoire', 'moyen'),
(86, 'Quelle civilisation a construit les pyramides de Gizeh ?', 'Les Romains', 'Les Grecs', 'Les Egyptiens', 'Les Mayas', 'C', 'Histoire', 'facile'),
(87, 'Qui a decouvert l Amerique en 1492 ?', 'Vasco de Gama', 'Christophe Colomb', 'Magellan', 'Marco Polo', 'B', 'Histoire', 'facile'),
(88, 'Quelle guerre a oppose le Nord et le Sud des Etats-Unis ?', 'Guerre d Independance', 'Guerre de Secession', 'Guerre du Vietnam', 'Guerre de 1812', 'B', 'Histoire', 'moyen'),
(89, 'Quel evenement a declenche la Premiere Guerre mondiale ?', 'L assassinat de l archiduc Francois-Ferdinand', 'L invasion de la Pologne', 'La crise de 1929', 'La revolution russe', 'A', 'Histoire', 'difficile'),
(90, 'Qui etait Cleopatre ?', 'Une reine d Egypte', 'Une reine de France', 'Une deesse grecque', 'Une imperatrice romaine', 'A', 'Histoire', 'facile'),
(91, 'Quel est le plus grand mammifere du monde ?', 'Elephant', 'Baleine bleue', 'Girafe', 'Rhinoceros', 'B', 'Animaux', 'facile'),
(92, 'Quel animal est connu comme le roi de la jungle ?', 'Tigre', 'Lion', 'Elephant', 'Gorille', 'B', 'Animaux', 'facile'),
(93, 'Combien de pattes a une araignee ?', '6', '8', '10', '12', 'B', 'Animaux', 'facile'),
(94, 'Quel est l animal le plus rapide du monde ?', 'Lion', 'Guepard', 'Antilope', 'Aigle', 'B', 'Animaux', 'moyen'),
(95, 'Quel oiseau ne peut pas voler mais court tres vite ?', 'Pingouin', 'Autruche', 'Pelican', 'Flamant rose', 'B', 'Animaux', 'facile'),
(96, 'Quel animal change de couleur pour se camoufler ?', 'Cameleon', 'Lezard', 'Grenouille', 'Iguane', 'A', 'Animaux', 'facile'),
(97, 'Quel est le plus grand reptile vivant ?', 'Crocodile marin', 'Anaconda', 'Komodo', 'Tortue luth', 'A', 'Animaux', 'difficile'),
(98, 'Combien de coeurs a une pieuvre ?', '1', '2', '3', '4', 'C', 'Animaux', 'difficile'),
(99, 'Quel mammifere marin est connu pour son intelligence et ses clics sonores ?', 'Phoque', 'Dauphin', 'Otarie', 'Loutre de mer', 'B', 'Animaux', 'moyen'),
(100, 'Quel animal hiberne pendant l hiver ?', 'Loup', 'Ours', 'Renard', 'Lynx', 'B', 'Animaux', 'facile'),
(101, 'Quel sport se joue avec une raquette et un volant ?', 'Tennis', 'Badminton', 'Squash', 'Tennis de table', 'B', 'Sport', 'facile'),
(102, 'Combien de joueurs compte une equipe de football sur le terrain ?', '9', '10', '11', '12', 'C', 'Sport', 'facile'),
(103, 'Tous les combien d annees ont lieu les Jeux Olympiques d ete ?', '2', '3', '4', '5', 'C', 'Sport', 'facile'),
(104, 'Quel pays a remporte la Coupe du Monde de football 2018 ?', 'Allemagne', 'Bresil', 'France', 'Croatie', 'C', 'Sport', 'moyen'),
(105, 'Dans quel sport utilise-t-on un club et une balle blanche ?', 'Hockey', 'Golf', 'Cricket', 'Baseball', 'B', 'Sport', 'facile'),
(106, 'Combien de points vaut un panier a 3 points en basketball ?', '2', '3', '4', '5', 'B', 'Sport', 'facile'),
(107, 'Quel evenement cycliste est tres connu en France ?', 'Vuelta', 'Giro', 'Tour de France', 'Paris-Roubaix', 'C', 'Sport', 'facile'),
(108, 'Dans quel sport peut-on marquer un essai ?', 'Football', 'Rugby', 'Basketball', 'Handball', 'B', 'Sport', 'facile'),
(109, 'Quelle nage est la plus rapide en natation ?', 'Brasse', 'Dos', 'Papillon', 'Crawl', 'D', 'Sport', 'moyen'),
(110, 'Combien de sets faut-il gagner pour remporter un match de tennis (best of 5) ?', '2', '3', '4', '5', 'B', 'Sport', 'moyen'),
(111, 'Quel instrument de musique a 88 touches ?', 'Guitare', 'Piano', 'Violon', 'Harpe', 'B', 'Musique', 'facile'),
(112, 'Quel compositeur a ecrit la 9eme Symphonie malgre sa surdite ?', 'Mozart', 'Beethoven', 'Bach', 'Chopin', 'B', 'Musique', 'moyen'),
(113, 'Combien de cordes a une guitare classique ?', '4', '5', '6', '7', 'C', 'Musique', 'facile'),
(114, 'Quel groupe a chante Bohemian Rhapsody ?', 'The Beatles', 'Queen', 'Pink Floyd', 'Led Zeppelin', 'B', 'Musique', 'moyen'),
(115, 'Quel genre musical est ne a la Nouvelle-Orleans ?', 'Rock', 'Jazz', 'Reggae', 'Blues', 'B', 'Musique', 'moyen'),
(116, 'Quelle est la monnaie utilisee au Japon ?', 'Yuan', 'Won', 'Yen', 'Ringgit', 'C', 'Culture generale', 'facile'),
(117, 'Quel est le plat national de l Italie souvent confondu avec un dessert ?', 'Pizza', 'Tiramisu', 'Pates', 'Risotto', 'C', 'Culture generale', 'moyen'),
(118, 'Quelle langue est la plus parlee au monde en nombre de locuteurs natifs ?', 'Anglais', 'Espagnol', 'Mandarin', 'Hindi', 'C', 'Culture generale', 'moyen'),
(119, 'Combien de jours y a-t-il dans une annee bissextile ?', '364', '365', '366', '367', 'C', 'Culture generale', 'facile'),
(120, 'Quel pays est connu comme le pays du Soleil Levant ?', 'Chine', 'Coree du Sud', 'Japon', 'Thailande', 'C', 'Culture generale', 'facile');

-- --------------------------------------------------------

--
-- Structure de la table `reponses_utilisateur`
--

CREATE TABLE `reponses_utilisateur` (
  `id` int(11) NOT NULL,
  `tentative_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `reponse_donnee` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_correcte` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tentatives`
--

CREATE TABLE `tentatives` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_tentative` datetime DEFAULT CURRENT_TIMESTAMP,
  `score` int(11) DEFAULT '0',
  `total_questions` int(11) DEFAULT '0',
  `statut` enum('en_cours','termine','abandonne') COLLATE utf8mb4_unicode_ci DEFAULT 'en_cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('connecte','deconnecte') COLLATE utf8mb4_unicode_ci DEFAULT 'deconnecte',
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `connexions`
--
ALTER TABLE `connexions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `deconnexions`
--
ALTER TABLE `deconnexions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reponses_utilisateur`
--
ALTER TABLE `reponses_utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tentative_id` (`tentative_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Index pour la table `tentatives`
--
ALTER TABLE `tentatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `connexions`
--
ALTER TABLE `connexions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deconnexions`
--
ALTER TABLE `deconnexions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT pour la table `reponses_utilisateur`
--
ALTER TABLE `reponses_utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tentatives`
--
ALTER TABLE `tentatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `connexions`
--
ALTER TABLE `connexions`
  ADD CONSTRAINT `connexions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `deconnexions`
--
ALTER TABLE `deconnexions`
  ADD CONSTRAINT `deconnexions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponses_utilisateur`
--
ALTER TABLE `reponses_utilisateur`
  ADD CONSTRAINT `reponses_utilisateur_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponses_utilisateur_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tentatives`
--
ALTER TABLE `tentatives`
  ADD CONSTRAINT `tentatives_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

-- =========================================================
-- COMPLÉMENTS (ajoutés par l'équipe — Personne A)
-- Tout ce qui suit complète la base ci-dessus sans modifier
-- aucune des tables/colonnes déjà créées par C.
-- =========================================================

-- 1. Rôle utilisateur (indispensable pour l'admin — absent de la structure initiale)
ALTER TABLE `utilisateurs`
  ADD COLUMN `role` ENUM('etudiant','admin') NOT NULL DEFAULT 'etudiant';

-- 2. Paramètres de l'application (durée du QCM configurable par l'admin)
CREATE TABLE `parametres` (
  `id` int(11) NOT NULL,
  `duree_qcm_minutes` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `parametres`
  ADD PRIMARY KEY (`id`);

INSERT INTO `parametres` (`id`, `duree_qcm_minutes`) VALUES (1, 10);

-- 2b. Table des incidents anti-triche (manquante dans ce dump précis,
--     mais nécessaire pour admin_triche.php et enregistrer_incident.php)
CREATE TABLE `incidents_triche` (
  `id` int(11) NOT NULL,
  `tentative_id` int(11) NOT NULL,
  `type_incident` enum('changement_onglet','perte_focus','copier_coller','clic_droit','temps_depasse','multi_session') NOT NULL,
  `date_incident` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `incidents_triche`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tentative_id` (`tentative_id`);

ALTER TABLE `incidents_triche`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `incidents_triche`
  ADD CONSTRAINT `incidents_triche_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives` (`id`) ON DELETE CASCADE;

-- 3. Note décimale possible (ex: 13.5/20) — la colonne était en INT à l'origine
ALTER TABLE `tentatives` MODIFY `score` FLOAT DEFAULT 0;

-- 4. Compte administrateur de test
--    email    : admin@quiz.fr
--    password : admin123 (hash bcrypt ci-dessous, compatible password_verify() en PHP)
INSERT INTO `utilisateurs` (`nom`, `prenom`, `email`, `mot_de_passe`, `role`, `statut`)
VALUES (
  'Professeur', 'AntiTriche', 'admin@quiz.fr',
  '$2b$12$wifsP05r2tMCaAPJVTIbK.U2/JmHR6fI436TfocXL646EWIZmv1AO',
  'admin', 'deconnecte'
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
