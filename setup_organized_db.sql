create database organized_db;

use organized_db;

CREATE TABLE `organized` ( `id` int(11) NOT NULL AUTO_INCREMENT, `datum` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `datum_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `my_labels` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, `datum_source` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `datum_source_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `context` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `meaning` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `examples` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `play` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `my_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `easyness_intensity` int(11) NOT NULL, `emotions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, `time_of_insert` datetime DEFAULT CURRENT_TIMESTAMP, `time_of_modification` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `basic_emotions` ( `id` int(11) NOT NULL AUTO_INCREMENT, `pe` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `se` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin, `te` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, PRIMARY KEY (`te`), UNIQUE KEY `id` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `complex_emotions` ( `id` int(11) NOT NULL AUTO_INCREMENT, `complex_emotion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, `description` text, PRIMARY KEY (`complex_emotion`), UNIQUE KEY `id` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `datum_sources` ( `id` int(11) NOT NULL AUTO_INCREMENT, `datum_source` varchar(255) NOT NULL, PRIMARY KEY (`datum_source`), UNIQUE KEY `id` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `my_labels` ( `id` int(11) NOT NULL AUTO_INCREMENT, `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, PRIMARY KEY (`label`), UNIQUE KEY `id` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `notes_of_days` ( `id` int(11) NOT NULL AUTO_INCREMENT, `note` text, `date` datetime DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `date` (`date`) ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `iwr_pile` ( `id` int(11) NOT NULL AUTO_INCREMENT, `datum_type` text, `front_col` text, `back_col` text, `card` text, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `iww_pile` ( `id` int(11) NOT NULL AUTO_INCREMENT, `datum_type` text, `front_col` text, `back_col` text, `card` text, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

