-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: zubanabienraiz
-- ------------------------------------------------------
-- Server version	8.0.44

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
-- Table structure for table `apartamento`
--

DROP TABLE IF EXISTS `apartamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `precio` bigint DEFAULT NULL,
  `ubicacion` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `direccion` varchar(75) DEFAULT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `propietario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contacto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `area_total` bigint DEFAULT NULL,
  `habitaciones` int DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `zona_ropa` varchar(5) DEFAULT NULL,
  `cocina` varchar(5) DEFAULT NULL,
  `sala_comedor` varchar(5) DEFAULT NULL,
  `balcon` varchar(5) DEFAULT NULL,
  `estrato` int DEFAULT NULL,
  `garaje` varchar(5) DEFAULT NULL,
  `tipo_unidad` varchar(25) DEFAULT NULL,
  `tipo` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vigilancia` varchar(5) DEFAULT NULL,
  `zonas_verdes` varchar(5) DEFAULT NULL,
  `juegos` varchar(5) DEFAULT NULL,
  `coworking` varchar(5) DEFAULT NULL,
  `gimnasio` varchar(5) DEFAULT NULL,
  `piscina` varchar(5) DEFAULT NULL,
  `cancha` varchar(5) DEFAULT NULL,
  `actualizacion` varchar(65) DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `barrio` varchar(255) DEFAULT NULL,
  `administracion` bigint DEFAULT NULL,
  `corregimiento` varchar(125) DEFAULT NULL,
  `palabra_clave` varchar(125) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `jacuzzi` varchar(5) DEFAULT NULL,
  `turco` varchar(5) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apartamento`
--

LOCK TABLES `apartamento` WRITE;
/*!40000 ALTER TABLE `apartamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `apartamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `casa`
--

DROP TABLE IF EXISTS `casa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `casa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `area_total` bigint DEFAULT NULL,
  `habitaciones` int DEFAULT NULL,
  `sala` varchar(5) DEFAULT NULL,
  `zona_ropa` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `nombre` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `imagen` varchar(200) DEFAULT NULL,
  `precio` bigint DEFAULT NULL,
  `ubicacion` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `direccion` varchar(60) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `propietario` varchar(100) DEFAULT NULL,
  `contacto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  `area_construida` bigint DEFAULT NULL,
  `estrato` int DEFAULT NULL,
  `cocina` varchar(5) DEFAULT NULL,
  `garaje` varchar(5) DEFAULT NULL,
  `tipo_unidad` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vigilancia` varchar(5) DEFAULT NULL,
  `zonas_verdes` varchar(255) DEFAULT NULL,
  `juegos` varchar(5) DEFAULT NULL,
  `coworking` varchar(5) DEFAULT NULL,
  `gimnasio` varchar(5) DEFAULT NULL,
  `piscina` varchar(5) DEFAULT NULL,
  `cancha` varchar(5) DEFAULT NULL,
  `actualizacion` varchar(65) DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `barrio` varchar(255) DEFAULT NULL,
  `administracion` bigint DEFAULT NULL,
  `corregimiento` varchar(125) DEFAULT NULL,
  `palabra_clave` varchar(125) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `jacuzzi` varchar(5) DEFAULT NULL,
  `turco` varchar(5) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casa`
--

LOCK TABLES `casa` WRITE;
/*!40000 ALTER TABLE `casa` DISABLE KEYS */;
INSERT INTO `casa` VALUES (60,2000,7,'Si','Si',10,'','c539232af5b03ac5578b9136388f3ff8.webp',10500000000,'Rionegro, Antioquia','N/A','Casa Campestre','Carlos Peralta','312 7578505','','Directo',400,4,'Si','Si','cerrada','Si','Si','No','No','No','No','No','casa','Mansión En Unidad Cerrada En Exclusiva Zona De Llanogrande, Siete Alcobas Con Baño, Vestier Y Terraza, Amplia Zona Social Con Piscina Y Cancha De Futbol, Zona De Bar, Dos Cocinas, Sala De Cine, Biblioteca, Ascensor, Aire Acondicionado Central, Cuarto De Juegos, Dos Oficinas, Capilla, Tres Cuartos De Servicio Con Baño Y Closet. Puerta Habitación Blindada, Vidrieras Blindadas, Cuarto Cuartos útiles Y Espacio Para Parqueo De Más De 12 Carros.','N/A',0,'Llanogrande, Rionegro','N/A',0.00000000,0.00000000,'No','No','https://youtu.be/n9SEnUmwXCk?si=r7Ka1tYid9jpPD9a');
/*!40000 ALTER TABLE `casa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL DEFAULT '',
  `telefono` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL DEFAULT '',
  `ciudad` varchar(100) NOT NULL DEFAULT '',
  `presupuesto_min` bigint unsigned NOT NULL DEFAULT '0',
  `presupuesto_max` bigint unsigned NOT NULL DEFAULT '0',
  `tipo_busqueda` enum('Compra','Arriendo','Ambos') NOT NULL DEFAULT 'Compra',
  `tipo_propiedad` varchar(100) NOT NULL DEFAULT '',
  `fuente` enum('Web','WhatsApp','Instagram','Facebook','TikTok','Referido','Llamada','Otro') NOT NULL DEFAULT 'Otro',
  `estado` enum('nuevo','contactado','interesado','negociacion','cerrado','perdido') NOT NULL DEFAULT 'nuevo',
  `notas` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'sebas','p','3147919932','lopezstun123@gmail.com','rionegro',200000,0,'Compra','Casa','WhatsApp','nuevo','','2026-06-27 06:25:25','2026-06-27 06:25:48');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_actividades`
--

DROP TABLE IF EXISTS `crm_actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `crm_actividades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` int unsigned NOT NULL,
  `tipo` enum('nota','llamada','whatsapp','visita','email','otro') NOT NULL DEFAULT 'nota',
  `descripcion` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `crm_actividades_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_actividades`
--

LOCK TABLES `crm_actividades` WRITE;
/*!40000 ALTER TABLE `crm_actividades` DISABLE KEYS */;
/*!40000 ALTER TABLE `crm_actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_propiedad`
--

DROP TABLE IF EXISTS `imagenes_propiedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes_propiedad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `casa_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_casa` (`casa_id`),
  CONSTRAINT `fk_imagenes_propiedad_casa` FOREIGN KEY (`casa_id`) REFERENCES `casa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad`
--

LOCK TABLES `imagenes_propiedad` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad` VALUES (373,60,'d5ac4e9fe1ba3df0d40ad480fa1fc879.webp'),(374,60,'dce5b84fd940b4c9eace6a48fa5d9983.webp'),(375,60,'3b331b75aa6d2a8ac1f2795d4580074a.webp'),(376,60,'d63bc47d6d1f2cbcaef2204ee85b89f2.webp'),(377,60,'ff0d478ebaf40cdf2bc7a8aa7b65b873.webp'),(378,60,'e0bbc471ae8b8011b859c0d51a1329e2.webp'),(379,60,'0ad42a7cb605aea4b18803dc22ff296d.webp'),(380,60,'a1a118e5073fb3423081366c8beca31b.webp'),(381,60,'2675e7c59b33936926a45ad9a4010f57.webp');
/*!40000 ALTER TABLE `imagenes_propiedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_propiedad_apartamento`
--

DROP TABLE IF EXISTS `imagenes_propiedad_apartamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes_propiedad_apartamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apartamento_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_apartamento` (`apartamento_id`),
  CONSTRAINT `fk_imagenes_propiedad_apartamento` FOREIGN KEY (`apartamento_id`) REFERENCES `apartamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad_apartamento`
--

LOCK TABLES `imagenes_propiedad_apartamento` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad_apartamento` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagenes_propiedad_apartamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_propiedad_local`
--

DROP TABLE IF EXISTS `imagenes_propiedad_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes_propiedad_local` (
  `id` int NOT NULL AUTO_INCREMENT,
  `local_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_local` (`local_id`),
  CONSTRAINT `fk_imagenes_propiedad_local` FOREIGN KEY (`local_id`) REFERENCES `local` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad_local`
--

LOCK TABLES `imagenes_propiedad_local` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad_local` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagenes_propiedad_local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes_propiedad_lotes`
--

DROP TABLE IF EXISTS `imagenes_propiedad_lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagenes_propiedad_lotes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lotes_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_lotes` (`lotes_id`),
  CONSTRAINT `fk_imagenes_propiedad_lotes` FOREIGN KEY (`lotes_id`) REFERENCES `lotes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad_lotes`
--

LOCK TABLES `imagenes_propiedad_lotes` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad_lotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagenes_propiedad_lotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `local`
--

DROP TABLE IF EXISTS `local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `local` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `precio` bigint DEFAULT NULL,
  `ubicacion` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `direccion` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `propietario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contacto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `area_total` bigint DEFAULT NULL,
  `area_construida` bigint DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `estrato` int DEFAULT NULL,
  `tipo` varchar(65) DEFAULT NULL,
  `tipo_local` varchar(65) DEFAULT NULL,
  `actualizacion` varchar(65) DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `barrio` varchar(255) DEFAULT NULL,
  `administracion` bigint DEFAULT NULL,
  `corregimiento` varchar(125) DEFAULT NULL,
  `palabra_clave` varchar(125) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `local`
--

LOCK TABLES `local` WRITE;
/*!40000 ALTER TABLE `local` DISABLE KEYS */;
/*!40000 ALTER TABLE `local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `precio` bigint DEFAULT NULL,
  `ubicacion` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `direccion` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `propietario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contacto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modalidad` varchar(25) DEFAULT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `area_total` bigint DEFAULT NULL,
  `estrato` int DEFAULT NULL,
  `tipo_unidad` varchar(65) DEFAULT NULL,
  `tipo` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `actualizacion` varchar(65) DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `barrio` varchar(255) DEFAULT NULL,
  `administracion` bigint DEFAULT NULL,
  `corregimiento` varchar(125) DEFAULT NULL,
  `palabra_clave` varchar(125) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes`
--

LOCK TABLES `lotes` WRITE;
/*!40000 ALTER TABLE `lotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(60) DEFAULT NULL,
  `password` char(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (2,'correo@correo.com','$2y$10$f85r901eSVL72F7pn2rNbO106Ed7pt8ux7OBcvUZNM6Ir0h53zcSW');
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

-- Dump completed on 2026-06-28 23:10:10
