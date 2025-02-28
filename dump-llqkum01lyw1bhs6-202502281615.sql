-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: xefi550t7t6tjn36.cbetxkdyhwsb.us-east-1.rds.amazonaws.com    Database: llqkum01lyw1bhs6
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int DEFAULT NULL,
  `admin_username` varchar(50) DEFAULT NULL,
  `admin_password` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','$2y$10$QF9/m842H/tKpTrjgYZSweXlDDmgDGXxUK.7EBIwDQ6lLcdedA9Ei'),(2,'admin2','$2y$10$QF9/m842H/tKpTrjgYZSweXlDDmgDGXxUK.7EBIwDQ6lLcdedA9Ei');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `event_date` varchar(50) DEFAULT NULL,
  `event_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `event_descrip` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `event_point` int DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'2024-12-04','ทักษะวิชาการ ปีการศึกษา 2566','ทักษะวิชาการ ปีการศึกษา 2566',10),(2,'2024-12-25','จิตอาสาพระราชทาน','จิตอาสาพระราชทาน',10),(3,'2025-01-02','ขับขี่ปลอดภัย ปีการศึกษา 2566','ขับขี่ปลอดภัย ปีการศึกษา 2566',5),(4,'2025-01-03','เข้าร่วมกิจกรรมตักบาตร','เข้าร่วมกิจกรรมตักบาตร',2),(6,'2025-01-07','พิธีวันคล้ายวันพระราชสมภพรัชกาลที่ 9','พิธีวันคล้ายวันพระราชสมภพรัชกาลที่ 9',10),(7,'2025-01-29','เข้าร่วมกิจกรรม วัคซีน HPV','เข้าร่วมกิจกรรม วัคซีน HPV',5),(8,'2025-01-29','เข้าร่วมพิธีลากราชรถ','เข้าร่วมพิธีลากราชรถ',5),(13,'2025-02-03','กีฬาสี ปีการศึกษา 2566','กีฬาสี ปีการศึกษา 2566',10);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history` (
  `his_id` int NOT NULL AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `check_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`his_id`),
  KEY `history_events_FK` (`event_id`),
  KEY `history_users_FK` (`user_id`),
  CONSTRAINT `history_events_FK` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  CONSTRAINT `history_users_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` VALUES (1,1,1,1),(2,2,1,1),(3,13,2,1),(4,3,1,0),(20,13,1,0),(21,1,4,0),(22,13,4,1),(23,13,14,0),(24,13,16,1),(25,13,5,1),(26,13,7,0);
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `term` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `activity_points` int DEFAULT NULL,
  `year` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('1',40,'2566'),('2',30,'2566'),('1',40,'2567'),('2',20,'2567'),('1',20,'2568'),('2',20,'2568');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` longtext,
  `password` longtext,
  `fullname` longtext,
  `class` longtext,
  `avatar` longtext,
  `std_qr_code` longtext,
  `points` bigint DEFAULT NULL,
  `department` longtext,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'66301280000','1234','นายทดสอบ เทสเตอร์','ปวส.2/1','https://res.cloudinary.com/hi8zsd11w/image/upload/v1734355142/cat-core-free_wzxdgj.png','qrcodes/Maew_qr.png',20,'แผนกเทสเตอร์'),(2,'66301280005','1234','นางสาวพิมพ์ตะวัน สงวนพงษ์','ปวส.2/1','https://res.cloudinary.com/hi8zsd11w/image/upload/v1734356432/66301280005_ib0ueq.jpg','qrcodes/66301280005_qr.png',10,'เทคโนฯ'),(3,'66301280009','1234','นายอารูวัน เจ๊ะมะ','ปวส.2/1','https://res.cloudinary.com/hi8zsd11w/image/upload/v1734356216/66301280009_jfxxgx.jpg','qrcodes/66301280009_qr.png',0,'เทคโนฯ'),(4,'66301280006','1234','นายฟาอิซ บากา','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801146/454181501_1020639543184054_8754073851927960501_n_slf4ij.jpg','qrcodes/66301280006_qr.png',10,'เทคโนฯ'),(5,'66301280008','1234','นางสาวสุดาภัทร์ แสงนวล','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801144/452885166_7902518563176750_2313041644084970944_n_jgyjnl.jpg','qrcodes/66301280008_qr.png',10,'เทคโนฯ'),(6,'66301280014','1234','นางสาวณัฐชา สุวรรณโณ','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801145/453408533_1918520991948668_4303259606985281450_n_qi3qap.jpg','qrcodes/66301280014_qr.png',0,'เทคโนฯ'),(7,'66301280015','1234','นายนิอัยมาน อุมา','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801147/455668246_1151680832797290_409616694812005180_n_mweqzy.jpg','qrcodes/66301280015_qr.png',0,'เทคโนฯ'),(8,'66301280016','1234','นายมูหัมมัด ดอปอ','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801146/453686616_1202102237491035_4298314156620589008_n_hcuj5n.jpg','qrcodes/66301280016_qr.png',0,'เทคโนฯ'),(9,'66301280017','1234','นายมูหัมมัดซูฟียาน อีแต','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801144/453378215_865209868341947_660604352580534275_n_wsibiz.jpg','qrcodes/66301280017_qr.png',0,'เทคโนฯ'),(10,'66301280018','1234','นายมูหัมมัดอนัส เด็ง','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801148/454100145_3492399760906866_2614974141669480778_n_tpi7hw.jpg','qrcodes/66301280018_qr.png',0,'เทคโนฯ'),(11,'66301280019','1234','นางสาวโยษิตา โยธินพันศรี','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801145/453651107_1033669538309514_2370632599790400483_n_gwvnm8.jpg','qrcodes/66301280019_qr.png',0,'เทคโนฯ'),(12,'66301280020','1234','นายวาลิด เต๊ะ','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801144/453187460_1226474051820753_2611415262869220222_n_lngos8.jpg','qrcodes/66301280020_qr.png',0,'เทคโนฯ'),(13,'66301280021','1234','นายอรรถพงศ์ คงสุข','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739883451/Users/wo1fbjmbwjzbutrpw91p.png','qrcodes/66301280021_qr.png',0,'เทคโนฯ'),(14,'66301280022','1234','นายอันวา เละนุ๊','ปวส.2/2','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801149/450759604_477917195025938_8208618920625845997_n_wtigcm.jpg','qrcodes/66301280022_qr.png',0,'เทคโนฯ'),(15,'66301280003','1234','นางสาวนุรยานี ปูตาสา','ปวส.2/3','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739884451/Users/vhnkbaieuaiiqsdxtdxd.png','qrcodes/66301280003_qr.png',0,'เทคโนฯ'),(16,'66301280004','1234','นางสาวนูรีฮัน แมวาโสะ','ปวส.2/3','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801147/453908277_1582530625978719_6191235163449273673_n_vaqzgc.jpg','qrcodes/66301280004_qr.png',10,'เทคโนฯ'),(17,'66301280026','1234','นายไซฟุตดีน โดยหมะ','ปวส.2/3','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739884450/Users/aav7sowajzxxmgdjnko5.png','qrcodes/66301280026_qr.png',0,'เทคโนฯ'),(18,'66301280032','1234','นายอาตีฟ สะมะแอ','ปวส.2/3','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801146/453511333_1021711342442262_3504337055866492750_n_clgopo.jpg','qrcodes/66301280032_qr.png',0,'เทคโนฯ'),(19,'66301280037','1234','นายฮานาฟี มะดรอฮิง','ปวส.2/3','https://res.cloudinary.com/hi8zsd11w/image/upload/v1739801146/453889087_1215340509882273_8058759825721867752_n_fwjmsz.jpg','qrcodes/66301280037_qr.png',0,'เทคโนฯ');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'llqkum01lyw1bhs6'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-28 16:16:22
