-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: datasenn_db
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_documento` enum('CC','TI','CE','Otro') COLLATE utf8mb4_general_ci NOT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `correo_electronico` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_documento` enum('NIT') COLLATE utf8mb4_general_ci NOT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `numero_telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `correo_electronico` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `confirmacion_correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `actividad_economica` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programa_formacion`
--

DROP TABLE IF EXISTS `programa_formacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programa_formacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_programa` enum('Tecnico','Tecnologo','Operario') NOT NULL,
  `nombre_programa` varchar(255) NOT NULL,
  `duracion_programa` int NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_programa` (`nombre_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programa_formacion`
--

LOCK TABLES `programa_formacion` WRITE;
/*!40000 ALTER TABLE `programa_formacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `programa_formacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Empresa');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-12 14:09:45
