-- MySQL dump 10.13  Distrib 8.0.29, for Win64 (x86_64)
--
-- Host: localhost    Database: datasenn_db
-- ------------------------------------------------------
-- Server version	8.3.0

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
  `rol_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_documento` (`numero_documento`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`),
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
-- Table structure for table `diagnostico_empresarial`
--

DROP TABLE IF EXISTS `diagnostico_empresarial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_empresarial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa` varchar(150) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `tamano` varchar(50) NOT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `empleados` int NOT NULL,
  `contrataciones` int NOT NULL,
  `contrato_frecuente` varchar(50) NOT NULL,
  `tiene_proceso` varchar(10) NOT NULL,
  `perfiles_definidos` varchar(10) NOT NULL,
  `publicacion` varchar(50) NOT NULL,
  `aprendices` varchar(10) NOT NULL,
  `programa_apoyo` varchar(10) NOT NULL,
  `perfiles_necesarios` varchar(255) NOT NULL,
  `infraestructura` varchar(10) NOT NULL,
  `apoyo_seleccion` varchar(10) NOT NULL,
  `beneficios` varchar(10) NOT NULL,
  `fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico_empresarial`
--

LOCK TABLES `diagnostico_empresarial` WRITE;
/*!40000 ALTER TABLE `diagnostico_empresarial` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico_empresarial` ENABLE KEYS */;
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
  `rol_id` int DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_documento` (`numero_documento`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`),
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
-- Table structure for table `programas`
--

DROP TABLE IF EXISTS `programas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_programa` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `codigo_programa` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nivel_formacion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_programa` (`codigo_programa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programas`
--

LOCK TABLES `programas` WRITE;
/*!40000 ALTER TABLE `programas` DISABLE KEYS */;
/*!40000 ALTER TABLE `programas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_identidad` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_documento` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `numero_identidad` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `residencia` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_sangre` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_identidad` (`numero_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'alejo','cc','123','123','AB-','f@gmail.com','123','[HASH]','activo','2025-05-22 07:36:41'),(2,'seoop','cc','12345','123','AB-','fe@gmail.com','123','[HASH]','activo','2025-05-22 07:40:15'),(3,'qw','cc','12345674','123','O+','fe@gmail.com','123','[HASH]','inactivo','2025-05-22 07:43:14'),(4,'asd','ti','789456','1233rr','A-','rer4@gmail.com','1234455','[HASH]','inactivo','2025-05-22 07:44:46'),(5,'asd','ti','78945633','1233rr','A-','rer4@gmail.com','1234455','[HASH]','inactivo','2025-05-22 07:46:57'),(6,'sdjk','cc','211','pl','O+','leyvadilan36@gmail.com','3113350701','$2y$10$UxhNkO9ygeucjJx1L7QYqOYYafd17Zkwy4x1jmNloS.51IY19U2Ne','activo','2025-05-22 19:54:35'),(7,'yilet','cc','1111','1111','O+','yilet@gmail.com','123','$2y$10$6yUzzFcnmf5pUelqrrVq2OAHPee/IRtFIf053CkrL77NPZVLVLVnu','activo','2025-05-22 23:05:22'),(8,'valentina z','cc','4352','casa alejo','O+','valentian@gmail.com','31235552','$2y$10$uCTV5N/8WVz4QIlMfP3CCOk0Rx3WN7K5MJxTBJ.bsDuPEThCgNbTK','activo','2025-05-22 23:08:46'),(9,'JUAN JOSE','cc','0808','rololandia','O+','leyva@ghh','3113350701','$2y$10$kwgrHju0t9BX/HHiaw1e..l9YzFU65aTRWlqmMwIru775jTbfqCgG','activo','2025-05-27 20:43:49'),(10,'sofia','cc','4567','PEREIRA','AB-','sofia@gmail.com','3108980485','$2y$10$z4rYz5lqwx3s68HvnuDbq.L8.EVlXFPdwBzU3JCVPQC/wH1IxQtTC','inactivo','2025-05-28 00:56:03');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-03 17:21:26
