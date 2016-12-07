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
CREATE TABLE `log_invoke_api` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一ID',
  `service` varchar(100) NOT NULL COMMENT '接口服务',
  `request` text NOT NULL COMMENT '请求参数',
  `op` int(11) NOT NULL DEFAULT '0' COMMENT '操作员',
  `response` text COMMENT '返回信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
