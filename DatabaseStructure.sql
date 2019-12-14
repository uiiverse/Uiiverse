-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-11-2019 a las 09:37:37
-- Versión del servidor: 10.3.18-MariaDB-0+deb10u1-log
-- Versión de PHP: 7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `uiiverse_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_messages`
--

CREATE TABLE `admin_messages` (
  `admin_id` int(8) NOT NULL,
  `admin_type` int(1) NOT NULL,
  `admin_text` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `admin_to` int(8) NOT NULL,
  `admin_by` int(8) NOT NULL,
  `admin_post` int(8) NOT NULL,
  `is_reply` int(1) NOT NULL,
  `admin_date` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_read` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blacklist`
--

CREATE TABLE `blacklist` (
  `source` int(12) NOT NULL,
  `target` int(12) NOT NULL,
  `type` int(1) NOT NULL DEFAULT 0,
  `blacklist_id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cloudinary_keys`
--

CREATE TABLE `cloudinary_keys` (
  `key_id` int(8) NOT NULL,
  `api_key` bigint(32) NOT NULL,
  `preset` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `site_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorite_titles`
--

CREATE TABLE `favorite_titles` (
  `fav_id` int(8) NOT NULL,
  `user_id` int(8) NOT NULL,
  `title_id` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `follows`
--

CREATE TABLE `follows` (
  `follow_id` int(8) NOT NULL,
  `follow_by` int(8) NOT NULL,
  `follow_to` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifs`
--

CREATE TABLE `notifs` (
  `notif_id` int(8) NOT NULL,
  `notif_type` int(1) NOT NULL,
  `notif_by` int(8) DEFAULT NULL,
  `notif_to` int(8) NOT NULL,
  `notif_post` int(8) DEFAULT NULL,
  `merged` int(8) DEFAULT NULL,
  `notif_date` datetime NOT NULL DEFAULT current_timestamp(),
  `notif_read` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(8) NOT NULL,
  `post_by_id` int(8) NOT NULL,
  `post_title` int(8) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `feeling_id` int(1) NOT NULL DEFAULT 0,
  `text` varchar(800) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `post_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `post_type` int(8) DEFAULT NULL,
  `topic` int(8) DEFAULT NULL,
  `post_drawing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `spoiler` int(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profiles`
--

CREATE TABLE `profiles` (
  `user_id` int(8) NOT NULL,
  `bio` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `name_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `country` enum('1','2','3','4','5','6','7') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `fav_post` int(8) DEFAULT NULL,
  `organization` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `yeah_notifs` int(1) NOT NULL DEFAULT 1,
  `last_online` datetime DEFAULT NULL,
  `skill` enum('1','2','3','4','5','6','7') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `user_relationship_visibility` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(8) NOT NULL,
  `reply_post` int(8) NOT NULL,
  `reply_by_id` int(8) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `feeling_id` int(1) NOT NULL DEFAULT 0,
  `text` varchar(800) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `reply_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reports`
--

CREATE TABLE `reports` (
  `report_id` bigint(20) NOT NULL,
  `source` int(12) NOT NULL,
  `subject` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `type` int(8) NOT NULL DEFAULT 0,
  `reason` int(8) NOT NULL DEFAULT 0,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `finished` int(8) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titles`
--

CREATE TABLE `titles` (
  `title_id` int(8) NOT NULL,
  `title_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title_desc` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title_banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `perm` int(1) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `user_made` tinyint(1) NOT NULL DEFAULT 0,
  `title_by` int(8) DEFAULT NULL,
  `time_created` datetime NOT NULL DEFAULT current_timestamp(),
  `owner_only` tinyint(1) NOT NULL DEFAULT 0,
  `title_type` int(8) DEFAULT NULL,
  `community_title` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(8) NOT NULL,
  `user_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `nickname` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_face` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `date_created` datetime NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_level` int(1) NOT NULL DEFAULT 0,
  `2fa_enabled` int(1) NOT NULL DEFAULT 0,
  `2fa_secret` varchar(80) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `reset_code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yeahs`
--

CREATE TABLE `yeahs` (
  `yeah_id` int(8) NOT NULL,
  `yeah_post` int(8) NOT NULL,
  `type` enum('post','reply') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'post',
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `yeah_by` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_by` (`admin_by`),
  ADD KEY `admin_by_2` (`admin_by`),
  ADD KEY `admin_to` (`admin_to`),
  ADD KEY `admin_post` (`admin_post`);

--
-- Indices de la tabla `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`blacklist_id`),
  ADD KEY `blibfk1` (`source`),
  ADD KEY `blibfk2` (`target`);

--
-- Indices de la tabla `cloudinary_keys`
--
ALTER TABLE `cloudinary_keys`
  ADD PRIMARY KEY (`key_id`);

--
-- Indices de la tabla `favorite_titles`
--
ALTER TABLE `favorite_titles`
  ADD PRIMARY KEY (`fav_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`title_id`),
  ADD KEY `title_id` (`title_id`);

--
-- Indices de la tabla `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follow_id`),
  ADD UNIQUE KEY `follow_by` (`follow_by`,`follow_to`),
  ADD KEY `follow_to` (`follow_to`);

--
-- Indices de la tabla `notifs`
--
ALTER TABLE `notifs`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `notif_by` (`notif_by`),
  ADD KEY `notif_to` (`notif_to`),
  ADD KEY `notif_post` (`notif_post`);

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_by_id` (`post_by_id`),
  ADD KEY `posts_ibfk_2` (`post_title`);

--
-- Indices de la tabla `profiles`
--
ALTER TABLE `profiles`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indices de la tabla `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `reply_post` (`reply_post`),
  ADD KEY `reply_by_id` (`reply_by_id`);

--
-- Indices de la tabla `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `repibfk1` (`source`);

--
-- Indices de la tabla `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`title_id`),
  ADD KEY `title_by` (`title_by`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indices de la tabla `yeahs`
--
ALTER TABLE `yeahs`
  ADD PRIMARY KEY (`yeah_id`),
  ADD UNIQUE KEY `yeah_post` (`yeah_post`,`type`,`yeah_by`),
  ADD KEY `yeah_by` (`yeah_by`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_messages`
--
ALTER TABLE `admin_messages`
  MODIFY `admin_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `cloudinary_keys`
--
ALTER TABLE `cloudinary_keys`
  MODIFY `key_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favorite_titles`
--
ALTER TABLE `favorite_titles`
  MODIFY `fav_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `follows`
--
ALTER TABLE `follows`
  MODIFY `follow_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `notifs`
--
ALTER TABLE `notifs`
  MODIFY `notif_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99583656;

--
-- AUTO_INCREMENT de la tabla `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99492676;

--
-- AUTO_INCREMENT de la tabla `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `yeahs`
--
ALTER TABLE `yeahs`
  MODIFY `yeah_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
