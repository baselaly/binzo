-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2019 at 01:24 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binzo`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) UNSIGNED NOT NULL,
  `follower_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower_id`, `user_id`, `created_at`) VALUES
(1, 36, 27, '2019-01-28 12:31:11'),
(4, 36, 26, '2019-01-28 12:52:02'),
(5, 36, 28, '2019-01-28 12:52:08'),
(6, 36, 29, '2019-01-28 12:52:10'),
(7, 36, 30, '2019-01-28 12:52:12'),
(8, 36, 31, '2019-01-28 12:52:15'),
(10, 36, 34, '2019-01-28 12:52:19'),
(11, 36, 35, '2019-01-28 12:52:21'),
(13, 36, 37, '2019-01-28 12:52:25'),
(22, 36, 33, '2019-02-02 18:48:35'),
(23, 36, 36, '2019-02-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `no_of_views` bigint(20) NOT NULL DEFAULT '0',
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `no_of_views`, `user_id`, `created_at`) VALUES
(1, 'my first post', 0, 29, '2019-01-23 12:04:34'),
(2, 'my first post', 0, 36, '2019-01-23 12:05:06'),
(3, 'my first post', 0, 36, '2019-01-23 12:16:01'),
(4, 'my first post', 0, 36, '2019-01-23 12:39:23'),
(5, 'my first post', 0, 36, '2019-01-23 12:40:22'),
(6, 'my first post', 0, 35, '2019-01-23 12:41:37'),
(7, 'my first post', 0, 28, '2019-01-23 13:00:06'),
(8, 'my first post', 0, 37, '2019-01-23 13:07:41'),
(12, 'my first post', 0, 36, '2019-01-23 13:18:07'),
(13, 'my second post', 0, 36, '2019-01-23 13:42:01'),
(14, 'my second post', 0, 36, '2019-01-23 14:10:00'),
(15, 'my second post', 0, 36, '2019-01-23 14:14:33'),
(16, 'my second post', 0, 36, '2019-01-23 14:23:19'),
(19, 'my second post', 0, 36, '2019-01-23 15:09:44'),
(20, 'my second post', 0, 36, '2019-01-23 15:10:58'),
(21, 'my second post', 0, 36, '2019-01-23 15:16:47'),
(22, 'my second post', 0, 36, '2019-01-23 15:46:37'),
(23, 'my second post', 0, 22, '2019-01-23 17:12:06'),
(24, 'my second post', 0, 36, '2019-01-23 17:19:18'),
(25, 'my second post', 0, 33, '2019-01-23 17:19:44'),
(26, 'my second post', 0, 36, '2019-01-26 19:44:34'),
(27, 'my second post', 0, 26, '2019-01-26 19:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `image` varchar(100) NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`id`, `image`, `post_id`, `created_at`) VALUES
(1, '5c48526bb4bcd.jpg', 4, '2019-01-23 12:39:23'),
(2, '5c4852a61a08f.jpg', 5, '2019-01-23 12:40:22'),
(5, '5c4875f2d8ec2.jpg', 20, '2019-01-23 15:10:58'),
(6, '5c48774f37485.jpg', 21, '2019-01-23 15:16:47'),
(7, '5c487e4d2c251.jpg', 22, '2019-01-23 15:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `status`, `image`, `country`, `city`, `created_at`) VALUES
(22, 'basel2@basel.com', '', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-20 14:59:53'),
(26, 'basel5@basel.com', '', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-20 15:57:11'),
(27, 'basel6@basel.com', '', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-20 15:57:45'),
(28, 'basel7@basel.com', '', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-20 15:57:56'),
(29, 'basel10@basel.com', '$2y$10$HG4YLSSSylHo2zTGQpGMq.ZHuXeZ9gisfPdZIexqVFjHlbe9fK2e2', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-21 10:21:45'),
(30, 'basel11@basel.com', '$2y$10$kldHab.cztsnQa5B4ocTGeN5aN345UaNVAXS/9AfelQLjssgFwWt.', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-21 12:37:56'),
(31, 'basel12@basel.com', '$2y$10$Z9B3wLcXKz2B8ZwVHIWu8uoAxyjOQ8CnnNJh2TFYdZAsAKllu0wTa', 'basel', 'aly', 0, 'user.jpg', 'egypt', 'cairo', '2019-01-21 12:57:25'),
(32, 'basel13@basel.com', '$2y$10$8P0fwd2tJ2GfExHL9jUO/ugKTYtHkVtcidKcSL0tWiBLl3SfHlbo2', 'basel', 'aly', 0, 'user.png', 'egypt', 'cairo', '2019-01-21 13:07:26'),
(33, 'basel14@basel.com', '$2y$10$KYGRufurltLgDmFF3pZxIuPiHDPS76l9VWcFj4qeffULuan0O34Ti', 'basel', 'aly', 0, '5c45cdae345ba.jpg', 'egypt', 'cairo', '2019-01-21 14:48:30'),
(34, 'basel15@basel.com', '$2y$10$BZTpNGfM0k/V7YOlL9Kq8ORcGSkxwdHRCEHEYG876i1v.kPVVfiB2', 'basel', 'aly', 0, '5c45ce4a116cc.jpg', 'egypt', 'cairo', '2019-01-21 14:51:06'),
(35, 'basel16@basel.com', '$2y$10$pVKqOM.aoVBle5.Wq3sXh..fUsqg6d1kKcNL7J5ZzX5B/yQsoABEa', 'basel', 'aly', 0, '5c45ce61ebc6b.jpg', 'egypt', 'cairo', '2019-01-21 14:51:30'),
(36, 'basel120@basel.com', '$2y$10$4LpQqQJyvzVNlNn26NladuVV0lrL3TAW5BK1WCdI8iQzQ7S1NWkxu', 'baselbaselh', 'alyaly', 1, '5c56047a087b8.jpg', 'algeria', 'algeria', '2019-01-21 16:27:05'),
(37, 'basel17@basel.com', '$2y$10$EDjXL9Y3p3qoy18DgyITCuvmN6RhCi.padPza7lSlhNBG0G24RJPy', 'basel', 'aly', 0, 'user.png', 'egypt', 'cairo', '2019-01-27 11:56:26'),
(38, 'asjd@dhabvs.com', '$2y$10$TMXrcWs7cqVQtc1pbyp//O6GmsTBjL3NNyV9aujUQYuivw1Mpgnnm', 'dasdad', 'dasdasd', 0, 'user.png', 'Daskjdasd', 'dasdas', '2019-02-11 17:01:55'),
(39, 'qqq@qq.com', '$2y$10$Ofd9tGrlMkFEfCrpPlucReyd.CKBA59PUjAZ4u1l33YdIgreuymru', 'basel', 'aly', 0, '5c631e22ebcb9.jpg', 'egypt', 'cairo', '2019-02-12 20:27:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `id_3` (`id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `post_images_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
