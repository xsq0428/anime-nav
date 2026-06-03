/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 二次元地址发布系统
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0+deb12u2

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super','admin') DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','super','2026-06-03 15:08:16','2026-06-03 15:29:59');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `copy_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` VALUES
(1,'风信子导航网','https://www.baidu.com/','https://www.baidu.com/','https://www.baidu.com/',1,1,'2026-06-03 15:08:16','2026-06-03 15:44:53'),
(2,'QQ 源码共享交流群','123456789',NULL,'123456789',2,1,'2026-06-03 15:08:16','2026-06-03 15:08:16');
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('bookmark','contact','notice') DEFAULT 'notice',
  `icon` varchar(20) DEFAULT '?',
  `content` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES
(1,'bookmark','📌','请 Ctrl+D 收藏本页到浏览器收藏夹回家不迷路',1,1,'2026-06-03 15:08:16','2026-06-03 15:24:20'),
(2,'contact','💬','若打不开可联系站长薇信:XXX QQ 群:123456789',1,2,'2026-06-03 15:08:16','2026-06-03 15:08:16');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','number') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'site_title','AnimeNav - 二次元网站导航','text','网站标题','2026-06-03 15:38:48'),
(2,'site_subtitle','精选二次元资源 · 永久导航发布','text','网站副标题','2026-06-03 15:38:48'),
(3,'site_logo','https://img2.baidu.com/it/u=1978192862,2048448374&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=500','image','网站 Logo URL','2026-06-03 15:08:16'),
(4,'group_link_text','点击此处加内部群永不失联','text','群组链接按钮文字','2026-06-03 15:08:16'),
(5,'group_link_url','https://www.baidu.com','text','群组链接地址','2026-06-03 15:43:13'),
(6,'permanent_url','https://www.baidu.com','text','永久地址','2026-06-03 15:43:13'),
(7,'footer_text','© 2026 AnimeNav. All Rights Reserved','text','页脚版权信息','2026-06-03 15:38:48');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urls`
--

DROP TABLE IF EXISTS `urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `type` enum('main','backup') DEFAULT 'backup',
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `click_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urls`
--

LOCK TABLES `urls` WRITE;
/*!40000 ALTER TABLE `urls` DISABLE KEYS */;
INSERT INTO `urls` VALUES
(1,'主网址','https://www.baidu.com','main','🌐',1,1,4,'2026-06-03 15:08:16','2026-06-03 15:43:29'),
(2,'备用网址 1','https://www.baidu.com','backup','🔗',2,1,0,'2026-06-03 15:08:16','2026-06-03 15:43:13'),
(3,'备用网址 2','https://www.baidu.com','backup','🔗',3,1,0,'2026-06-03 15:08:16','2026-06-03 15:43:13'),
(4,'备用网址 3','https://www.baidu.com','backup','🔗',4,1,0,'2026-06-03 15:08:16','2026-06-03 15:43:13'),
(5,'备用网址 4','https://www.baidu.com','backup','🔗',5,1,1,'2026-06-03 15:08:16','2026-06-03 15:43:13'),
(6,'备用网址 5','https://www.baidu.com','backup','🔗',6,1,1,'2026-06-03 15:08:16','2026-06-03 15:43:13');
/*!40000 ALTER TABLE `urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visit_logs`
--

DROP TABLE IF EXISTS `visit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `visit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `visited_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `url_id` (`url_id`),
  KEY `visited_at` (`visited_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visit_logs`
--

LOCK TABLES `visit_logs` WRITE;
/*!40000 ALTER TABLE `visit_logs` DISABLE KEYS */;
INSERT INTO `visit_logs` VALUES
(1,6,'211.90.251.252','Mozilla/5.0 (Linux; U; Android 15; zh-cn; RMX3706 Build/AP3A.240617.008) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/115.0.5790.168 Mobile Safari/537.36 HeyTapBrowser/40.10.10.1','https://8080-6a23e20d848739f4.monkeycode-ai.online/','2026-06-03 15:18:12'),
(2,1,'127.0.0.1','curl/7.88.1','','2026-06-03 15:20:34'),
(3,1,'127.0.0.1','curl/7.88.1','','2026-06-03 15:29:40'),
(4,1,'211.90.251.252','Mozilla/5.0 (Linux; U; Android 15; zh-cn; RMX3706 Build/AP3A.240617.008) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/115.0.5790.168 Mobile Safari/537.36 HeyTapBrowser/40.10.10.1','https://8080-6a23e20d848739f4.monkeycode-ai.online/','2026-06-03 15:42:23'),
(5,5,'211.90.251.252','Mozilla/5.0 (Linux; U; Android 15; zh-cn; RMX3706 Build/AP3A.240617.008) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/115.0.5790.168 Mobile Safari/537.36 HeyTapBrowser/40.10.10.1','https://8080-6a23e20d848739f4.monkeycode-ai.online/','2026-06-03 15:42:25'),
(6,0,'211.90.251.252','Mozilla/5.0 (Linux; U; Android 15; zh-cn; RMX3706 Build/AP3A.240617.008) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/115.0.5790.168 Mobile Safari/537.36 HeyTapBrowser/40.10.10.1','https://8080-6a23e20d848739f4.monkeycode-ai.online/','2026-06-03 15:42:31'),
(7,1,'211.90.251.252','Mozilla/5.0 (Linux; U; Android 15; zh-cn; RMX3706 Build/AP3A.240617.008) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/115.0.5790.168 Mobile Safari/537.36 HeyTapBrowser/40.10.10.1','https://8080-6a23e20d848739f4.monkeycode-ai.online/','2026-06-03 15:43:29');
/*!40000 ALTER TABLE `visit_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-03 15:45:42
