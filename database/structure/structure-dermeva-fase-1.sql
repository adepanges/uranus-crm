/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.7.21-0ubuntu0.16.04.1 : Database - dermeva
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dermeva` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dermeva`;

/*Table structure for table `customer` */

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1151 DEFAULT CHARSET=latin1;

/*Table structure for table `customer_address` */

CREATE TABLE `customer_address` (
  `customer_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `provinsi_id` char(2) DEFAULT '0',
  `kabupaten_id` char(4) DEFAULT '0',
  `kecamatan_id` char(7) DEFAULT '0',
  `desa_id` char(10) DEFAULT '0',
  `desa_kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`customer_address_id`),
  KEY `FK_reference_14` (`customer_id`),
  CONSTRAINT `FK_reference_14` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1146 DEFAULT CHARSET=latin1;

/*Table structure for table `franchise` */

CREATE TABLE `franchise` (
  `franchise_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_franchise_id` int(11) DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `address` text,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`franchise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `management_team_cs` */

CREATE TABLE `management_team_cs` (
  `team_cs_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `leader_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`team_cs_id`),
  KEY `FK_reference_8` (`franchise_id`),
  KEY `FK_reference_9` (`leader_id`),
  CONSTRAINT `FK_reference_8` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`),
  CONSTRAINT `FK_reference_9` FOREIGN KEY (`leader_id`) REFERENCES `sso_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `management_team_cs_member` */

CREATE TABLE `management_team_cs_member` (
  `team_cs_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_cs_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`team_cs_member_id`),
  KEY `FK_reference_10` (`team_cs_id`),
  KEY `FK_reference_11` (`user_id`),
  CONSTRAINT `FK_reference_10` FOREIGN KEY (`team_cs_id`) REFERENCES `management_team_cs` (`team_cs_id`),
  CONSTRAINT `FK_reference_11` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `master_call_method` */

CREATE TABLE `master_call_method` (
  `call_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` smallint(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`call_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `master_event` */

CREATE TABLE `master_event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
  `postback_network` smallint(6) DEFAULT '0',
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `master_logistics` */

CREATE TABLE `master_logistics` (
  `logistic_id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` smallint(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`logistic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `master_logistics_status` */

CREATE TABLE `master_logistics_status` (
  `logistics_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` smallint(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`logistics_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `master_order_status` */

CREATE TABLE `master_order_status` (
  `order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `sort` smallint(6) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`order_status_id`),
  KEY `FK_reference_43` (`event_id`),
  CONSTRAINT `FK_reference_43` FOREIGN KEY (`event_id`) REFERENCES `master_event` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `master_payment_method` */

CREATE TABLE `master_payment_method` (
  `payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
  `third_party` smallint(6) DEFAULT '0',
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `master_wilayah_desa` */

CREATE TABLE `master_wilayah_desa` (
  `id` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `kecamatan_id` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `villages_district_id_index` (`kecamatan_id`),
  CONSTRAINT `villages_district_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `master_wilayah_kecamatan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_kabupaten` */

CREATE TABLE `master_wilayah_kabupaten` (
  `id` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `provinsi_id` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `regencies_province_id_index` (`provinsi_id`),
  CONSTRAINT `regencies_province_id_foreign` FOREIGN KEY (`provinsi_id`) REFERENCES `master_wilayah_provinsi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_kecamatan` */

CREATE TABLE `master_wilayah_kecamatan` (
  `id` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `kabupaten_id` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_id_index` (`kabupaten_id`),
  CONSTRAINT `districts_regency_id_foreign` FOREIGN KEY (`kabupaten_id`) REFERENCES `master_wilayah_kabupaten` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_provinsi` */

CREATE TABLE `master_wilayah_provinsi` (
  `id` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `module_feature` */

CREATE TABLE `module_feature` (
  `feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `is_menu` smallint(6) DEFAULT '0',
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`feature_id`),
  UNIQUE KEY `name` (`name`),
  KEY `FK_reference_29` (`menu_id`),
  CONSTRAINT `FK_reference_29` FOREIGN KEY (`menu_id`) REFERENCES `module_menu` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `module_menu` */

CREATE TABLE `module_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`menu_id`),
  KEY `FK_reference_34` (`module_id`),
  CONSTRAINT `FK_reference_34` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `modules` */

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `network` */

CREATE TABLE `network` (
  `network_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`network_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `network_event` */

CREATE TABLE `network_event` (
  `network_event_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `callback_link` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`network_event_id`),
  KEY `FK_reference_41` (`network_id`),
  KEY `FK_reference_42` (`event_id`),
  CONSTRAINT `FK_reference_41` FOREIGN KEY (`network_id`) REFERENCES `network` (`network_id`),
  CONSTRAINT `FK_reference_42` FOREIGN KEY (`event_id`) REFERENCES `master_event` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `network_order` */

CREATE TABLE `network_order` (
  `network_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `cid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`network_order_id`),
  KEY `FK_reference_18` (`order_id`),
  KEY `FK_reference_19` (`network_id`),
  CONSTRAINT `FK_reference_18` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `FK_reference_19` FOREIGN KEY (`network_id`) REFERENCES `network` (`network_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orders` */

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `customer_address_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `logistic_id` int(11) DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL,
  `logistics_status_id` int(11) DEFAULT NULL,
  `call_method_id` int(11) DEFAULT NULL,
  `order_status` varchar(255) DEFAULT NULL,
  `logistics_status` varchar(255) DEFAULT NULL,
  `shipping_code` varchar(100) DEFAULT NULL,
  `call_method` varchar(255) DEFAULT NULL,
  `order_code` varchar(30) NOT NULL,
  `customer_info` text,
  `customer_address` text,
  `total_price` float(8,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `version` smallint(6) DEFAULT '1',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `index_1` (`order_code`),
  KEY `FK_reference_13` (`customer_id`),
  KEY `FK_reference_15` (`customer_address_id`),
  KEY `FK_reference_16` (`logistic_id`),
  KEY `FK_reference_17` (`payment_method_id`),
  KEY `FK_reference_22` (`order_status_id`),
  KEY `FK_reference_37` (`logistics_status_id`),
  KEY `FK_reference_44` (`call_method_id`),
  CONSTRAINT `FK_reference_13` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `FK_reference_15` FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`customer_address_id`),
  CONSTRAINT `FK_reference_16` FOREIGN KEY (`logistic_id`) REFERENCES `master_logistics` (`logistic_id`),
  CONSTRAINT `FK_reference_17` FOREIGN KEY (`payment_method_id`) REFERENCES `master_payment_method` (`payment_method_id`),
  CONSTRAINT `FK_reference_22` FOREIGN KEY (`order_status_id`) REFERENCES `master_order_status` (`order_status_id`),
  CONSTRAINT `FK_reference_37` FOREIGN KEY (`logistics_status_id`) REFERENCES `master_logistics_status` (`logistics_status_id`),
  CONSTRAINT `FK_reference_44` FOREIGN KEY (`call_method_id`) REFERENCES `master_call_method` (`call_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_cart` */

CREATE TABLE `orders_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_package_id` int(11) DEFAULT NULL,
  `product_merk` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `qty` smallint(6) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `is_package` tinyint(1) DEFAULT NULL,
  `price_type` enum('PACKAGE','RETAIL') DEFAULT NULL,
  `package_price` float(8,2) DEFAULT NULL,
  `version` int(11) DEFAULT '1',
  PRIMARY KEY (`cart_id`),
  KEY `FK_reference_30` (`product_id`),
  KEY `FK_reference_31` (`product_package_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `FK_reference_30` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `FK_reference_31` FOREIGN KEY (`product_package_id`) REFERENCES `product_package` (`product_package_id`),
  CONSTRAINT `orders_cart_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_invoices` */

CREATE TABLE `orders_invoices` (
  `order_invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_address_id` int(11) DEFAULT NULL,
  `customer` text,
  `customer_address` text,
  `order_cart` text,
  `billed_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `version` smallint(6) DEFAULT '1',
  PRIMARY KEY (`order_invoice_id`),
  KEY `FK_reference_38` (`order_id`),
  KEY `FK_reference_39` (`customer_id`),
  KEY `FK_reference_40` (`customer_address_id`),
  CONSTRAINT `FK_reference_38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `FK_reference_39` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `FK_reference_40` FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`customer_address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orders_logistics` */

CREATE TABLE `orders_logistics` (
  `order_logistics_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `logistics_status_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`order_logistics_id`),
  KEY `FK_reference_24` (`user_id`),
  KEY `FK_reference_27` (`order_id`),
  KEY `FK_reference_36` (`logistics_status_id`),
  CONSTRAINT `FK_reference_24` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`),
  CONSTRAINT `FK_reference_27` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `FK_reference_36` FOREIGN KEY (`logistics_status_id`) REFERENCES `master_logistics_status` (`logistics_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orders_process` */

CREATE TABLE `orders_process` (
  `process_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notes` text,
  `event_status` char(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `FK_reference_20` (`order_id`),
  KEY `FK_reference_21` (`user_id`),
  KEY `FK_reference_23` (`order_status_id`),
  CONSTRAINT `FK_reference_20` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `FK_reference_21` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`),
  CONSTRAINT `FK_reference_23` FOREIGN KEY (`order_status_id`) REFERENCES `master_order_status` (`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Table structure for table `product` */

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `merk` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `product_category` */

CREATE TABLE `product_category` (
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `product_package` */

CREATE TABLE `product_package` (
  `product_package_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `price_type` enum('PACKAGE','RETAIL') DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `product_package_list` */

CREATE TABLE `product_package_list` (
  `product_package_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_package_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `qty` smallint(6) DEFAULT '1',
  `weight` smallint(6) DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_package_list_id`),
  KEY `FK_reference_32` (`product_package_id`),
  KEY `FK_reference_33` (`product_id`),
  CONSTRAINT `FK_reference_32` FOREIGN KEY (`product_package_id`) REFERENCES `product_package` (`product_package_id`),
  CONSTRAINT `FK_reference_33` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_role` */

CREATE TABLE `sso_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_role_access` */

CREATE TABLE `sso_role_access` (
  `role_access_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `feature_id` int(11) DEFAULT NULL,
  `feature_name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`role_access_id`),
  KEY `FK_reference_35` (`module_id`),
  KEY `FK_reference_5` (`role_id`),
  KEY `FK_reference_6` (`menu_id`),
  KEY `FK_reference_7` (`feature_id`),
  CONSTRAINT `FK_reference_35` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`),
  CONSTRAINT `FK_reference_5` FOREIGN KEY (`role_id`) REFERENCES `sso_role` (`role_id`),
  CONSTRAINT `FK_reference_6` FOREIGN KEY (`menu_id`) REFERENCES `module_menu` (`menu_id`),
  CONSTRAINT `FK_reference_7` FOREIGN KEY (`feature_id`) REFERENCES `module_feature` (`feature_id`)
) ENGINE=InnoDB AUTO_INCREMENT=564 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_session_web` */

CREATE TABLE `sso_session_web` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `sso_user` */

CREATE TABLE `sso_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_user_role` */

CREATE TABLE `sso_user_role` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`user_role_id`),
  KEY `FK_reference_1` (`user_id`),
  KEY `FK_reference_2` (`role_id`),
  KEY `FK_reference_4` (`franchise_id`),
  CONSTRAINT `FK_reference_1` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`),
  CONSTRAINT `FK_reference_2` FOREIGN KEY (`role_id`) REFERENCES `sso_role` (`role_id`),
  CONSTRAINT `FK_reference_4` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
