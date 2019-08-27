-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 27, 2019 at 01:03 PM
-- Server version: 10.1.41-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goodmarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('superAdmin', '1', 1566887620);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('superAdmin', 1, 'Главный админ', NULL, NULL, 1566887620, 1566887620);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `publish` smallint(6) DEFAULT NULL,
  `publish_status` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `stock_id`, `parent_id`, `media_id`, `alias`, `position`, `rating`, `publish`, `publish_status`) VALUES
(1, 1, 0, 1, 'categ1', 1, 1, 1, 1),
(2, 2, 1, 1, 'categ2', 2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_characteristic`
--

CREATE TABLE `category_characteristic` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `characteristic_id` int(11) DEFAULT NULL,
  `prod_count` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_lang`
--

CREATE TABLE `category_lang` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category_lang`
--

INSERT INTO `category_lang` (`id`, `category_id`, `lang_id`, `name`) VALUES
(1, 1, 1, 'категорияя 1'),
(2, 1, 2, 'category 1');

-- --------------------------------------------------------

--
-- Table structure for table `filemanager_mediafile`
--

CREATE TABLE `filemanager_mediafile` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `alt` text COLLATE utf8_unicode_ci,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `thumbs` text COLLATE utf8_unicode_ci,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filemanager_owners`
--

CREATE TABLE `filemanager_owners` (
  `mediafile_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `owner_attribute` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lang`
--

CREATE TABLE `lang` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `priority` tinyint(3) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lang`
--

INSERT INTO `lang` (`id`, `alias`, `name`, `priority`, `status`) VALUES
(1, 'ru', 'Русский', 1, 1),
(2, 'eng', 'English', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1566887606),
('m130524_201440_rbac_init', 1566887619),
('m130524_201441_rbac_add_index_on_auth_assignment_user_id', 1566887619),
('m130524_201442_init', 1566887620),
('m141129_130551_create_filemanager_mediafile_table', 1566887620),
('m141203_173402_create_filemanager_owners_table', 1566887621),
('m141203_175538_add_filemanager_owners_ref_mediafile_fk', 1566887621),
('m180613_064410_create_seo_meta_table', 1566891021),
('m180613_115300_create_settings_table', 1566887621),
('m180613_140115_create_category_table', 1566887622),
('m181011_122727_add_field_alias_to_seo_meta', 1566891022),
('m190124_083937_create_category_characteristic_table', 1566887622),
('m190124_110200_add_verification_token_column_to_user_table', 1566887623),
('m190211_084949_add_prod_count_column_into_category_characteristic_table', 1566887623),
('m190306_150813_create_permission_actions_table', 1566887623),
('m190328_124008_add_column_status_setting', 1566887624),
('m190806_110305_add_parent_id_and_publish_status_columns_to_category_table', 1566887624),
('m190809_111043_create_lang_table', 1566887625),
('m190809_111636_create_category_lang_table', 1566887625),
('m190809_114759_delete_columns_in_category_table', 1566887627);

-- --------------------------------------------------------

--
-- Table structure for table `permission_actions`
--

CREATE TABLE `permission_actions` (
  `id` int(11) NOT NULL,
  `perm_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `action` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seo_meta`
--

CREATE TABLE `seo_meta` (
  `id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `h1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `seo_text` text COLLATE utf8_unicode_ci,
  `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `seo_meta`
--

INSERT INTO `seo_meta` (`id`, `page_id`, `h1`, `title`, `keywords`, `description`, `seo_text`, `language`, `parent_id`, `alias`) VALUES
(1, 1, 'категория_1_Заголовок', 'категория_1_Название', 'категория_1_Ключевые слова', 'категория_1_Описание', '<p><span class=\"label-red\">Label</span></p>', 'ru', NULL, 'category');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `body`, `status`) VALUES
(1, 'set_language', 'a:1:{i:0;a:3:{s:6:\"status\";i:1;s:4:\"lang\";s:14:\"Русский\";s:5:\"alias\";s:2:\"ru\";}}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'admin', 'lor73fnjgpivttpPO2zHGGbPZu5jWOe-', '$2y$13$TBswmfkBZx0NvXBwSSPmouLKxuh5TbIHkDCNv4ZasQGc.cmc/QlPy', NULL, 'admin@admin.com', 10, 1566887620, 1566887620, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Indexes for table `category_characteristic`
--
ALTER TABLE `category_characteristic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_lang`
--
ALTER TABLE `category_lang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-category_lang-category_id` (`category_id`),
  ADD KEY `idx-category_lang-lang_id` (`lang_id`);

--
-- Indexes for table `filemanager_mediafile`
--
ALTER TABLE `filemanager_mediafile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filemanager_owners`
--
ALTER TABLE `filemanager_owners`
  ADD PRIMARY KEY (`mediafile_id`,`owner_id`,`owner`,`owner_attribute`);

--
-- Indexes for table `lang`
--
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-lang-alias` (`alias`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `permission_actions`
--
ALTER TABLE `permission_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo_meta`
--
ALTER TABLE `seo_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `category_characteristic`
--
ALTER TABLE `category_characteristic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category_lang`
--
ALTER TABLE `category_lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `filemanager_mediafile`
--
ALTER TABLE `filemanager_mediafile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lang`
--
ALTER TABLE `lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `permission_actions`
--
ALTER TABLE `permission_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `seo_meta`
--
ALTER TABLE `seo_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `filemanager_owners`
--
ALTER TABLE `filemanager_owners`
  ADD CONSTRAINT `filemanager_owners_ref_mediafile` FOREIGN KEY (`mediafile_id`) REFERENCES `filemanager_mediafile` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
