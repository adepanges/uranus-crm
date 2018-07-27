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
/*Table structure for table `account_statement` */

CREATE TABLE `account_statement` (
  `account_statement_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_statement_seq` int(11) DEFAULT '1',
  `parent_statement_id` int(11) DEFAULT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `seq_invoice` int(11) unsigned NOT NULL,
  `generated_invoice` varchar(100) DEFAULT NULL,
  `transaction_type` enum('D','K') DEFAULT 'K',
  `transaction_date` date NOT NULL,
  `transaction_amount` double(15,2) NOT NULL,
  `balance` double(15,2) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `claim` tinyint(1) DEFAULT '0',
  `commit` tinyint(1) DEFAULT '0',
  `fix` tinyint(1) DEFAULT '1',
  `user_id` int(11) DEFAULT NULL,
  `commit_user_id` int(11) DEFAULT NULL,
  `is_sales` tinyint(1) DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`account_statement_id`),
  KEY `payment_method_id` (`payment_method_id`),
  KEY `franchise_id` (`franchise_id`),
  KEY `user_id` (`user_id`),
  KEY `commit_user_id` (`commit_user_id`),
  CONSTRAINT `account_statement_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `master_payment_method` (`payment_method_id`) ON UPDATE NO ACTION,
  CONSTRAINT `account_statement_ibfk_2` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ON UPDATE NO ACTION,
  CONSTRAINT `account_statement_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `account_statement_ibfk_4` FOREIGN KEY (`commit_user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=567 DEFAULT CHARSET=latin1;

/*Table structure for table `customer` */

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `gender` enum('L','P','N') DEFAULT 'N',
  `birthdate` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1976 DEFAULT CHARSET=latin1;

/*Table structure for table `customer_address` */

CREATE TABLE `customer_address` (
  `customer_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `nama_penerima` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
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
  `is_primary` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`customer_address_id`),
  KEY `FK_reference_14` (`customer_id`),
  CONSTRAINT `FK_reference_14` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2138 DEFAULT CHARSET=latin1;

/*Table structure for table `customer_phonenumber` */

CREATE TABLE `customer_phonenumber` (
  `customer_phonenumber_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `is_primary` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`customer_phonenumber_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `customer_phonenumber_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1036 DEFAULT CHARSET=latin1;

/*Table structure for table `franchise` */

CREATE TABLE `franchise` (
  `franchise_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_franchise_id` int(11) DEFAULT '0',
  `code` varchar(5) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nama_badan` varchar(255) DEFAULT NULL,
  `tax_number` varchar(255) DEFAULT NULL,
  `address` text,
  `logo` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`franchise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `inventory` */

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `used` int(11) DEFAULT '0',
  `notes` text,
  `is_active` tinyint(4) DEFAULT '0',
  `status` enum('1','2') DEFAULT NULL,
  `arrived_at` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`inventory_id`),
  KEY `franchise_id` (`franchise_id`),
  KEY `product_id` (`product_id`),
  KEY `created_by_user_id` (`created_by_user_id`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ON UPDATE NO ACTION,
  CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON UPDATE NO ACTION,
  CONSTRAINT `inventory_ibfk_3` FOREIGN KEY (`created_by_user_id`) REFERENCES `sso_user` (`user_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `inventory_operations` */

CREATE TABLE `inventory_operations` (
  `inventory_operations_id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `orders_code` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`inventory_operations_id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `product_id` (`product_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `inventory_operations_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`inventory_id`) ON UPDATE NO ACTION,
  CONSTRAINT `inventory_operations_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON UPDATE NO ACTION,
  CONSTRAINT `inventory_operations_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  CONSTRAINT `FK_reference_9` FOREIGN KEY (`leader_id`) REFERENCES `sso_user` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

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
  CONSTRAINT `FK_reference_10` FOREIGN KEY (`team_cs_id`) REFERENCES `management_team_cs` (`team_cs_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_11` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

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
  `sort` int(11) DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kecamatan_id` int(11) NOT NULL,
  `kode_pos` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `villages_district_id_index` (`kecamatan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81249 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_kabupaten` */

CREATE TABLE `master_wilayah_kabupaten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provinsi_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `regencies_province_id_index` (`provinsi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=476 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_kecamatan` */

CREATE TABLE `master_wilayah_kecamatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kabupaten_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_id_index` (`kabupaten_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6995 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `master_wilayah_provinsi` */

CREATE TABLE `master_wilayah_provinsi` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Table structure for table `modules` */

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Table structure for table `network` */

CREATE TABLE `network` (
  `network_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `catch` text,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`network_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `network_postback` */

CREATE TABLE `network_postback` (
  `network_postback_id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`network_postback_id`),
  KEY `FK_reference_41` (`network_id`),
  KEY `FK_reference_42` (`event_id`),
  CONSTRAINT `FK_reference_41` FOREIGN KEY (`network_id`) REFERENCES `network` (`network_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_reference_42` FOREIGN KEY (`event_id`) REFERENCES `master_event` (`event_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `orders` */

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `orders_double_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_address_id` int(11) DEFAULT NULL,
  `customer_phonenumber_id` int(11) DEFAULT NULL,
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
  `total_price` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `note` text,
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
  KEY `orders_double_id` (`orders_double_id`),
  KEY `orders_ibfk_2` (`franchise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1132 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_backup` */

CREATE TABLE `orders_backup` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `orders_double_id` int(11) DEFAULT NULL,
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
  `total_price` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `version` smallint(6) DEFAULT '1',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `index_1` (`order_code`)
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_cart` */

CREATE TABLE `orders_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_package_id` int(11) DEFAULT NULL,
  `product_merk` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `qty` smallint(6) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `is_package` tinyint(1) DEFAULT NULL,
  `price_type` enum('PACKAGE','RETAIL') DEFAULT NULL,
  `package_price` decimal(15,2) DEFAULT NULL,
  `version` int(11) DEFAULT '1',
  PRIMARY KEY (`cart_id`),
  KEY `FK_reference_30` (`product_id`),
  KEY `FK_reference_31` (`product_package_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `orders_cart_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3742 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_cart_backup` */

CREATE TABLE `orders_cart_backup` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_package_id` int(11) DEFAULT NULL,
  `product_merk` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `qty` smallint(6) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `is_package` tinyint(1) DEFAULT NULL,
  `price_type` enum('PACKAGE','RETAIL') DEFAULT NULL,
  `package_price` decimal(15,2) DEFAULT NULL,
  `version` int(11) DEFAULT '1',
  PRIMARY KEY (`cart_id`),
  KEY `FK_reference_30` (`product_id`),
  KEY `FK_reference_31` (`product_package_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2027 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_double` */

CREATE TABLE `orders_double` (
  `orders_double_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_telephone` varchar(25) DEFAULT NULL,
  `double_reason` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`orders_double_id`),
  KEY `orders_double_ibfk_1` (`customer_id`),
  CONSTRAINT `orders_double_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_invoices` */

CREATE TABLE `orders_invoices` (
  `order_invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `account_statement_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_address_id` int(11) DEFAULT NULL,
  `logistic_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `order_code` varchar(20) DEFAULT NULL,
  `customer` text,
  `customer_address` text,
  `order_cart` text,
  `total_price` decimal(15,2) DEFAULT NULL,
  `transaction_amount` decimal(15,2) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `billed_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `printed` smallint(6) DEFAULT '0',
  `logistic_name` varchar(255) DEFAULT NULL,
  `version` smallint(6) DEFAULT '1',
  PRIMARY KEY (`order_invoice_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `FK_reference_39` (`customer_id`),
  KEY `FK_reference_40` (`customer_address_id`),
  KEY `FK_reference_38` (`order_id`),
  KEY `orders_invoices_ibfk_1` (`franchise_id`),
  KEY `logistic_id` (`logistic_id`),
  KEY `account_statement_id` (`account_statement_id`),
  CONSTRAINT `FK_reference_38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_39` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `FK_reference_40` FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`customer_address_id`),
  CONSTRAINT `orders_invoices_ibfk_1` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ON DELETE SET NULL,
  CONSTRAINT `orders_invoices_ibfk_3` FOREIGN KEY (`account_statement_id`) REFERENCES `account_statement` (`account_statement_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

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
  KEY `FK_reference_36` (`logistics_status_id`),
  KEY `FK_reference_27` (`order_id`),
  CONSTRAINT `FK_reference_24` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`),
  CONSTRAINT `FK_reference_27` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_36` FOREIGN KEY (`logistics_status_id`) REFERENCES `master_logistics_status` (`logistics_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=798 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_network` */

CREATE TABLE `orders_network` (
  `order_network_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `catch` text,
  PRIMARY KEY (`order_network_id`),
  KEY `FK_reference_19` (`network_id`),
  KEY `FK_reference_18` (`order_id`),
  CONSTRAINT `FK_reference_18` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_19` FOREIGN KEY (`network_id`) REFERENCES `network` (`network_id`)
) ENGINE=InnoDB AUTO_INCREMENT=931 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_network_postback` */

CREATE TABLE `orders_network_postback` (
  `order_network_postback_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `network_postback_id` int(11) DEFAULT NULL,
  `order_network_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `orders_trigger` varchar(255) DEFAULT NULL,
  `network_name` varchar(255) DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `catch_data` text,
  `url` text,
  `postback_response` text,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_network_postback_id`),
  KEY `order_id` (`order_id`),
  KEY `network_id` (`network_id`),
  KEY `network_postback_id` (`network_postback_id`),
  KEY `order_network_id` (`order_network_id`),
  KEY `process_id` (`process_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `orders_network_postback_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `orders_network_postback_ibfk_2` FOREIGN KEY (`network_id`) REFERENCES `network` (`network_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `orders_network_postback_ibfk_3` FOREIGN KEY (`network_postback_id`) REFERENCES `network_postback` (`network_postback_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `orders_network_postback_ibfk_4` FOREIGN KEY (`order_network_id`) REFERENCES `orders_network` (`order_network_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `orders_network_postback_ibfk_5` FOREIGN KEY (`process_id`) REFERENCES `orders_process` (`process_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `orders_network_postback_ibfk_6` FOREIGN KEY (`event_id`) REFERENCES `master_event` (`event_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1450 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_process` */

CREATE TABLE `orders_process` (
  `process_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notes` text,
  `event_postback_status` char(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `FK_reference_21` (`user_id`),
  KEY `FK_reference_23` (`order_status_id`),
  KEY `FK_reference_20` (`order_id`),
  CONSTRAINT `FK_reference_20` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_21` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `FK_reference_23` FOREIGN KEY (`order_status_id`) REFERENCES `master_order_status` (`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3547 DEFAULT CHARSET=latin1;

/*Table structure for table `product` */

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(4) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `commission` float(8,2) DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `product_category` */

CREATE TABLE `product_category` (
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `product_package` */

CREATE TABLE `product_package` (
  `product_package_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(4) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double(15,2) DEFAULT NULL,
  `price_type` enum('PACKAGE','RETAIL') DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `product_package_list` */

CREATE TABLE `product_package_list` (
  `product_package_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_package_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `qty` smallint(6) DEFAULT '1',
  `weight` smallint(6) DEFAULT NULL,
  `price` double(15,2) DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`product_package_list_id`),
  KEY `FK_reference_32` (`product_package_id`),
  KEY `FK_reference_33` (`product_id`),
  CONSTRAINT `FK_reference_32` FOREIGN KEY (`product_package_id`) REFERENCES `product_package` (`product_package_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_33` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `setting_franchise` */

CREATE TABLE `setting_franchise` (
  `setting_franchise_id` int(11) NOT NULL AUTO_INCREMENT,
  `franchise_id` int(11) DEFAULT NULL,
  `setting_point_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`setting_franchise_id`),
  KEY `franchise_id` (`franchise_id`),
  KEY `setting_point_id` (`setting_point_id`),
  CONSTRAINT `setting_franchise_ibfk_1` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `setting_franchise_ibfk_2` FOREIGN KEY (`setting_point_id`) REFERENCES `setting_point` (`setting_point_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Table structure for table `setting_point` */

CREATE TABLE `setting_point` (
  `setting_point_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `default` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`setting_point_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_role` */

CREATE TABLE `sso_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=738 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_session_web` */

CREATE TABLE `sso_session_web` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` mediumblob NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

/*Table structure for table `sso_user_role` */

CREATE TABLE `sso_user_role` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `status` smallint(6) DEFAULT '0',
  PRIMARY KEY (`user_role_id`),
  KEY `FK_reference_1` (`user_id`),
  KEY `FK_reference_2` (`role_id`),
  KEY `FK_reference_4` (`franchise_id`),
  CONSTRAINT `FK_reference_1` FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_reference_2` FOREIGN KEY (`role_id`) REFERENCES `sso_role` (`role_id`),
  CONSTRAINT `FK_reference_4` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

/*Table structure for table `time_dimension` */

CREATE TABLE `time_dimension` (
  `id` int(11) NOT NULL,
  `db_date` date NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `quarter` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `day_name` varchar(9) NOT NULL,
  `month_name` varchar(9) NOT NULL,
  `holiday_flag` tinyint(1) DEFAULT '0',
  `weekend_flag` tinyint(1) DEFAULT '0',
  `event` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `td_ymd_idx` (`year`,`month`,`day`),
  UNIQUE KEY `td_dbdate_idx` (`db_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* Trigger structure for table `account_statement` */

DELIMITER $$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `before_insert_account_statement` BEFORE INSERT ON `account_statement` FOR EACH ROW BEGIN
	DECLARE vMaxAccStatement_seq INTEGER;
	
	SELECT MAX(`account_statement_seq`) into vMaxAccStatement_seq FROM `account_statement`
	WHERE `franchise_id` = NEW.franchise_id AND `payment_method_id` = NEW.payment_method_id;
	
	IF(vMaxAccStatement_seq IS NULL) THEN
		SET vMaxAccStatement_seq = 0;
	END IF;
	
	SET NEW.account_statement_seq = (vMaxAccStatement_seq + 1);
	
    END */$$


DELIMITER ;

/* Procedure structure for procedure `fill_date_dimension` */

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `fill_date_dimension`(IN startdate DATE,IN stopdate DATE)
BEGIN
    DECLARE currentdate DATE;
    SET currentdate = startdate;
    WHILE currentdate <= stopdate DO
        INSERT INTO time_dimension VALUES (
                        YEAR(currentdate)*10000+MONTH(currentdate)*100 + DAY(currentdate),
                        currentdate,
                        YEAR(currentdate),
                        MONTH(currentdate),
                        DAY(currentdate),
                        QUARTER(currentdate),
                        WEEKOFYEAR(currentdate),
                        DATE_FORMAT(currentdate,'%W'),
                        DATE_FORMAT(currentdate,'%M'),
                        0,
                        CASE DAYOFWEEK(currentdate) WHEN 1 THEN 1 WHEN 7 then 1 ELSE 0 END,
                        NULL);
        SET currentdate = ADDDATE(currentdate,INTERVAL 1 DAY);
    END WHILE;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
