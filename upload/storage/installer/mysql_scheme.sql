/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `installers_data` (
  `installer_id` int(11) NOT NULL AUTO_INCREMENT,
  `installer_name` varchar(50) DEFAULT NULL,
  `installer_description` varchar(250) DEFAULT NULL,
  `installer_file_name` varchar(250) DEFAULT NULL,
  `installer_file_name_real` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`installer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `packages_data` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(50) DEFAULT NULL,
  `package_description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`package_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `statistics_data` (
  `stats_id` int(11) NOT NULL AUTO_INCREMENT,
  `stats_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `stats_query_ip` varchar(15) DEFAULT NULL,
  `stats_query_packet` varchar(25) DEFAULT NULL,
  `stats_query_version` varchar(10) DEFAULT NULL,
  `stats_local_packet_id` varchar(10) DEFAULT NULL,
  `stats_proposed_update_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`stats_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `updates_data` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) DEFAULT NULL,
  `update_source_version` varchar(50) DEFAULT NULL,
  `update_target_version` varchar(50) DEFAULT NULL,
  `update_description` text,
  `installer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`update_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
