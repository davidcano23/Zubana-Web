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
  `tenant_id` int NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apartamento`
--

LOCK TABLES `apartamento` WRITE;
/*!40000 ALTER TABLE `apartamento` DISABLE KEYS */;
INSERT INTO `apartamento` VALUES (26,1,'',850000000,'La Ceja, Antioquia','Vereda los Olivos Propiedad #15','38a37b60eebe413cfb5937f71351bfea.webp','Daniel Gonzales','3054154879','Colegaje','',125,4,3,'No','Si','Si','Si',4,'Si','abierta','Apartamento','No','Si','Si','Si','Si','Si','Si','apartamento','Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba   Apartamento de prueba','N/A',250000,'San Antonio de Pereira, Rionegro, Antioquia','La Cumbre',6.15510000,-75.37370000,'Si','Si','N/A','2026-06-29 21:08:14'),(27,1,'',850000000,'El Carmen de Viboral, Antioquia','Vereda los Olivos Propiedad #15','852e7c96f98ac5c355e068cd38ab5dc3.webp','Antonio Lopez','3054157278','Directo','',125,2,2,'Si','Si','Si','Si',2,'No','abierta','Apartamento','Si','Si','Si','Si','Si','Si','Si','apartamento','Prueba Apartamento   Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento  Prueba Apartamento','Vereda Sonadora, El Canada, El Carmen de Viboral, Antioquia',250000,'San Antonio de Pereira, Rionegro, Antioquia','La Cumbre',6.13014342,-75.38286209,'Si','Si','N/A','2026-06-30 23:16:11');
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
  `tenant_id` int NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `casa`
--

LOCK TABLES `casa` WRITE;
/*!40000 ALTER TABLE `casa` DISABLE KEYS */;
INSERT INTO `casa` VALUES (61,1,2000,5,'Si','Si',5,'','35864b8b87540eccca116fe92c6227b9.webp',600000000,'El Carmen de Viboral, Antioquia','calle 55 #23 - 34','Casa Campestre','David Cano','3004641524','','Directo',220,6,'Si','Si','abierta','Si','Si','Si','Si','Si','Si','Si','casa','Casa de Prueba   Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba  Casa de Prueba','Vereda Sonadora, El Canada, El Carmen de Viboral, Antioquia',250000,'El Canada, El Carmen de Viboral, Antioquia','comando el porvenir',6.08057769,-75.33602872,'Si','Si','https://www.youtube.com/watch?v=nfPPgK5ftz8','2026-06-29 11:08:14'),(64,1,120,5,'No','No',4,'','fa9ea5efc010fbffbeb658130e4f39b6.webp',995000000,'Rionegro, Antioquia','N/A','Finca','Alveiro Tabares','3206110254','','Colegaje',150,5,'No','No','cerrada','No','Si','Si','No','No','No','No','finca','Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia   Finca Prueba Rionegro Antioquia','N/A',150000,'San Antonio de Pereira, Rionegro, Antioquia','N/A',6.15659813,-75.39779663,'No','No','N/A','2026-06-30 23:19:16');
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
  `tenant_id` int NOT NULL,
  `casa_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_casa` (`casa_id`),
  KEY `idx_tenant` (`tenant_id`),
  CONSTRAINT `fk_imagenes_propiedad_casa` FOREIGN KEY (`casa_id`) REFERENCES `casa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad`
--

LOCK TABLES `imagenes_propiedad` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad` VALUES (382,1,61,'4ae4037dacf3c9f454b9c53a561762af.webp'),(383,1,61,'2dc8339819f0d3f5902bb88d35c0fc61.webp'),(384,1,61,'ae6d5dd17440f2ea18ba142b7d62e8d5.webp'),(385,1,61,'2e4b71b41bcb87bd8a9ccdc16772eaa8.webp'),(386,1,61,'620a06f1a9f368352ee9ee41a9c03516.webp'),(387,1,61,'4ede5562cd7037f349dfc542b217c742.webp'),(388,1,61,'3a0bc290903d70d822400e28286c37e0.webp'),(389,1,61,'00d63111f5957a4660120f644feec188.webp'),(390,1,61,'f592c9b9d08a2ed7abdcb975427c7926.webp'),(391,1,61,'1214b8d0dcadef840440cd391494a5de.webp'),(392,1,61,'28448364e158223443cc629495ac6a6a.webp'),(393,1,61,'31362fb02a06577a3c111660fc125fcf.webp'),(394,1,61,'a7529e46626dba4aed1e76bd0b0fabe6.webp'),(395,1,61,'d36ea15f3c28efd6cc49577f326aa767.webp'),(396,1,61,'1b165bded75ca4d1d95f3244e8245d3d.webp'),(397,1,61,'addb8c20e83d2260e7130d8b197b6adf.webp'),(398,1,64,'84dea509a771b1236f1a463a937e110f.webp'),(399,1,64,'6aac0928d201296cd5032c734ca5b303.webp'),(400,1,64,'30cee7c623531b019e169aa92237d3ac.webp'),(401,1,64,'9a6b832c6125a29ff3ce6733e352be9b.webp'),(402,1,64,'bd957fa9de87a5da65656b5a699fc9ad.webp'),(403,1,64,'1e21455b4b1dc5f5a85d95af6cfee682.webp'),(404,1,64,'42574ba10c55cf91beb58c8e77029054.webp'),(405,1,64,'6cce51a67dfc318ed5bc48673f64dcb2.webp'),(406,1,64,'ef2374299aaeef7362734a25cebf811a.webp'),(407,1,64,'c81e0ee7bb45a7db26a2f3aea1e2ecd7.webp'),(408,1,64,'3b01b1a8f3443c6b9ba356d876bc9be6.webp'),(409,1,64,'31698dcbb3cdec3e0afc6fdea37b75f7.webp'),(410,1,64,'ae35f8f583ec242aa41a8fd4237f1602.webp'),(411,1,64,'811e66551df818be779ae6a38bedf3f9.webp'),(412,1,64,'37d7b6632e6dd2ec477c52b33ce03f56.webp');
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
  `tenant_id` int NOT NULL,
  `apartamento_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_apartamento` (`apartamento_id`),
  KEY `idx_tenant` (`tenant_id`),
  CONSTRAINT `fk_imagenes_propiedad_apartamento` FOREIGN KEY (`apartamento_id`) REFERENCES `apartamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad_apartamento`
--

LOCK TABLES `imagenes_propiedad_apartamento` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad_apartamento` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad_apartamento` VALUES (182,1,26,'0229b3cdbd4d0441a1cc7e79c21e36a2.webp'),(183,1,26,'dc35cbae7f79978beae962ceeb6f8060.webp'),(184,1,26,'f799b854a088175fa03900167ba42d10.webp'),(185,1,26,'76957cb64736488fa5d2145f2788968f.webp'),(186,1,26,'85fcafe6f50b73b0dc091e3181fb53d0.webp'),(187,1,26,'1fda659137153055ffbd1c850fe78d73.webp'),(188,1,27,'9d267622aa5bcec185c44516f8d62ad4.webp'),(189,1,27,'0dead1c9a80e9906d8b2eb82f4a36464.webp'),(190,1,27,'4c29afe2276cb59f8337b1f179e1e23c.webp'),(191,1,27,'3b9dc78ff25dc0183f37cb383474d53a.webp'),(192,1,27,'a9039219b672ca1cde0a40c23daf25f7.webp'),(193,1,27,'da89495e0a8893d031ae280baf76abf2.webp'),(194,1,27,'b049c0e0bb81ec65c895f7a724a9673c.webp'),(195,1,27,'ac0042618a4e1fdd237d14d43eda2199.webp'),(196,1,27,'12a47ceb230595a1368f002d67ba6615.webp'),(197,1,27,'01de00b0c60e550631334ead446ae605.webp'),(198,1,27,'34be3d5f84b43dfe538dabfd4ab32e97.webp');
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
  `tenant_id` int NOT NULL,
  `local_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_local` (`local_id`),
  KEY `idx_tenant` (`tenant_id`),
  CONSTRAINT `fk_imagenes_propiedad_local` FOREIGN KEY (`local_id`) REFERENCES `local` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes_propiedad_local`
--

LOCK TABLES `imagenes_propiedad_local` WRITE;
/*!40000 ALTER TABLE `imagenes_propiedad_local` DISABLE KEYS */;
INSERT INTO `imagenes_propiedad_local` VALUES (51,1,11,'551ac16abf483e8b13ff1a94e331310d.webp'),(52,1,11,'e9a2d11592785277b7e8e2f2b39e2474.webp'),(53,1,11,'9d153ef9fa9a52e0438206c0971b9494.webp'),(54,1,11,'cc5e362cd743582af60181606463ff82.webp'),(55,1,11,'eaa019c8bd41fe6a751607bfcfa2627d.webp'),(56,1,11,'252e1c3b6db79f854121714c6fb83557.webp'),(57,1,11,'4862119118a4202a9e060f1587c6eabd.webp');
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
  `tenant_id` int NOT NULL,
  `lotes_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_imagenes_propiedad_lotes` (`lotes_id`),
  KEY `idx_tenant` (`tenant_id`),
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
  `tenant_id` int NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `local`
--

LOCK TABLES `local` WRITE;
/*!40000 ALTER TABLE `local` DISABLE KEYS */;
INSERT INTO `local` VALUES (11,1,'',950000000,'Rionegro, Antioquia','Vereda los palitos','5419aebf808a7e39903cfcdcdb8f0bd9.webp','Antonio Lopez','3054157278','Colegaje','',125,220,3,4,'Local','centro comercial','local','Local de Prueba Local de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de PruebaLocal de Prueba','N/A',150000,'San Antonio de Pereira, Rionegro, Antioquia','cacha de tejo manuel trujillo',6.15510000,-75.37370000,'https://www.youtube.com/watch?v=nfPPgK5ftz8','2026-06-29 21:17:36');
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
  `tenant_id` int NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tenant` (`tenant_id`)
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
  `tenant_id` int NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` char(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tenant` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (2,1,'correo@correo.com','$2y$10$f85r901eSVL72F7pn2rNbO106Ed7pt8ux7OBcvUZNM6Ir0h53zcSW');
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

-- Dump completed on 2026-06-30 18:35:39
