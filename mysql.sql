/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.0.45 : Database - library
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`library` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `library`;

/*Table structure for table `books` */

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `title` varchar(255) NOT NULL COMMENT '书名',
  `author` varchar(255) NOT NULL COMMENT '作者',
  `category_id` int DEFAULT NULL COMMENT '分类',
  `description` text COMMENT '简介',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面URL',
  `stock` int DEFAULT '0' COMMENT '总库存',
  `available` int DEFAULT '0' COMMENT '可借数量',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `category` (`category_id`),
  CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `books` */

insert  into `books`(`id`,`title`,`author`,`category_id`,`description`,`cover`,`stock`,`available`,`created_at`,`updated_at`) values 
(2,'现代汉语词典','中国社会科学院',2,'<p style=\"text-indent: 2em;\">《现代汉语词典》是一部以记录普通话语汇为主的现代汉语规范型词典，也是学习、研究和正确使用现代汉语的重要工具书。全书收录了大量现代汉语中的常用字、词、短语、成语以及近年来出现的新词新义，并对词语的读音、词义、词性和具体用法进行了清晰、准确的解释。</p><p style=\"text-indent: 2em;\">本词典内容丰富，编排科学，注释简明易懂。在每个词条中，不仅提供规范的汉语拼音和基本释义，还通过必要的例句和用法说明，帮助读者更加准确地理解词语在不同语言环境中的含义和使用方式。同时，词典对容易读错、写错或混淆的字词进行了规范说明，对提高读者的汉语阅读、写作和语言表达能力具有重要的参考价值。</p><p style=\"text-indent: 2em;\">作为一部实用性较强的语言工具书，《现代汉语词典》适用于中小学生、大学生、教师、文字工作者以及广大汉语学习者。无论是在日常学习中查询生词、理解词义，还是在写作过程中确认词语的正确用法，都能够为读者提供可靠的参考。</p><p style=\"text-indent: 2em;\">随着社会发展和语言变化，《现代汉语词典》也不断进行修订和完善，及时收录具有广泛影响的新词语、新表达和新的词义，反映现代汉语的发展变化。它不仅是一部方便实用的查阅工具，也是一部能够帮助读者深入了解现代汉语语言特点和中华语言文化的重要参考书。</p>','6a4a87d1702d2',10,9,'2026-05-02 19:28:07','2026-07-08 02:25:19'),
(4,'活着','余华',1,'<h2>《活着》——余华</h2>\r\n<p>\r\n《活着》是作家<strong>余华</strong>创作的一部长篇小说，被认为是中国当代文学中最具影响力的作品之一。\r\n</p>\r\n<h3>故事简介</h3>\r\n<p>\r\n小说讲述了主人公<strong>徐福贵</strong>从富家少爷到普通农民的一生经历。\r\n他年轻时因赌博败光家产，从此人生急转直下。\r\n</p>\r\n<ul>\r\n  <li>父亲被气死</li>\r\n  <li>母亲病逝</li>\r\n  <li>儿子因医疗事故去世</li>\r\n  <li>女儿难产死亡</li>\r\n  <li>妻子因劳累去世</li>\r\n</ul>\r\n<p>\r\n尽管经历接连不断的打击，福贵依然活着，最后与一头老牛相依为伴。\r\n</p>\r\n<h3>主题思想</h3>\r\n\r\n<p>\r\n本书探讨的核心是：\r\n<strong>人在失去一切之后，为什么还要继续活着。</strong>\r\n</p>\r\n<p>\r\n它表达了三个重要主题：\r\n</p>\r\n<ol>\r\n  <li><strong>活着本身就是意义</strong></li>\r\n  <li><strong>命运的不可控性</strong></li>\r\n  <li><strong>苦难的日常化与真实感</strong></li>\r\n</ol>\r\n<h3>作品特点</h3>\r\n<ul>\r\n  <li>语言极其简洁，没有华丽修辞</li>\r\n  <li>叙事克制，但情绪冲击强烈</li>\r\n  <li>用平静方式讲述极端悲剧</li>\r\n</ul>\r\n<blockquote>\r\n“人是为了活着本身而活着，而不是为了活着之外的任何事物所活着。”\r\n</blockquote>','4',8,7,'2026-05-02 19:28:07','2026-07-05 20:53:42'),
(5,'西游记','吴承恩',3,'中国古典四大名著之一。','5',6,5,'2026-05-02 19:28:07','2026-07-14 23:01:42'),
(6,'红楼梦','曹雪芹',3,'中国古典小说巅峰之作。','6',5,5,'2026-05-02 19:28:07','2026-05-02 19:28:07'),
(7,'安徒生童话','安徒生',4,'经典童话故事合集。','7',9,8,'2026-05-02 19:28:07','2026-07-05 20:53:53'),
(8,'格林童话','格林兄弟',4,'世界著名童话集。','8',7,6,'2026-05-02 19:28:07','2026-07-05 20:53:26'),
(9,'十万个为什么','叶永烈',2,'儿童科普读物，解答各种问题。','9',11,11,'2026-05-02 19:28:07','2026-05-02 19:28:07'),
(10,'小王子','圣埃克苏佩里',4,'关于成长与爱的寓言故事。','10',10,9,'2026-05-02 19:28:07','2026-07-14 23:01:52'),
(11,'围城','钱钟书',1,'描写婚姻与人生的讽刺小说。','11',6,6,'2026-05-02 19:28:07','2026-05-02 19:28:07'),
(12,'平凡的世界','路遥',1,'<p>反映普通人奋斗的现实主义作品。</p>','12',8,8,'2026-05-02 19:28:07','2026-07-13 21:00:37'),
(13,'新华字典（第11版）','商务印书馆',2,'中小学生常用字典，收录规范汉字。','1',2,2,'2026-05-02 19:28:07','2026-05-02 19:28:07');

/*Table structure for table `borrow` */

DROP TABLE IF EXISTS `borrow`;

CREATE TABLE `borrow` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '借阅记录ID',
  `user_id` int unsigned DEFAULT NULL,
  `book_id` int unsigned DEFAULT NULL,
  `borrow_time` date NOT NULL COMMENT '借书时间',
  `due_time` date NOT NULL COMMENT '应还时间',
  `return_time` date DEFAULT NULL COMMENT '实际归还时间',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '状态 0借阅中 \n1已归还 \n2已逾期\n 3丢失',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `root_confirm` int DEFAULT '0' COMMENT '0管理员未确认 1管理员已确认',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `borrow` */

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varbinary(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `category` */

insert  into `category`(`id`,`name`) values 
(1,'小说'),
(2,'工具书'),
(3,'文学'),
(4,'少儿'),
(11,'岁的法国');

/*Table structure for table `info` */

DROP TABLE IF EXISTS `info`;

CREATE TABLE `info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varbinary(255) DEFAULT NULL,
  `content` text COMMENT '内容',
  `created_at` date DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `info` */

insert  into `info`(`id`,`title`,`content`,`created_at`) values 
(1,'系统上线通知','<h1>图书管理系统正式上线</h1>\r\n<p>欢迎使用图书管理系统！本系统已正式上线，提供图书查询、分类浏览等功能。</p>','2026-06-30'),
(2,'开放时间调整','<p>图书馆开放时间调整为：<strong>08:00 - 22:00</strong>，请合理安排借阅时间。</p>','2026-06-30'),
(3,'借阅规则说明','<p>每位用户最多可借阅 <strong>5</strong> 本图书，借阅期限为 <strong>30天</strong>，请按时归还。</p>','2026-06-30'),
(5,'新书上架公告','<p>本周新增多本热门图书，欢迎前往“图书展示”页面查看最新资源。</p>','2026-06-30'),
(12,'价格说明','<h1 style=\"text-align: center;\">价格说明</h1><p>本价格只包含代码：</p><ol><ol><ol><ol><li>后端（php-&gt;laravel）</li><li>前端（vue3）</li><li>数据库（mysql）</li></ol></ol></ol></ol><p>其他均不包含：</p><ol><ol><ol><ol><li>讲解</li><li>调试</li><li>运行</li></ol></ol></ol></ol><p>代码如果有如何bug请及时联系我，非bug不修改，不帮忙改或者定制功能。</p><p>得加钱（表情包）</p>','2026-06-30');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_05_05_065922_create_personal_access_tokens_table',2);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values 
(1,'App\\Models\\User',3,'login_token','5b492679675ef6ebb2200b1e62f515b03229f66999e443a72bfbaeca606eb31b','[\"*\"]',NULL,NULL,'2026-05-05 06:59:31','2026-05-05 06:59:31'),
(2,'App\\Models\\User',3,'login_token','7ba13f81dac7d65d7f66355e3be30f70809128f33ffc3962c48ff4e85debb3e7','[\"*\"]',NULL,NULL,'2026-05-05 06:59:56','2026-05-05 06:59:56'),
(3,'App\\Models\\User',3,'login_token','97bec2a6d82fcfbf1528ff144c175691b805e37abf23b4bb82c521df6fda93c5','[\"*\"]',NULL,NULL,'2026-05-05 07:10:15','2026-05-05 07:10:15'),
(4,'App\\Models\\User',3,'login_token','e08f5b376ef4e573338119ef77f872b6b8b93dab9807196814da5138d0c5d340','[\"*\"]','2026-05-05 07:24:36',NULL,'2026-05-05 07:10:58','2026-05-05 07:24:36'),
(11,'App\\Models\\User',3,'login_token','dfaa482b80c7eef66379da5b83a29caf9ef255b2b4708e16c325a1b817cad081','[\"*\"]','2026-05-10 00:27:29',NULL,'2026-05-09 15:18:14','2026-05-10 00:27:29'),
(12,'App\\Models\\User',3,'login_token','e6e5281e525edd505f056b14c8da39d0285e5659d9e04c1e405483870977decc','[\"*\"]','2026-05-09 16:18:50',NULL,'2026-05-09 16:14:33','2026-05-09 16:18:50'),
(14,'App\\Models\\User',3,'login_token','2764131f04ccda8d613e830c142c1681eb6291e69a11682796df75f3fe17b14d','[\"*\"]','2026-05-12 16:33:25',NULL,'2026-05-12 16:18:12','2026-05-12 16:33:25'),
(15,'App\\Models\\User',3,'login_token','0a9ade3073a6c5d0a45c6fc9161b4104cafa5647a91b11e6196e417bb6a33234','[\"*\"]','2026-05-13 16:11:01',NULL,'2026-05-13 16:09:40','2026-05-13 16:11:01'),
(16,'App\\Models\\User',3,'login_token','168b8c77f7d49bee3aee6a5c887b564318b0cd40f364d4da51e49811abeb15c8','[\"*\"]','2026-05-13 17:23:45',NULL,'2026-05-13 16:33:50','2026-05-13 17:23:45'),
(17,'App\\Models\\User',3,'login_token','ba74239405fb12ba74673dd8ddaa93b8983b9e1cd70bc7c054efa4f28dc9daba','[\"*\"]','2026-05-16 19:23:02',NULL,'2026-05-16 16:57:06','2026-05-16 19:23:02'),
(18,'App\\Models\\User',3,'login_token','f655879b3ff3d8237316f3e0ff3251208634d692f000b2759ae425ec52db9b24','[\"*\"]','2026-05-17 01:35:00',NULL,'2026-05-17 01:00:22','2026-05-17 01:35:00'),
(19,'App\\Models\\User',3,'login_token','e8d1801f6ef8a2692dd66c5c1f974bcf27ca51df041ed9ebdde0fb9e3f625fee','[\"*\"]','2026-05-18 19:46:43',NULL,'2026-05-18 19:38:13','2026-05-18 19:46:43'),
(20,'App\\Models\\User',3,'login_token','c8e2e9c8875f1070ef8bfd798a29b54e18acbea9eab6e12bee555f90f4786e41','[\"*\"]','2026-05-23 19:15:37',NULL,'2026-05-23 18:26:40','2026-05-23 19:15:37'),
(22,'App\\Models\\User',3,'login_token','6bec6ca51a5ebb9154c2890b0c6481664ebf149a70c61e68c0efc2a8fe4f7e79','[\"*\"]','2026-05-25 19:26:49',NULL,'2026-05-25 19:03:12','2026-05-25 19:26:49'),
(23,'App\\Models\\User',3,'login_token','068cf3cfa9370dd9e4c9315c5c3c8184dca1367044364945697bc2b3fc5da9a1','[\"*\"]','2026-06-27 19:31:30',NULL,'2026-06-27 19:31:28','2026-06-27 19:31:30'),
(27,'App\\Models\\User',3,'login_token','9ec8c1656619386b3c3a3f14a0f4fef579ff41d78a87129222573019dad158ed','[\"*\"]','2026-06-27 20:14:36',NULL,'2026-06-27 20:14:31','2026-06-27 20:14:36'),
(28,'App\\Models\\Root',8,'login_token','b5d62597af1b735e57f0f234cee1123905129bad4fdc0c4d87ef7afa962ac3ae','[\"*\"]',NULL,NULL,'2026-06-28 20:40:48','2026-06-28 20:40:48'),
(29,'App\\Models\\Root',8,'login_token','dc681b2fb0274daaa6ff8dd35259c875deeedf2541d14a8d0165a9d0162c7d4d','[\"*\"]',NULL,NULL,'2026-06-28 20:41:15','2026-06-28 20:41:15'),
(30,'App\\Models\\Root',8,'login_token','b7e4e6a3b34cc7c367730137091e74747d5b9cb8bbc26b40952167256df9fbf2','[\"*\"]',NULL,NULL,'2026-06-28 20:41:33','2026-06-28 20:41:33'),
(31,'App\\Models\\Root',8,'login_token','ec0a87c0a47569830484548bf2ba080de4c0acd30e30550e06f955c57a57b695','[\"*\"]',NULL,NULL,'2026-06-28 20:41:54','2026-06-28 20:41:54'),
(32,'App\\Models\\Root',8,'login_token','e91e628fca9dfb254f236059c1f25c4cbd2eb1c6bc779f2b9bf931d7de7c9e3b','[\"*\"]',NULL,NULL,'2026-06-28 20:42:53','2026-06-28 20:42:53'),
(33,'App\\Models\\Root',8,'login_token','ba9eef58222302870f948bc428a2172cf8fecd78b55f8149abbd45aa91e9082b','[\"*\"]','2026-06-28 22:23:58',NULL,'2026-06-28 21:04:47','2026-06-28 22:23:58'),
(35,'App\\Models\\Root',8,'login_token','59f1385e990948af95c5acd34e331f6d2f1fb2ba022d867530d97a53314f20f8','[\"*\"]',NULL,NULL,'2026-06-28 22:23:22','2026-06-28 22:23:22'),
(36,'App\\Models\\Root',8,'login_token','b0c3159bbdabb37290a059d45d05c1618c7355b7552440250a5e95fdfdb6d116','[\"*\"]','2026-06-30 18:13:11',NULL,'2026-06-28 22:23:38','2026-06-30 18:13:11'),
(37,'App\\Models\\Root',8,'login_token','9945efbf1e698b18237e5100cc410b902ef230efbfb877139594f25d5526c1c1','[\"*\"]',NULL,NULL,'2026-06-28 22:23:57','2026-06-28 22:23:57'),
(39,'App\\Models\\Root',8,'login_token','84beffe75c4530303258e6119b0db28037f850fa6b27fa6cb61330cd36b9472d','[\"*\"]',NULL,NULL,'2026-06-28 22:24:29','2026-06-28 22:24:29'),
(41,'App\\Models\\Root',8,'login_token','3269b928221b224da7a934377422e7c84cc13fe352c13751135bbb5d492a58f7','[\"*\"]',NULL,NULL,'2026-06-28 22:26:00','2026-06-28 22:26:00'),
(43,'App\\Models\\Root',8,'login_token','94ea9d1ea16d696e6eeacaebe19f7b58912309bfd69e649edccb0f6ee9452e2d','[\"*\"]',NULL,NULL,'2026-06-28 22:28:13','2026-06-28 22:28:13'),
(44,'App\\Models\\Root',8,'login_token','f7d5a40161514ad0dde64ea826f87f9298b1bd6d9869d30b03d6a7ae724701bd','[\"*\"]',NULL,NULL,'2026-06-28 22:28:46','2026-06-28 22:28:46'),
(45,'App\\Models\\Root',8,'login_token','d2ae9abaad9dc1fa807704a1bba0726504b23563d6e3f4672f8072634bcd51ed','[\"*\"]','2026-06-28 22:30:12',NULL,'2026-06-28 22:30:10','2026-06-28 22:30:12'),
(47,'App\\Models\\Root',8,'login_token','fd86a59d178a111a80478f1ae49be50c005bebd666f4990a72df4d4f2544d6c7','[\"*\"]','2026-06-28 22:42:35',NULL,'2026-06-28 22:30:32','2026-06-28 22:42:35'),
(48,'App\\Models\\Root',8,'login_token','a7f150d0e251ecad13df56f117d06785e414de0dabe2a303094dad02a83d36d1','[\"*\"]','2026-06-29 23:37:41',NULL,'2026-06-29 23:09:26','2026-06-29 23:37:41'),
(54,'App\\Models\\Root',8,'login_token','a645cce77ab70d80b2a0e9451472cb0c0cda522595e8f4c7cc2c3ee121330911','[\"*\"]','2026-06-30 00:01:10',NULL,'2026-06-29 23:55:35','2026-06-30 00:01:10'),
(55,'App\\Models\\User',3,'login_token','12fb0514ef547f07d5e7c877cfb1c90ec5a1566ec702add4566f66596bb3ba59','[\"*\"]','2026-06-29 23:59:03',NULL,'2026-06-29 23:59:01','2026-06-29 23:59:03'),
(58,'App\\Models\\User',3,'login_token','15e57c9d4714a2e2b9d8d267ddde281142acd6d0cc01369589b5185c4ab7eb2e','[\"*\"]','2026-06-30 18:13:21',NULL,'2026-06-30 18:13:19','2026-06-30 18:13:21'),
(60,'App\\Models\\Root',8,'login_token','17529ce029f0bad989efd6679bee10ff30ca098e3132a536c1b49758c169522a','[\"*\"]','2026-06-30 20:32:15',NULL,'2026-06-30 20:14:37','2026-06-30 20:32:15'),
(61,'App\\Models\\Root',8,'login_token','13d31c389075232675987db9a2b8a55c2d37924acb4018e06ee83582eaa17b4b','[\"*\"]','2026-06-30 21:36:49',NULL,'2026-06-30 20:45:20','2026-06-30 21:36:49'),
(62,'App\\Models\\Root',8,'login_token','32d63b4bac2319125ecc0bdb7771687451677251275a87b77b62f0d2eb451ede','[\"*\"]','2026-07-01 13:41:58',NULL,'2026-07-01 13:00:00','2026-07-01 13:41:58'),
(64,'App\\Models\\Root',8,'login_token','a731ed16c739093efaea200426bd3a5b950a698f17ed1502c0f3dcd1ccc9062c','[\"*\"]','2026-07-01 18:12:54',NULL,'2026-07-01 17:04:15','2026-07-01 18:12:54'),
(65,'App\\Models\\User',9,'login_token','8d855cefd798b0cbaad5dc07e55cf8bbd81ee51d41563dadab9dd6b2892f3135','[\"*\"]','2026-07-01 18:09:43',NULL,'2026-07-01 17:44:31','2026-07-01 18:09:43'),
(66,'App\\Models\\Root',8,'login_token','179144a4aa0b12853404d20839afe82a27364b3c812e4c1962107d73c401c575','[\"*\"]','2026-07-02 21:25:26',NULL,'2026-07-02 20:56:04','2026-07-02 21:25:26'),
(67,'App\\Models\\User',10,'login_token','b9e2e16f6abbf7c08833d55671382e6ebe334b68edb44e51c6b311eb23846f1a','[\"*\"]','2026-07-02 21:04:41',NULL,'2026-07-02 21:04:40','2026-07-02 21:04:41'),
(68,'App\\Models\\Root',8,'login_token','222126a4267e7128e055b22cef5262bc7c160670a3d560d9d75c99ae3f14fd02','[\"*\"]','2026-07-04 18:36:34',NULL,'2026-07-04 18:20:33','2026-07-04 18:36:34'),
(69,'App\\Models\\Root',8,'login_token','381a9b451fa82c57fd8359a7508a1b8be12fd676b8c009b4bea9a1d9c26177b1','[\"*\"]','2026-07-05 21:32:26',NULL,'2026-07-05 19:58:11','2026-07-05 21:32:26'),
(70,'App\\Models\\User',3,'login_token','05890bc93cecdd267c723c4543bf9251c9766cd2f84857d85b5c5e0a2b4ca0ba','[\"*\"]','2026-07-05 20:54:45',NULL,'2026-07-05 20:52:52','2026-07-05 20:54:45'),
(72,'App\\Models\\Root',8,'login_token','cb8459f5a508f9f87b2ca59b7c382281ecbece0d54b75cb54fb7b961d7adbfda','[\"*\"]','2026-07-08 02:19:32',NULL,'2026-07-08 02:19:06','2026-07-08 02:19:32'),
(75,'App\\Models\\Root',8,'login_token','572f1416afde206dfbfe471a020b9738f01ebdc8cd096027ceefa640257607cc','[\"*\"]','2026-07-13 18:11:44',NULL,'2026-07-12 22:14:38','2026-07-13 18:11:44'),
(76,'App\\Models\\User',12,'login_token','9a0a6782d0a82bd46690557da873c4d30d4a70277dab59b2ed0d1ddd4ce74850','[\"*\"]','2026-07-12 22:27:25',NULL,'2026-07-12 22:26:40','2026-07-12 22:27:25'),
(80,'App\\Models\\Root',8,'login_token','5b8fe77d0312082da9b541b545ac14f69f174fde72a0513a85174160b760d96b','[\"*\"]','2026-07-13 21:00:37',NULL,'2026-07-13 20:12:42','2026-07-13 21:00:37'),
(81,'App\\Models\\User',12,'login_token','8bd506b54213122e0d89933485521f4924a1a9376ac762ef0e8e6e401ac91896','[\"*\"]','2026-07-13 21:01:14',NULL,'2026-07-13 20:13:52','2026-07-13 21:01:14'),
(84,'App\\Models\\User',12,'login_token','0c82ffd128476357e3e6f93c41488f024d270a161016714b32b6ae5736e59e37','[\"*\"]','2026-07-14 23:02:03',NULL,'2026-07-14 23:01:32','2026-07-14 23:02:03');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `email` varchar(100) NOT NULL COMMENT '邮箱（登录用）',
  `password` varchar(255) NOT NULL COMMENT '密码（加密存储）',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'avatars/mr.jpg' COMMENT '头像URL',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `role` enum('user','root') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user' COMMENT '角色',
  `status` tinyint DEFAULT '1' COMMENT '状态：1正常 0禁用',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `baned_at` timestamp NULL DEFAULT NULL COMMENT '禁用时间',
  `ban_why` varbinary(255) DEFAULT NULL COMMENT '禁用理由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='用户表';

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
