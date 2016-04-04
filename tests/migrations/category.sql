SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (`id` int(11) NOT NULL, `guid` varchar(255) DEFAULT NULL, `title` varchar(255) DEFAULT NULL, `lft` int(11) DEFAULT NULL, `rgt` int(11) DEFAULT NULL, `depth` int(11) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `category` (`id`, `guid`, `title`, `lft`, `rgt`, `depth`) VALUES (1, 'root', 'ROOT', 1, 38, 0), (19, 'root_1', 'Root #1', 2, 3, 1), (2, 'root_2', 'Root #2', 4, 15, 1), (17, 'child_2_1', 'Child #2.1', 5, 6, 2), (3, 'child_2_2', 'Child #2.2', 7, 12, 2), (18, 'child_2_2_1', 'Child #2.2.1', 8, 9, 3), (4, 'child_2_2_2', 'Child #2.2.2', 10, 11, 3), (5, 'child_2_3', 'Child #2.3', 13, 14, 2), (6, 'root_3', 'Root #3', 16, 25, 1), (9, 'child_3_1', 'Child #3.1', 17, 22, 2), (10, 'child_3_1_1', 'Child #3.1.1', 18, 19, 3), (8, 'child_3_1_2', 'Child #3.1.2', 20, 21, 3), (7, 'child_3_2', 'Child #3.2', 23, 24, 2), (11, 'root_4', 'Root #4', 26, 37, 1), (12, 'child_4_1', 'Child #4.1', 27, 34, 2), (15, 'child_4_1_1', 'Child #4.1.1', 28, 29, 3), (16, 'child_4_2', 'Child #4.2', 35, 36, 2);
ALTER TABLE `category` ADD PRIMARY KEY (`id`);
SET FOREIGN_KEY_CHECKS=1;