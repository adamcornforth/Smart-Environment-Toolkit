-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2015 at 03:38 PM
-- Server version: 5.5.41
-- PHP Version: 5.4.36-0+deb7u3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `Acceleration`
--

CREATE TABLE IF NOT EXISTS `Acceleration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acceleration` float(8,2) NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acceleration_job_id_foreign` (`job_id`),
  KEY `acceleration_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `Actuator`
--

CREATE TABLE IF NOT EXISTS `Actuator` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actuator_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_on` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `actuator_actuator_address_unique` (`actuator_address`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `actuator_job`
--

CREATE TABLE IF NOT EXISTS `actuator_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `actuator_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  `direction` enum('ABOVE','BELOW','EQUALS') COLLATE utf8_unicode_ci NOT NULL,
  `threshold` float(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `actuator_job_actuator_id_foreign` (`actuator_id`),
  KEY `actuator_job_job_id_foreign` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `Condition`
--

CREATE TABLE IF NOT EXISTS `Condition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actuator_id` int(10) unsigned DEFAULT NULL,
  `actuator_job` int(10) unsigned DEFAULT NULL,
  `boolean_operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `second_actuator_job` int(10) unsigned DEFAULT NULL,
  `next_condition` int(10) unsigned DEFAULT NULL,
  `next_operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `condition_actuator_job_foreign` (`actuator_job`),
  KEY `condition_second_actuator_job_foreign` (`second_actuator_job`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Heat`
--

CREATE TABLE IF NOT EXISTS `Heat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `heat_temperature` float(8,2) NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `heat_job_id_foreign` (`job_id`),
  KEY `heat_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15071 ;

-- --------------------------------------------------------

--
-- Table structure for table `Job`
--

CREATE TABLE IF NOT EXISTS `Job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `threshold` float(8,2) DEFAULT NULL,
  `sample_rate` int(11) DEFAULT NULL,
  `tracking` tinyint(1) DEFAULT '1',
  `object_id` int(10) unsigned NOT NULL,
  `sensor_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `job_object_id_foreign` (`object_id`),
  KEY `job_sensor_id_foreign` (`sensor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `Light`
--

CREATE TABLE IF NOT EXISTS `Light` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `light_intensity` int(11) NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `light_job_id_foreign` (`job_id`),
  KEY `light_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15042 ;

-- --------------------------------------------------------

--
-- Table structure for table `Motion`
--

CREATE TABLE IF NOT EXISTS `Motion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `motion` int(11) NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `motion_job_id_foreign` (`job_id`),
  KEY `motion_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Object`
--

CREATE TABLE IF NOT EXISTS `Object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `spot_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `object_spot_id_foreign` (`spot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `Sensor`
--

CREATE TABLE IF NOT EXISTS `Sensor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `measures` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `decimal_points` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port_number` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `Spot`
--

CREATE TABLE IF NOT EXISTS `Spot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `battery_percent` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `spot_spot_address_unique` (`spot_address`),
  KEY `spot_user_id_foreign` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `Switch`
--

CREATE TABLE IF NOT EXISTS `Switch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `switch_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `switch_job_id_foreign` (`job_id`),
  KEY `switch_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Tweet`
--

CREATE TABLE IF NOT EXISTS `Tweet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tweet_id` bigint(20) NOT NULL,
  `tweet` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seen` int(11) NOT NULL DEFAULT '0',
  `replied` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Water`
--

CREATE TABLE IF NOT EXISTS `Water` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `water_percent` int(11) NOT NULL,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `water_job_id_foreign` (`job_id`),
  KEY `water_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `Zone`
--

CREATE TABLE IF NOT EXISTS `Zone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `zone_object`
--

CREATE TABLE IF NOT EXISTS `zone_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(10) unsigned NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `zone_object_object_id_foreign` (`object_id`),
  KEY `zone_object_zone_id_foreign` (`zone_id`),
  KEY `zone_object_job_id_foreign` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `zone_spot`
--

CREATE TABLE IF NOT EXISTS `zone_spot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(10) unsigned NOT NULL,
  `spot_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `zone_spot_spot_id_foreign` (`spot_id`),
  KEY `zone_spot_zone_id_foreign` (`zone_id`),
  KEY `zone_spot_job_id_foreign` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=115 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Acceleration`
--
ALTER TABLE `Acceleration`
  ADD CONSTRAINT `acceleration_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `acceleration_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `actuator_job`
--
ALTER TABLE `actuator_job`
  ADD CONSTRAINT `actuator_job_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`),
  ADD CONSTRAINT `actuator_job_actuator_id_foreign` FOREIGN KEY (`actuator_id`) REFERENCES `Actuator` (`id`);

--
-- Constraints for table `Condition`
--
ALTER TABLE `Condition`
  ADD CONSTRAINT `condition_second_actuator_job_foreign` FOREIGN KEY (`second_actuator_job`) REFERENCES `actuator_job` (`id`),
  ADD CONSTRAINT `condition_actuator_job_foreign` FOREIGN KEY (`actuator_job`) REFERENCES `actuator_job` (`id`);

--
-- Constraints for table `Heat`
--
ALTER TABLE `Heat`
  ADD CONSTRAINT `heat_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `heat_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `Job`
--
ALTER TABLE `Job`
  ADD CONSTRAINT `job_sensor_id_foreign` FOREIGN KEY (`sensor_id`) REFERENCES `Sensor` (`id`),
  ADD CONSTRAINT `job_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `Object` (`id`);

--
-- Constraints for table `Light`
--
ALTER TABLE `Light`
  ADD CONSTRAINT `light_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `light_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `Motion`
--
ALTER TABLE `Motion`
  ADD CONSTRAINT `motion_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `motion_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `Object`
--
ALTER TABLE `Object`
  ADD CONSTRAINT `object_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `Spot` (`id`);

--
-- Constraints for table `Spot`
--
ALTER TABLE `Spot`
  ADD CONSTRAINT `spot_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`);

--
-- Constraints for table `Switch`
--
ALTER TABLE `Switch`
  ADD CONSTRAINT `switch_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `switch_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `Water`
--
ALTER TABLE `Water`
  ADD CONSTRAINT `water_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `water_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`);

--
-- Constraints for table `zone_object`
--
ALTER TABLE `zone_object`
  ADD CONSTRAINT `zone_object_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`),
  ADD CONSTRAINT `zone_object_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `Object` (`id`),
  ADD CONSTRAINT `zone_object_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`);

--
-- Constraints for table `zone_spot`
--
ALTER TABLE `zone_spot`
  ADD CONSTRAINT `zone_spot_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `Job` (`id`),
  ADD CONSTRAINT `zone_spot_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `Spot` (`id`),
  ADD CONSTRAINT `zone_spot_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
