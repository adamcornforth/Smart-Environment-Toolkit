SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `Job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direction` enum('ABOVE','BELOW') COLLATE utf8_unicode_ci DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `Spot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spot_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `basestation_id` int(10) unsigned DEFAULT NULL,
  `battery_percent` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `spot_spot_address_unique` (`spot_address`),
  KEY `spot_user_id_foreign` (`user_id`),
  KEY `spot_basestation_id_foreign` (`basestation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `Zone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `width` int(11) NOT NULL DEFAULT '150',
  `height` int(11) NOT NULL DEFAULT '100',
  `top` int(11) NOT NULL DEFAULT '0',
  `left` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `zone_object_id_foreign` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `ZoneObject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` int(10) unsigned NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `width` int(11) NOT NULL DEFAULT '150',
  `height` int(11) NOT NULL DEFAULT '100',
  `top` int(11) NOT NULL DEFAULT '0',
  `left` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `zoneobject_object_id_foreign` (`object_id`),
  KEY `zoneobject_zone_id_foreign` (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;


ALTER TABLE `Job`
  ADD CONSTRAINT `job_sensor_id_foreign` FOREIGN KEY (`sensor_id`) REFERENCES `Sensor` (`id`),
  ADD CONSTRAINT `job_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `Object` (`id`);

ALTER TABLE `Object`
  ADD CONSTRAINT `object_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `Spot` (`id`);

ALTER TABLE `Spot`
  ADD CONSTRAINT `spot_basestation_id_foreign` FOREIGN KEY (`basestation_id`) REFERENCES `Basestation` (`id`),
  ADD CONSTRAINT `spot_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`);

ALTER TABLE `Zone`
  ADD CONSTRAINT `zone_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `Object` (`id`);

ALTER TABLE `ZoneObject`
  ADD CONSTRAINT `zoneobject_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `Zone` (`id`),
  ADD CONSTRAINT `zoneobject_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `Object` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
