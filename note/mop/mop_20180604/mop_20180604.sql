ALTER TABLE `customer_address`
	DROP FOREIGN KEY `FK_reference_14`  ;

ALTER TABLE `orders_invoices`
	DROP FOREIGN KEY `FK_reference_38`  ,
	DROP FOREIGN KEY `FK_reference_39`  ,
	DROP FOREIGN KEY `FK_reference_40`  ,
	DROP FOREIGN KEY `orders_invoices_ibfk_1`  ;

ALTER TABLE `sso_user_role`
	DROP FOREIGN KEY `FK_reference_1`  ,
	DROP FOREIGN KEY `FK_reference_2`  ,
	DROP FOREIGN KEY `FK_reference_4`  ;

ALTER TABLE `customer`
	ADD COLUMN `gender` enum('L','P','N')  COLLATE latin1_swedish_ci NULL DEFAULT 'N' after `full_name` ,
	ADD COLUMN `birthdate` date   NULL after `gender` ,
	ADD COLUMN `email` varchar(50)  COLLATE latin1_swedish_ci NULL after `birthdate` ,
	CHANGE `created_at` `created_at` datetime   NULL after `email` ,
	CHANGE `updated_at` `updated_at` datetime   NULL after `created_at` ,
	CHANGE `status` `status` smallint(6)   NULL DEFAULT 1 after `updated_at`;

ALTER TABLE `customer_address`
	ADD COLUMN `nama_penerima` varchar(255)  COLLATE latin1_swedish_ci NULL after `customer_id` ,
	ADD COLUMN `phone_number` varchar(255)  COLLATE latin1_swedish_ci NULL after `nama_penerima` ,
	CHANGE `address` `address` varchar(255)  COLLATE latin1_swedish_ci NULL after `phone_number` ,
	CHANGE `provinsi_id` `provinsi_id` char(2)  COLLATE latin1_swedish_ci NULL DEFAULT '0' after `address` ,
	CHANGE `kabupaten_id` `kabupaten_id` char(4)  COLLATE latin1_swedish_ci NULL DEFAULT '0' after `provinsi_id` ,
	CHANGE `kecamatan_id` `kecamatan_id` char(7)  COLLATE latin1_swedish_ci NULL DEFAULT '0' after `kabupaten_id` ,
	CHANGE `desa_id` `desa_id` char(10)  COLLATE latin1_swedish_ci NULL DEFAULT '0' after `kecamatan_id` ,
	CHANGE `desa_kelurahan` `desa_kelurahan` varchar(255)  COLLATE latin1_swedish_ci NULL after `desa_id` ,
	CHANGE `kecamatan` `kecamatan` varchar(255)  COLLATE latin1_swedish_ci NULL after `desa_kelurahan` ,
	CHANGE `kabupaten` `kabupaten` varchar(255)  COLLATE latin1_swedish_ci NULL after `kecamatan` ,
	CHANGE `provinsi` `provinsi` varchar(255)  COLLATE latin1_swedish_ci NULL after `kabupaten` ,
	CHANGE `postal_code` `postal_code` varchar(10)  COLLATE latin1_swedish_ci NULL after `provinsi` ,
	ADD COLUMN `is_primary` tinyint(1)   NULL DEFAULT 0 after `postal_code` ,
	CHANGE `created_at` `created_at` datetime   NULL after `is_primary` ,
	CHANGE `status` `status` smallint(6)   NULL DEFAULT 1 after `created_at` ;

ALTER TABLE `customer_phonenumber`
	ADD COLUMN `created_at` datetime   NULL after `is_primary` ,
	ADD COLUMN `updated_at` datetime   NULL after `created_at` ;

ALTER TABLE `orders`
	ADD COLUMN `customer_phonenumber_id` int(11)   NULL after `customer_address_id` ,
	CHANGE `payment_method_id` `payment_method_id` int(11)   NULL after `customer_phonenumber_id` ,
	CHANGE `logistic_id` `logistic_id` int(11)   NULL after `payment_method_id` ,
	CHANGE `order_status_id` `order_status_id` int(11)   NULL after `logistic_id` ,
	CHANGE `logistics_status_id` `logistics_status_id` int(11)   NULL after `order_status_id` ,
	CHANGE `call_method_id` `call_method_id` int(11)   NULL after `logistics_status_id` ,
	CHANGE `order_status` `order_status` varchar(255)  COLLATE latin1_swedish_ci NULL after `call_method_id` ,
	CHANGE `logistics_status` `logistics_status` varchar(255)  COLLATE latin1_swedish_ci NULL after `order_status` ,
	CHANGE `shipping_code` `shipping_code` varchar(100)  COLLATE latin1_swedish_ci NULL after `logistics_status` ,
	CHANGE `call_method` `call_method` varchar(255)  COLLATE latin1_swedish_ci NULL after `shipping_code` ,
	CHANGE `order_code` `order_code` varchar(30)  COLLATE latin1_swedish_ci NOT NULL after `call_method` ,
	CHANGE `customer_info` `customer_info` text  COLLATE latin1_swedish_ci NULL after `order_code` ,
	CHANGE `customer_address` `customer_address` text  COLLATE latin1_swedish_ci NULL after `customer_info` ,
	CHANGE `total_price` `total_price` decimal(15,2)   NULL after `customer_address` ,
	CHANGE `created_at` `created_at` datetime   NULL after `total_price` ,
	CHANGE `is_deleted` `is_deleted` tinyint(1)   NULL DEFAULT 0 after `created_at` ,
	CHANGE `note` `note` text  COLLATE latin1_swedish_ci NULL after `is_deleted` ,
	CHANGE `version` `version` smallint(6)   NULL DEFAULT 1 after `note` ;

ALTER TABLE `orders_invoices`
	ADD COLUMN `account_statement_id` int(11)   NULL after `franchise_id` ,
	CHANGE `order_id` `order_id` int(11)   NULL after `account_statement_id` ,
	CHANGE `customer_id` `customer_id` int(11)   NULL after `order_id` ,
	CHANGE `customer_address_id` `customer_address_id` int(11)   NULL after `customer_id` ,
	CHANGE `logistic_id` `logistic_id` int(11)   NULL after `customer_address_id` ,
	CHANGE `invoice_number` `invoice_number` varchar(50)  COLLATE latin1_swedish_ci NULL after `logistic_id` ,
	CHANGE `order_code` `order_code` varchar(20)  COLLATE latin1_swedish_ci NULL after `invoice_number` ,
	CHANGE `customer` `customer` text  COLLATE latin1_swedish_ci NULL after `order_code` ,
	CHANGE `customer_address` `customer_address` text  COLLATE latin1_swedish_ci NULL after `customer` ,
	CHANGE `order_cart` `order_cart` text  COLLATE latin1_swedish_ci NULL after `customer_address` ,
	CHANGE `total_price` `total_price` decimal(15,2)   NULL after `order_cart` ,
	ADD COLUMN `transaction_amount` decimal(15,2)   NULL after `total_price` ,
	CHANGE `payment_method` `payment_method` varchar(255)  COLLATE latin1_swedish_ci NULL after `transaction_amount` ,
	CHANGE `billed_date` `billed_date` datetime   NULL after `payment_method` ,
	CHANGE `paid_date` `paid_date` datetime   NULL after `billed_date` ,
	CHANGE `publish_date` `publish_date` datetime   NULL after `paid_date` ,
	CHANGE `printed` `printed` smallint(6)   NULL DEFAULT 0 after `publish_date` ,
	CHANGE `logistic_name` `logistic_name` varchar(255)  COLLATE latin1_swedish_ci NULL after `printed` ,
	CHANGE `version` `version` smallint(6)   NULL DEFAULT 1 after `logistic_name` ,
	ADD KEY `account_statement_id`(`account_statement_id`) ;

ALTER TABLE `orders_invoices`
	ADD CONSTRAINT `orders_invoices_ibfk_3`
	FOREIGN KEY (`account_statement_id`) REFERENCES `account_statement` (`account_statement_id`) ON UPDATE NO ACTION ;

ALTER TABLE `orders_network_postback`
	DROP FOREIGN KEY `orders_network_postback_ibfk_3`  ,
	DROP FOREIGN KEY `orders_network_postback_ibfk_5`  ;
ALTER TABLE `orders_network_postback`
	ADD CONSTRAINT `orders_network_postback_ibfk_3`
	FOREIGN KEY (`network_postback_id`) REFERENCES `network_postback` (`network_postback_id`) ON DELETE CASCADE ON UPDATE NO ACTION ,
	ADD CONSTRAINT `orders_network_postback_ibfk_5`
	FOREIGN KEY (`process_id`) REFERENCES `orders_process` (`process_id`) ON DELETE CASCADE ON UPDATE NO ACTION ;

ALTER TABLE `sso_user_role`
	ADD COLUMN `is_primary` tinyint(1)   NULL DEFAULT 0 after `created_at` ,
	CHANGE `status` `status` smallint(6)   NULL DEFAULT 0 after `is_primary` ;

ALTER TABLE `customer_address`
	ADD CONSTRAINT `FK_reference_14`
	FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ;

ALTER TABLE `orders_invoices`
	ADD CONSTRAINT `FK_reference_38`
	FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ,
	ADD CONSTRAINT `FK_reference_39`
	FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ,
	ADD CONSTRAINT `FK_reference_40`
	FOREIGN KEY (`customer_address_id`) REFERENCES `customer_address` (`customer_address_id`) ,
	ADD CONSTRAINT `orders_invoices_ibfk_1`
	FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ON DELETE SET NULL ;

ALTER TABLE `sso_user_role`
	ADD CONSTRAINT `FK_reference_1`
	FOREIGN KEY (`user_id`) REFERENCES `sso_user` (`user_id`) ON DELETE CASCADE ,
	ADD CONSTRAINT `FK_reference_2`
	FOREIGN KEY (`role_id`) REFERENCES `sso_role` (`role_id`) ,
	ADD CONSTRAINT `FK_reference_4`
	FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`franchise_id`) ;

-------------------------------------------------------------------------------------------------------
INSERT INTO `customer_phonenumber` (`customer_id`,`phonenumber`,`is_primary`)
SELECT `customer_id`,`telephone` AS phonenumber, 1 AS is_primary FROM `customer`
WHERE telephone IS NOT NULL
-------------------------------------------------------------------------------------------------------

/* Alter table in target */
ALTER TABLE `customer`
	DROP COLUMN `telephone` ;
