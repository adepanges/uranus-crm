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

/*Data for the table `customer` */

/*Data for the table `customer_address` */

/*Data for the table `franchise` */

/*Data for the table `management_team_cs` */

/*Data for the table `management_team_cs_member` */

/*Data for the table `master_call_method` */

insert  into `master_call_method`(`call_method_id`,`sort`,`name`,`icon`,`status`) values (1,1,'Telepon',NULL,1),(2,2,'SMS',NULL,1),(3,3,'WhatsApp',NULL,1),(4,99,'BBM',NULL,0),(5,99,'Line',NULL,0),(6,99,'Telegram',NULL,0),(7,99,'Skype',NULL,0);

/*Data for the table `master_event` */

insert  into `master_event`(`event_id`,`name`,`desc`,`postback_network`,`status`) values (1,'NO_EVENT',NULL,0,1),(2,'TRAFIC_IN',NULL,0,1),(3,'LEADS','Triggernya saat customer selesai masukin data2nya di form order begitu di klik submit, dia pindah ke halaman thank you page. Nah saat di thankyou page postback leads di jalankan',1,1),(4,'CONVERSIONS','triggernya saat CS ganti status jadi Confirm Buy',1,1),(5,'SALES','Trigernya saat Finance ganti status jadi SALE',1,1);

/*Data for the table `master_logistics` */

insert  into `master_logistics`(`logistic_id`,`sort`,`name`,`status`) values (1,1,'TIKI',1),(2,2,'JNE',1),(3,3,'J&T',1),(4,4,'POS Indonesia',1),(5,5,'GO-SEND (Khusus Jakarta))',1);

/*Data for the table `master_logistics_status` */

insert  into `master_logistics_status`(`logistics_status_id`,`sort`,`name`,`label`,`icon`) values (1,1,'PENDING_PACKING','Packing ditunda',NULL),(2,2,'NOTYET_PACKING','Belum di Packing',NULL),(3,3,'ALREDY_PACKING','Sudah di Packing',NULL),(4,4,'ALREDY_PICKUP','Sudah di Pickup',NULL),(5,5,'SHIPPING','Pengiriman',NULL),(6,6,'PROBLEM','Bermasalah',NULL),(7,7,'RECEIVED','Diterima',NULL);

/*Data for the table `master_order_status` */

insert  into `master_order_status`(`order_status_id`,`event_id`,`sort`,`name`,`label`,`color`,`icon`) values (1,3,1,'NEW_ORDER','New Order',NULL,NULL),(2,1,2,'FOLLOW_UP','Follow Up',NULL,NULL),(3,1,3,'PENDING','Pending',NULL,NULL),(4,1,4,'CANCEL','Cancel',NULL,NULL),(5,4,5,'CONFIRM_BUY','Confirm Buy',NULL,NULL),(6,5,6,'SALE','Sale',NULL,NULL),(7,1,7,'LOGISTICS','Logistics',NULL,NULL),(8,1,8,'FINISH','Finish',NULL,NULL);

/*Data for the table `master_payment_method` */

insert  into `master_payment_method`(`payment_method_id`,`name`,`desc`,`third_party`,`status`) values (1,'midtrans',NULL,1,0),(2,'BCA',NULL,0,1),(3,'BRI',NULL,0,1),(4,'Mandiri',NULL,0,1);

/*Data for the table `module_feature` */

insert  into `module_feature`(`feature_id`,`menu_id`,`name`,`label`,`status`) values (1,1,'users_view','View Users',1),(2,1,'users_list','Tambah User',1),(3,1,'users_add','Tambah User',1),(4,1,'users_upd','Ubah User',1),(5,1,'users_del','Hapus Users',0),(6,1,'users_role_list','View Role User',1),(7,1,'users_role_add','Tambah Role Users',1),(8,1,'users_role_del','Hapus Role User',1);

/*Data for the table `module_menu` */

insert  into `module_menu`(`menu_id`,`module_id`,`name`,`link`,`status`) values (1,1,'Users','users',1),(2,1,'Roles','roles',0),(3,1,'Access Control','access_control',0);

/*Data for the table `modules` */

insert  into `modules`(`module_id`,`link`,`name`,`status`) values (1,'sso','SSO',1),(2,'sales','Penjualan',0),(3,'logistics','Logistik',0),(4,'finance','Keuangan',0),(5,'management','Manajemen',0);

/*Data for the table `network` */

insert  into `network`(`network_id`,`name`,`status`) values (1,'Creathinker',1);

/*Data for the table `network_event` */

insert  into `network_event`(`network_event_id`,`network_id`,`event_id`,`callback_link`,`status`) values (1,1,3,'http://ctctrk.com/p.ashx?a=5&e=5&f=pb&r=',1),(2,1,4,'http://ctctrk.com/p.ashx?a=5&e=20&f=pb&r=',1),(3,1,5,'http://ctctrk.com/p.ashx?a=5&e=21&f=pb&r=',1);

/*Data for the table `network_order` */

/*Data for the table `order_invoices` */

/*Data for the table `order_logistics` */

/*Data for the table `order_process` */

/*Data for the table `orders` */

/*Data for the table `product` */

/*Data for the table `product_category` */

/*Data for the table `product_package` */

/*Data for the table `product_package_list` */

/*Data for the table `sso_role` */

insert  into `sso_role`(`role_id`,`name`,`label`,`status`) values (1,'ADMINISTRATOR','Administrator',1),(2,'MANAGER','Manajer',0),(3,'FINANCE','Keuangan',0),(4,'LOGISTICS','Logistik',0),(5,'Sales','Penjualan',0);

/*Data for the table `sso_role_access` */

insert  into `sso_role_access`(`role_access_id`,`role_id`,`module_id`,`menu_id`,`feature_id`,`feature_name`,`status`) values (1,1,1,1,1,'users_view',1),(2,1,1,1,1,'users_list',1),(3,1,1,1,1,'users_add',1),(4,1,1,1,1,'users_upd',1),(5,1,1,1,1,'users_del',1),(6,1,1,1,1,'users_role_view',1),(7,1,1,1,1,'users_role_add',1),(8,1,1,1,1,'users_role_del',1);

/*Data for the table `sso_user` */

insert  into `sso_user`(`user_id`,`username`,`password`,`email`,`first_name`,`last_name`,`status`) values (1,'admin','0cc175b9c0f1b6a831c399e269772661','admin@dermeva.co.id','Admin','',1);

/*Data for the table `sso_user_role` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
