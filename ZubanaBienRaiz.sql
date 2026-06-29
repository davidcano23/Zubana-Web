-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: zubanabienraiz
-- ------------------------------------------------------
-- Server version	8.0.42

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casa`
--

LOCK TABLES `casa` WRITE;
/*!40000 ALTER TABLE `casa` DISABLE KEYS */;
INSERT INTO `casa` VALUES (61,2000,5,'Si','Si',5,'','35864b8b87540eccca116fe92c6227b9.webp',600000000,'El Carmen de Viboral, Antioquia','calle 55 #23 - 34','Casa Campestre','David Cano','3004641524','','Directo',220,6,'Si','Si','abierta','Si','Si','Si','Si','Si','Si','Si','casa','Casa de Prueba   Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba','Vereda Sonadora, El Canada, El Carmen de Viboral, Antioquia',250000,'El Canada, El Carmen de Viboral, Antioquia','comando el porvenir',6.08057769,-75.33602872,'Si','Si','https://www.youtube.com/watch?v=nfPPgK5ftz8');
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
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad`
--

LOCK TABLES `imagenes_propiedad` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad` VALUES (382,61,'4ae4037dacf3c9f454b9c53a561762af.webp'),(383,61,'2dc8339819f0d3f5902bb88d35c0fc61.webp'),(384,61,'ae6d5dd17440f2ea18ba142b7d62e8d5.webp'),(385,61,'2e4b71b41bcb87bd8a9ccdc16772eaa8.webp'),(386,61,'620a06f1a9f368352ee9ee41a9c03516.webp'),(387,61,'4ede5562cd7037f349dfc542b217c742.webp'),(388,61,'3a0bc290903d70d822400e28286c37e0.webp'),(389,61,'00d63111f5957a4660120f644feec188.webp'),(390,61,'f592c9b9d08a2ed7abdcb975427c7926.webp'),(391,61,'1214b8d0dcadef840440cd391494a5de.webp'),(392,61,'28448364e158223443cc629495ac6a6a.webp'),(393,61,'31362fb02a06577a3c111660fc125fcf.webp'),(394,61,'a7529e46626dba4aed1e76bd0b0fabe6.webp'),(395,61,'d36ea15f3c28efd6cc49577f326aa767.webp'),(396,61,'1b165bded75ca4d1d95f3244e8245d3d.webp'),(397,61,'addb8c20e83d2260e7130d8b197b6adf.webp');
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
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `subdominio` varchar(63) NOT NULL,
  `estado` enum('activo','suspendido','prueba') NOT NULL DEFAULT 'prueba',
  `plan_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdominio` (`subdominio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Zubana BienRaíz','zubana','activo',NULL,'2026-06-18 17:32:18');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `theme_colors`
--

DROP TABLE IF EXISTS `theme_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme_colors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `color_uno` varchar(10) DEFAULT NULL,
  `color_dos` varchar(10) DEFAULT NULL,
  `color_tres` varchar(10) DEFAULT NULL,
  `color_cuatro` varchar(10) DEFAULT NULL,
  `color_cinco` varchar(10) DEFAULT NULL,
  `color_seis` varchar(10) DEFAULT NULL,
  `color_siete` varchar(10) DEFAULT NULL,
  `color_ocho` varchar(10) DEFAULT NULL,
  `color_nueve` varchar(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme_colors`
--

LOCK TABLES `theme_colors` WRITE;
/*!40000 ALTER TABLE `theme_colors` DISABLE KEYS */;
INSERT INTO `theme_colors` VALUES (1,'#f27373','#2e8b57','#a4c8d8','#f5f1ea','#2e2e2e','#000000','#ffffff','#ff0000','#a9cbb7','2026-02-24 21:45:35');
/*!40000 ALTER TABLE `theme_colors` ENABLE KEYS */;
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

-- Dump completed on 2026-06-29  0:08:51
