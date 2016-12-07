/*
SQLyog Ultimate v10.51 
MySQL - 5.6.14 : Database - erp_jxc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `log_invoke_api` */

DROP TABLE IF EXISTS `log_invoke_api`;

CREATE TABLE `log_invoke_api` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `service` varchar(100) NOT NULL COMMENT '接口服务',
  `request` text NOT NULL COMMENT '请求参数',
  `op` int(11) NOT NULL DEFAULT '0' COMMENT '操作员',
  `response` text COMMENT '返回信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `log_invoke_api` */

/*Table structure for table `log_order` */

DROP TABLE IF EXISTS `log_order`;

CREATE TABLE `log_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单类型',
  `ct_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户ID',
  `ct_name` varchar(20) NOT NULL DEFAULT '' COMMENT '客户名称',
  `total_rmb` int(11) NOT NULL DEFAULT '0' COMMENT '订单涉及总金额',
  `datetime` datetime NOT NULL COMMENT '订单时间',
  `op_id` int(10) NOT NULL DEFAULT '0' COMMENT '操作员ID',
  `op_name` varchar(20) NOT NULL DEFAULT '' COMMENT '操作员名称',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `log_order` */

insert  into `log_order`(`order_id`,`type`,`ct_id`,`ct_name`,`total_rmb`,`datetime`,`op_id`,`op_name`) values (8,2,0,'',400,'2016-11-23 00:13:34',1,'zzh_name'),(9,2,0,'',1500,'2016-11-23 23:02:20',1,'zzh_name'),(10,1,0,'',6850,'2016-11-26 22:57:34',1,'zzh_name'),(11,1,0,'',4000,'2016-11-26 23:02:05',1,'zzh_name'),(12,1,0,'',900,'2016-11-26 23:03:25',1,'zzh_name'),(13,2,0,'',11500,'2016-11-26 23:07:48',1,'zzh_name'),(14,2,0,'',0,'2016-11-27 17:35:05',1,'zzh_name'),(15,1,0,'',2000,'2016-11-27 21:47:08',1,'zzh_name'),(16,1,0,'',18000,'2016-11-29 20:09:09',1,'zzh_name'),(17,1,0,'',15200,'2016-12-04 01:09:37',1,'zzh_name');

/*Table structure for table `log_order_detail` */

DROP TABLE IF EXISTS `log_order_detail`;

CREATE TABLE `log_order_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单号',
  `pdt_id` varchar(50) NOT NULL DEFAULT '' COMMENT '产品货号',
  `pdt_counts` varchar(100) NOT NULL DEFAULT '' COMMENT '产品库存',
  `pdt_zk` smallint(5) NOT NULL DEFAULT '100' COMMENT '折扣  default: 100',
  `pdt_price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '进货价',
  `pdt_total` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '库存总数',
  `total_rmb` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '库存总价值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `log_order_detail` */

insert  into `log_order_detail`(`id`,`order_id`,`pdt_id`,`pdt_counts`,`pdt_zk`,`pdt_price`,`pdt_total`,`total_rmb`) values (12,8,'8820','||1|1|1|1||||',100,100.00,4.00,400.00),(13,9,'8816','||2|2|2|2|2|||',100,150.00,10.00,1500.00),(14,11,'8816','||2|2|2|2||||',100,500.00,8.00,4000.00),(15,13,'8816','||5|5|5|5|5|||',100,150.00,25.00,3750.00),(16,13,'8817','||2|2|2|2|2|||',100,150.00,10.00,1500.00),(17,13,'8819','||5|5|5|5|5|||',100,100.00,25.00,2500.00),(18,13,'8821','||5|5|5|5|5|||',100,150.00,25.00,3750.00),(19,14,'8815','||5|5|5|||||',100,0.00,15.00,0.00),(20,14,'8815-黑','|||5|5|||||',100,0.00,10.00,0.00),(21,14,'8818','|||5|5|5|5|5||',100,0.00,25.00,0.00),(22,14,'8827','||2|2|2|2|2|||',100,0.00,10.00,0.00),(23,15,'8816','||5|5||||||',100,200.00,10.00,2000.00),(24,16,'8816','|||10|10|10|10|10||',100,200.00,50.00,10000.00),(25,16,'8818','|||10|10|10|10|10||',100,50.00,50.00,2500.00),(26,16,'8821','|5|5|5|5|5||||',100,100.00,25.00,2500.00),(27,16,'8822','||5|5|5|5|5|5||',100,100.00,30.00,3000.00),(28,17,'8818','||10|10|10|10||||',100,130.00,40.00,5200.00),(29,17,'8819','||5|5|5|5||||',100,500.00,20.00,10000.00);

/*Table structure for table `log_procure` */

DROP TABLE IF EXISTS `log_procure`;

CREATE TABLE `log_procure` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `pdt_id` varchar(50) NOT NULL DEFAULT '',
  `ct_id` int(11) NOT NULL DEFAULT '0',
  `pdt_counts` varchar(100) NOT NULL DEFAULT '',
  `pdt_total` int(11) NOT NULL DEFAULT '0',
  `pdt_price` double(20,2) NOT NULL DEFAULT '0.00',
  `pdt_zk` double(20,2) NOT NULL DEFAULT '0.00',
  `total_rmb` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `log_procure` */

insert  into `log_procure`(`id`,`pdt_id`,`ct_id`,`pdt_counts`,`pdt_total`,`pdt_price`,`pdt_zk`,`total_rmb`,`datetime`) values (1,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(2,'8806',0,'0|1|2|3|5|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(3,'8807',0,'10|1|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(4,'8809',0,'8|10|35|3|4|5|6|7|8',0,115.00,100.00,9775,'0000-00-00 00:00:00'),(5,'8810',9,'9|18|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(6,'8805',9,'0|9|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(7,'8815',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(8,'8806',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(9,'8805',2,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(10,'8810',2,'0|1|2|99|4|5|6|7|8',132,115.00,100.00,15180,'2016-10-29 17:59:59'),(11,'8805',2,'0|1|2|5|4|5|6|7|8',38,115.00,100.00,4370,'2016-10-29 17:59:46'),(12,'8805',2,'0|12|12|22|0|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(13,'8805',2,'0|5|5|5|5|0|0|0|0',0,100.00,70.00,3920,'0000-00-00 00:00:00'),(20,'8828',2,'0|0|1|10|10|10|10|0|0',41,100.00,100.00,4100,'2016-10-29 17:36:15'),(21,'8828',2,'0|4|4|4|4|10|10|0|0',40,100.00,100.00,11600,'2016-10-29 16:52:42'),(22,'8829',2,'0|0|8|10|5|5|10',38,100.00,100.00,3800,'2016-10-29 18:20:46'),(23,'8830',2,'0|1|10|10|5|5|5|0|0|0',36,200.00,50.00,3600,'2016-10-29 18:20:51'),(24,'8831',1,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 17:59:25'),(25,'8832',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 18:22:01'),(29,'9003',2,'||6|3|2|2||||',13,100.00,100.00,1300,'2016-10-29 19:07:45'),(30,'9004',2,'||5|5|5|5||||',20,50.00,50.00,500,'2016-10-29 19:31:16'),(31,'9005',3,'||5|5|5|5||||',20,100.00,50.00,1000,'2016-10-29 19:32:26'),(32,'9006',1,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-29 19:32:26'),(33,'9007',1,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-29 19:34:35'),(34,'9008',1,'||3|3|3|3||||',12,100.00,100.00,1200,'2016-10-30 02:05:03'),(35,'9009',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 19:34:35'),(36,'105',2,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-30 02:47:54'),(37,'106',3,'||10|10|10|10||||',40,50.00,50.00,1000,'2016-10-30 02:47:54'),(38,'107',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-30 02:47:54');

/*Table structure for table `log_refund` */

DROP TABLE IF EXISTS `log_refund`;

CREATE TABLE `log_refund` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `pdt_id` varchar(50) NOT NULL DEFAULT '',
  `ct_id` int(11) NOT NULL DEFAULT '0',
  `pdt_counts` varchar(100) NOT NULL DEFAULT '',
  `pdt_total` int(11) NOT NULL DEFAULT '0',
  `pdt_price` double(20,2) NOT NULL DEFAULT '0.00',
  `pdt_zk` double(20,2) NOT NULL DEFAULT '0.00',
  `total_rmb` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `log_refund` */

insert  into `log_refund`(`id`,`pdt_id`,`ct_id`,`pdt_counts`,`pdt_total`,`pdt_price`,`pdt_zk`,`total_rmb`,`datetime`) values (1,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(2,'8806',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(3,'8807',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(4,'8809',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(5,'8810',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(6,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(7,'8815',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(8,'8806',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(9,'8805',2,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(10,'8810',2,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(11,'8805',2,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(12,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(13,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(14,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(15,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(16,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00');

/*Table structure for table `log_sales` */

DROP TABLE IF EXISTS `log_sales`;

CREATE TABLE `log_sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pdt_id` varchar(50) NOT NULL DEFAULT '',
  `ct_id` int(11) NOT NULL DEFAULT '0',
  `pdt_counts` varchar(100) NOT NULL DEFAULT '',
  `pdt_total` int(11) NOT NULL DEFAULT '0',
  `pdt_price` double(20,2) NOT NULL DEFAULT '0.00',
  `pdt_zk` double(20,2) NOT NULL DEFAULT '0.00',
  `total_rmb` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `log_sales` */

insert  into `log_sales`(`id`,`pdt_id`,`ct_id`,`pdt_counts`,`pdt_total`,`pdt_price`,`pdt_zk`,`total_rmb`,`datetime`) values (1,'8805',0,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(2,'8806',0,'0|1|2|3|5|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(3,'8807',0,'10|1|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(4,'8809',0,'8|10|35|3|4|5|6|7|8',0,115.00,100.00,9775,'0000-00-00 00:00:00'),(5,'8810',9,'9|18|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(6,'8805',9,'0|9|2|3|4|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(7,'8815',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(8,'8806',1,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(9,'8805',2,'0|1|2|3|4|5|6|7|8',0,115.00,100.00,10000,'0000-00-00 00:00:00'),(10,'8810',2,'0|1|2|99|4|5|6|7|8',132,115.00,100.00,15180,'2016-10-29 17:59:59'),(11,'8805',2,'0|1|2|5|4|5|6|7|8',38,115.00,100.00,4370,'2016-10-29 17:59:46'),(12,'8805',2,'0|12|12|22|0|5|6|7|8',0,115.00,100.00,4140,'0000-00-00 00:00:00'),(13,'8805',2,'0|5|5|5|5|0|0|0|0',0,100.00,70.00,3920,'0000-00-00 00:00:00'),(20,'8828',2,'0|0|1|10|10|10|10|0|0',41,100.00,100.00,4100,'2016-10-29 17:36:15'),(21,'8828',2,'0|4|4|4|4|10|10|0|0',40,100.00,100.00,11600,'2016-10-29 16:52:42'),(22,'8829',2,'0|0|8|10|5|5|10',38,100.00,100.00,3800,'2016-10-29 18:20:46'),(23,'8830',2,'0|1|10|10|5|5|5|0|0|0',36,200.00,50.00,3600,'2016-10-29 18:20:51'),(24,'8831',1,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 17:59:25'),(25,'8832',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 18:22:01'),(29,'9003',2,'||6|3|2|2||||',13,100.00,100.00,1300,'2016-10-29 19:07:45'),(30,'9004',2,'||5|5|5|5||||',20,50.00,50.00,500,'2016-10-29 19:31:16'),(31,'9005',3,'||5|5|5|5||||',20,100.00,50.00,1000,'2016-10-29 19:32:26'),(32,'9006',1,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-29 19:32:26'),(33,'9007',1,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-29 19:34:35'),(34,'9008',1,'||3|3|3|3||||',12,100.00,100.00,1200,'2016-10-30 02:05:03'),(35,'9009',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-29 19:34:35'),(36,'105',2,'||2|2|2|2||||',8,100.00,100.00,800,'2016-10-30 02:47:54'),(37,'106',3,'||10|10|10|10||||',40,50.00,50.00,1000,'2016-10-30 02:47:54'),(38,'107',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-30 02:47:54'),(39,'555',2,'||5|5|5|||||',15,150.00,75.00,1688,'2016-10-31 17:09:39'),(40,'556',2,'||5|5|5|5||||',20,100.00,100.00,2000,'2016-10-31 17:10:29');

/*Table structure for table `tb_colors` */

DROP TABLE IF EXISTS `tb_colors`;

CREATE TABLE `tb_colors` (
  `color_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `color_name` varchar(50) NOT NULL DEFAULT '',
  `color_rgba` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `tb_colors` */

insert  into `tb_colors`(`color_id`,`color_name`,`color_rgba`) values (1,'红色','FF0000'),(2,'绿色','00FF00'),(3,'蓝色','0000FF'),(4,'紫色','FF21F5'),(5,'黑色','000000'),(6,'白色','FFFFFF'),(7,'湖蓝','3D85C6'),(8,'深咖啡','783F0B'),(11,'军绿','6AA84F'),(12,'灰色','CCCCCC'),(13,'浅灰色','D9EAD3');

/*Table structure for table `tb_customer` */

DROP TABLE IF EXISTS `tb_customer`;

CREATE TABLE `tb_customer` (
  `ct_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户ID',
  `ct_name` varchar(50) NOT NULL DEFAULT '' COMMENT '客户姓名',
  `ct_address` varchar(200) NOT NULL DEFAULT '' COMMENT '通信地址',
  `ct_phone` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `ct_money` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `last_order` int(11) NOT NULL DEFAULT '0' COMMENT '上一个订单',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '客户状态[0:正常1:废弃]',
  PRIMARY KEY (`ct_id`),
  UNIQUE KEY `ct_name` (`ct_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `tb_customer` */

insert  into `tb_customer`(`ct_id`,`ct_name`,`ct_address`,`ct_phone`,`ct_money`,`last_order`,`status`) values (1,'周先生','杭州市下城区','15267468286',100.00,0,0),(2,'将及时','台湾省台北市','110',1000000.00,0,0),(4,'冼春磊','郑州和睦社区','12345678951',1000.00,0,0),(5,'曾楚楠','昆山市1','10235621359',800.00,0,0),(7,'军委会','南京','12345623512',1000000.00,0,0),(8,'李小龙','什么鬼地方我也不知道','111111111',100.00,0,0),(9,'周灼华','杭州市下城区文晖街道流水东苑25-5幢402室','15267468286',10000.00,0,0),(10,'黄子韬','yyyyyy','11100101010',10000.00,0,0);

/*Table structure for table `tb_operator` */

DROP TABLE IF EXISTS `tb_operator`;

CREATE TABLE `tb_operator` (
  `op_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '操作员ID',
  `op_account` varchar(20) NOT NULL DEFAULT '' COMMENT '登录名',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `op_psw` varchar(50) NOT NULL COMMENT '登录密钥 - md5(str)',
  `op_name` varchar(20) NOT NULL COMMENT '操作员名称',
  `op_phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `op_auth` text NOT NULL COMMENT '操作员权限',
  PRIMARY KEY (`op_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tb_operator` */

insert  into `tb_operator`(`op_id`,`op_account`,`status`,`op_psw`,`op_name`,`op_phone`,`op_auth`) values (1,'zzh',0,'44b433a30dabd30fd57fc3209b076a03','zzh_name','','{\"all_allow\":1}');

/*Table structure for table `tb_pdt_stock` */

DROP TABLE IF EXISTS `tb_pdt_stock`;

CREATE TABLE `tb_pdt_stock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pdt_id` varchar(50) NOT NULL DEFAULT '',
  `pdt_name` varchar(50) NOT NULL DEFAULT '',
  `pdt_color` int(11) NOT NULL DEFAULT '0',
  `pdt_stock` varchar(100) NOT NULL DEFAULT '',
  `pdt_purchase` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_pdt_stock` */

/*Table structure for table `tb_product` */

DROP TABLE IF EXISTS `tb_product`;

CREATE TABLE `tb_product` (
  `pdt_id` varchar(50) NOT NULL DEFAULT '' COMMENT '产品货号',
  `pdt_name` varchar(100) NOT NULL DEFAULT '' COMMENT '产品名称',
  `pdt_color` int(11) NOT NULL DEFAULT '0' COMMENT '产品颜色',
  `pdt_counts` varchar(100) NOT NULL DEFAULT '' COMMENT '产品库存',
  `pdt_cost` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `pdt_price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '标价',
  `pdt_total` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '库存总数',
  `total_rmb` double(20,2) NOT NULL DEFAULT '0.00' COMMENT '库存总价值',
  `datetime` datetime NOT NULL COMMENT '记录时间',
  `timeLastOp` datetime NOT NULL COMMENT '最后一次操作时间',
  `flag` tinyint(3) NOT NULL DEFAULT '0' COMMENT '[0]有效数据  [1]废弃的',
  PRIMARY KEY (`pdt_id`),
  UNIQUE KEY `pdt_id` (`pdt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_product` */

insert  into `tb_product`(`pdt_id`,`pdt_name`,`pdt_color`,`pdt_counts`,`pdt_cost`,`pdt_price`,`pdt_total`,`total_rmb`,`datetime`,`timeLastOp`,`flag`) values ('1981','吊牌2',3,'|||||||||',0.00,598.00,0.00,0.00,'0000-00-00 00:00:00','2016-11-20 00:34:10',1),('1982','信使',2,'||10|10|10000|10||||',0.00,100.00,0.00,0.00,'0000-00-00 00:00:00','2016-11-20 00:34:10',1),('1989','1989-x',8,'|||||||||',0.00,100.00,0.00,0.00,'2016-11-12 06:21:34','2016-11-20 00:55:05',1),('8815','羊绒-A',12,'||10|10|10|5||||',0.00,200.00,35.00,7000.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8815-黑','8815',7,'||2|7|7|2||||',0.00,100.00,18.00,1800.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8816','风衣-X',11,'0|0|20|30|25|25|17|10|0|0',0.00,200.00,127.00,25400.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8817','风衣-A',3,'||7|7|7|7|2|||',0.00,100.00,30.00,3000.00,'0000-00-00 00:00:00','2016-11-30 23:05:52',0),('8818','衬衣-Z',8,'||12|27|27|27|17|15||',0.00,50.00,125.00,6250.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8819','sdf',2,'||12|12|12|12|7|||',0.00,2.00,55.00,110.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8820','45',7,'0|0|3|3|3|10|0|0|0|0',0.00,2.00,19.00,38.00,'0000-00-00 00:00:00','0000-00-00 00:00:00',0),('8821','8821-*',1,'|5|10|10|10|10|5|||',0.00,100.00,50.00,5000.00,'2016-11-12 08:03:28','0000-00-00 00:00:00',0),('8822','8822/5',4,'||5|5|5|5|5|5||',0.00,100.00,30.00,3000.00,'2016-11-12 16:07:46','0000-00-00 00:00:00',0),('8823','8823-4',6,'0|0|0|5|5|5|5|0|0|0',0.00,100.00,20.00,2000.00,'2016-11-12 16:17:47','0000-00-00 00:00:00',0),('8824','1',7,'|||||||||',0.00,100.00,0.00,0.00,'2016-11-12 17:30:32','2016-11-22 23:18:38',1),('8825','衣服8825',3,'|||||||||',0.00,150.00,0.00,0.00,'2016-11-22 22:05:42','2016-11-22 23:18:38',1),('8826','衣服8826',11,'|||||||||',0.00,200.00,0.00,0.00,'2016-11-22 22:53:02','2016-11-22 23:18:38',1),('8827','8827',5,'0|0|2|22|22|22|22|0|0|0',0.00,90.00,90.00,8100.00,'2016-11-22 23:17:40','2016-11-30 23:06:07',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
