-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: tableCreator
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `tableCreator`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `tableCreator` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `tableCreator`;

--
-- Table structure for table `Linux`
--

DROP TABLE IF EXISTS `Linux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Linux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Command` text DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `#tag` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Linux`
--

LOCK TABLES `Linux` WRITE;
/*!40000 ALTER TABLE `Linux` DISABLE KEYS */;
INSERT INTO `Linux` VALUES (1,'ps aux','Show running processes','processes');
/*!40000 ALTER TABLE `Linux` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Vim`
--

DROP TABLE IF EXISTS `Vim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Vim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Command` text DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `#tag` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Vim`
--

LOCK TABLES `Vim` WRITE;
/*!40000 ALTER TABLE `Vim` DISABLE KEYS */;
INSERT INTO `Vim` VALUES 
  (1,':h[elp] keyword','open help for keyword','global'),
  (2, ':sav[eas] file', 'save file as', 'global'),
  (3, ':clo[se]', 'close current pane', 'global'),
  (4, ':ter[minal]', 'open a terminal window', 'global'),
  (5, 'K', 'open man page for word under the cursor', 'global'),
  (6, 'h', 'move cursor left', 'cursor movement'),
  (7, 'j', 'move cursor down', 'cursor movement'),
  (8, 'k', 'move cursor up', 'cursor movement'),
  (9, 'l', 'move cursor right', 'cursor movement'),
  (10, 'gj', 'move cursor down (multi-line text)', 'cursor movement'),
  (11, 'gk', 'move cursor up (multi-line text)', 'cursor movement'),
  (12, 'H', 'move to top of screen', 'cursor movement'),
  (13, 'M', 'move to middle of screen', 'cursor movement'),
  (14, 'L', 'move to bottom of screen', 'cursor movement'),
  (15, 'w', 'jump forwards to the start of a word', 'cursor movement'),
  (16, 'W', 'jump forwards to the start of a word (words can contain punctuation)', 'cursor movement'),
  (17, 'e', 'jump forwards to the end of a word', 'cursor movement'),
  (18, 'E', 'jump forwards to the end of a word (words can contain punctuation)', 'cursor movement'),
  (19, 'b', 'jump backwards to the start of a word', 'cursor movement'),
  (20, 'B', 'jump backwards to the start of a word (words can contain punctuation)', 'cursor movement'),
  (21, '0', 'jump to the start of the line', 'cursor movement'),
  (22, '^', 'jump to the first non-blank character of the line', 'cursor movement'),
  (23, '$', 'jump to the end of the line', 'cursor movement'),
  (24, 'g_', 'jump to the last non-blank character of the line', 'cursor movement'),
  (25, 'gg', 'go to the first line of the document', 'cursor movement'),
  (26, 'G', 'go to the last line of the document', 'cursor movement'),
  (27, '5G', 'go to line 5', 'cursor movement'),
  (28, 'fx', 'jump to the next occurrence of character x', 'cursor movement'),
  (29, 'tx', 'jump to before the next occurrence of character x', 'cursor movement'),
  (30, 'Fx', 'jump to the previous occurrence of character x', 'cursor movement'),
  (31, 'Tx', 'jump to after the previous occurrence of character x', 'cursor movement'),
  (32, ';', 'repeat the previous f, t, F or T movement', 'cursor movement'),
  (33, ',', 'repeat the previous f, t, F or T movement, backwards', 'cursor movement'),
  (34, '%', 'move to matching character (default supported pairs: (), {}, [])', 'cursor movement'),
  (35, ':s[ubstitute]/pattern/replacement/', 'replace pattern with replacement on current line', 'search and replace'),
  (36, ':s[ubstitute]/pattern/replacement/g', 'replace all occurrences of pattern with replacement on current line', 'search and replace'),
  (37, ':%s[ubstitute]/pattern/replacement/g', 'replace all occurrences of pattern with replacement in the file', 'search and replace'),
  (38, ':%s[ubstitute]/pattern/replacement/gc', 'replace all occurrences with confirmation', 'search and replace');
/*!40000 ALTER TABLE `Vim` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataset`
--

DROP TABLE IF EXISTS `dataset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dataset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dataset`
--

LOCK TABLES `dataset` WRITE;
/*!40000 ALTER TABLE `dataset` DISABLE KEYS */;
INSERT INTO `dataset` VALUES (1,'Vim'),(2,'Linux');
/*!40000 ALTER TABLE `dataset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datasetAttribute`
--

DROP TABLE IF EXISTS `datasetAttribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datasetAttribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datasetId` int(11) NOT NULL,
  `attributeName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datasetId` (`datasetId`),
  CONSTRAINT `datasetAttribute_ibfk_1` FOREIGN KEY (`datasetId`) REFERENCES `dataset` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datasetAttribute`
--

LOCK TABLES `datasetAttribute` WRITE;
/*!40000 ALTER TABLE `datasetAttribute` DISABLE KEYS */;
INSERT INTO `datasetAttribute` VALUES (2,1,'Command'),(3,1,'Description'),(4,1,'#tag'),(5,2,'Command'),(6,2,'Description'),(7,2,'#tag');
/*!40000 ALTER TABLE `datasetAttribute` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-05  9:00:50
