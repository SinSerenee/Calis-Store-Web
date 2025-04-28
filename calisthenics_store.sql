# Host: localhost  (Version 5.5.5-10.4.32-MariaDB)
# Date: 2024-12-08 16:50:50
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "products"
#

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# Data for table "products"
#

INSERT INTO `products` VALUES (3,'Pull Up Bar','Untuk melatih sayap anda!',60.00,90,'uploads/Pull Up Bar.jpg'),(4,'Parallete wooden','Agar Anda bisa Handstand dan melatih stabilitas Anda!',45.00,20,'uploads/Wooden Paralletes.jpg'),(5,'Dips Parallete Bar','Untuk memperbanyak repetisi Dips Anda!',570.00,10,'uploads/Dips Paralletes Bar.jpg'),(6,'Gymnastic Ring','Untuk melatih otot core Anda!',99.00,99,'uploads/Gymnastics Rings.jpg'),(7,'Resistance Band','Karet lentur yang dapat melatih otot',80.00,99,'uploads/Resistance Band.jpg');

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member','user') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,'admin1','202cb962ac59075b964b07152d234b70','admin'),(2,'member2','289dff07669d7a23de0ef88d2f7129e7','member'),(3,'user3','d81f9c1be2e08964bf9f24b15f0e4900','user');

#
# Structure for table "transactions"
#

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `purchase_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# Data for table "transactions"
#


#
# Structure for table "purchases"
#

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# Data for table "purchases"
#


#
# Structure for table "purchase_history"
#

DROP TABLE IF EXISTS `purchase_history`;
CREATE TABLE `purchase_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchase_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `purchase_history_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# Data for table "purchase_history"
#

INSERT INTO `purchase_history` VALUES (4,2,3,'2024-12-07 21:22:25',1,60.00),(5,3,3,'2024-12-07 21:23:03',1,60.00),(6,3,3,'2024-12-07 21:30:10',1,60.00),(7,2,4,'2024-12-07 21:44:38',1,45.00),(8,3,5,'2024-12-07 21:44:57',1,570.00),(9,2,3,'2024-12-08 15:33:13',1,60.00),(10,2,3,'2024-12-08 15:35:27',0,0.00),(11,2,3,'2024-12-08 15:35:35',0,0.00),(12,3,3,'2024-12-08 15:37:16',2,120.00),(13,2,4,'2024-12-08 15:39:27',3,135.00),(14,2,4,'2024-12-08 15:42:54',1,45.00),(15,2,4,'2024-12-08 15:43:00',1,45.00),(16,2,5,'2024-12-08 15:43:10',1,570.00),(17,2,5,'2024-12-08 15:45:41',1,570.00),(18,3,4,'2024-12-08 15:45:55',3,135.00),(19,3,5,'2024-12-08 15:46:28',2,1140.00),(20,3,5,'2024-12-08 15:47:02',2,1140.00),(21,3,5,'2024-12-08 15:47:08',3,1710.00),(22,3,6,'2024-12-08 15:47:34',5,495.00),(23,2,6,'2024-12-08 15:47:48',1,99.00),(24,2,6,'2024-12-08 15:50:58',4,356.40),(25,3,7,'2024-12-08 15:56:13',5,400.00),(26,3,7,'2024-12-08 16:01:41',1,80.00),(27,2,7,'2024-12-08 16:06:58',1,80.00),(28,2,7,'2024-12-08 16:11:10',2,144.00),(29,3,3,'2024-12-08 16:13:13',1,60.00),(30,3,3,'2024-12-08 16:15:06',1,60.00),(31,3,3,'2024-12-08 16:16:33',2,120.00),(32,3,3,'2024-12-08 16:16:39',6,360.00),(33,2,6,'2024-12-08 16:18:17',1,89.10),(34,2,3,'2024-12-08 16:19:02',21,1134.00),(35,2,7,'2024-12-08 16:19:11',11,792.00),(36,2,3,'2024-12-08 16:26:14',5,270.00),(37,2,3,'2024-12-08 16:26:20',4,216.00);
