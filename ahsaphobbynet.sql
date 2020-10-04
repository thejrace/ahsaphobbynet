-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 94.73.144.196
-- Üretim Zamanı: 04 Eki 2020, 09:48:59
-- Sunucu sürümü: 5.5.38-35.2
-- PHP Sürümü: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `ahsaphobbynet`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `activation_codes`
--

CREATE TABLE `activation_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `period` text NOT NULL,
  `activated` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` text NOT NULL,
  `token` char(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `auth_tokens`
--

INSERT INTO `auth_tokens` (`id`, `user_id`, `selector`, `token`) VALUES
(20, 34, '07buazMD6Lmf', '0730fbc76c27596fdbf51fe59d6a552b47290473c69d6662ef610cc0d0c9095b'),
(19, 33, '6MO/5ovJWjww', 'bb7ec160552695c8de70b1cfce6e57edc348874d7cc38fa66e7db8e198eb6b9b');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cart_contents`
--

CREATE TABLE `cart_contents` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `total_price` float NOT NULL,
  `item_price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `editor_text` text,
  `editor_letter_count` int(11) DEFAULT NULL,
  `img` text,
  `added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `cart_contents`
--

INSERT INTO `cart_contents` (`id`, `cart_id`, `product_id`, `variant_id`, `total_price`, `item_price`, `quantity`, `editor_text`, `editor_letter_count`, `img`, `added`) VALUES
(143, 54, 107, 177, 3.6, 0.9, 1, 'Hdhj', 4, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/IU8iqbEZUHK6Lf9FdNF4Mb4P23JvKgEt_54.gif', '2018-02-18 21:38:01'),
(142, 53, 107, 177, 6.3, 0.9, 1, 'Test 123', 7, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/8bYhP1txks2BUFcbppxv9qqIWbO1mD1u_53.gif', '2018-02-18 21:37:08'),
(140, 42, 4, 189, 10, 10, 1, NULL, NULL, NULL, '2016-07-31 17:25:17'),
(141, 53, 107, 184, 7.7, 0.7, 1, 'Halo Dayiiii', 11, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/OnsGanwhdJqZMau8OoaUbHCUkMIycGv0_53.gif', '2017-08-31 12:52:41'),
(130, 44, 105, NULL, 29.5, 29.5, 1, NULL, NULL, NULL, '2016-02-20 22:05:09'),
(133, 45, 107, 177, 9.9, 0.9, 1, 'Kekomusnuz?', 11, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/i2nQ6D1hgrArn50WGGOAdc9UzzkiSSqa_45.gif', '2016-02-28 19:18:24'),
(126, 42, 2, NULL, 23.6, 23.6, 1, NULL, NULL, NULL, '2016-02-19 15:41:40'),
(139, 42, 5, 159, 59, 59, 1, NULL, NULL, NULL, '2016-07-30 22:55:43'),
(134, 46, 107, 177, 9.9, 0.9, 1, 'Ampır Ömer', 11, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/KTtG6p40sZUicIjxgyNmb9e1PEj14bEP_46.gif', '2016-03-01 15:31:19'),
(135, 47, 107, 181, 8, 0.5, 1, 'Ben bir teroristim', 16, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/ofgdz6qhDJ1WGAjnLfFt8VrseUqvLBha_47.gif', '2016-03-04 18:13:00'),
(136, 48, 102, NULL, 94.4, 94.4, 1, NULL, NULL, NULL, '2016-03-31 01:11:00'),
(137, 48, 107, 185, 15, 1, 1, 'Ahmet Ziya Kanbur', 15, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/h3Kv1Gh1ySe2y3BpKqbi9L9cGldBa4Br_48.gif', '2016-04-26 11:32:00'),
(138, 49, 107, 178, 60, 5, 1, 'Ahmetnkjg  gke', 12, 'http://ahsaphobbynet.test/res/img/ahsaphobby/editor_previews/cart/zVae1J3Q5skCoiRxKyh4layIgDU332ND_49.gif', '2016-06-30 22:24:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` text NOT NULL,
  `parent` text,
  `order_no` int(11) NOT NULL,
  `tags` text,
  `title` text,
  `details` text,
  `icon` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `parent`, `order_no`, `tags`, `title`, `details`, `icon`, `status`) VALUES
(1, 'Ayna Pleksler', '', 31349, 'ayna, pleks, ayna pleksler, beat it', 'Ayna Pleksler', 'aborey, hobarey', 1, 1),
(2, 'Boya ve Yardımcı Ürünler', '', 225, 'boya', '', '', 1, 1),
(3, 'Boyalı Ürünler', '', 31350, 'boya, fırça, selam, selam', '', '', 1, 1),
(4, 'Ham MDF Ürünler', '', 227, '', '', NULL, 1, 1),
(5, 'Kontra Materyaller', '', 31352, '', '', NULL, 1, 1),
(6, 'Okul Setleri', '', 229, '', '', '', 1, 1),
(10, 'Askılar ve Yardımcılar', '1', 231, 'asd', '', '', 1, 1),
(11, 'Bf3', '1', 232, '', '', '', 0, 1),
(12, 'Snorky', '1', 31351, '', '', 'snoor, aboe', 0, 1),
(13, 'Soviet', '1', 237, '', '', '', 0, 1),
(19, 'Craft', '', 31351, 'craft, cutaray', '', '', 1, 1),
(20, 'CraftAltt', '19', 31347, 'alt, alt', '', '', NULL, 1),
(21, 'CraftAltAltO', '20', 31348, '', '', '', NULL, 1),
(28, 'Cut', '21', 31345, '', '', '', NULL, 1),
(30, 'San Andreas', '11', 1111, '', '', '', NULL, 1),
(31, 'Stencil', '28', 111, '', '', '', NULL, 1),
(51, 'Snorky Alt', '12', 50, '', '', 'adasdasd', NULL, 1),
(35, 'Sandıklar', '1', 232, '', '', '', NULL, 1),
(36, 'Plaketler', '1', 166, 'aborey, misty mountains cold', '', '', NULL, 1),
(38, 'Plastik Askılar', '10', 999, '', '', '', NULL, 1),
(39, 'Tepsiler', '4', 131334, '', '', '', NULL, 1),
(47, 'Kutulu', '3', 214, '', '', '', NULL, 1),
(48, 'Hebe', '2', 213123, '', '', '', 1, 1),
(49, 'Etos', '48', 244, '', '', '', NULL, 1),
(50, 'Istanbul', '49', 99, '', '', '', NULL, 1),
(53, 'Upon Oken', '1', 2222, '', '', '', NULL, 1),
(54, 'In Flames', '1', 3111, '', '', '', NULL, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `ip` int(11) NOT NULL,
  `attempted` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `failed_logins`
--

INSERT INTO `failed_logins` (`id`, `email`, `ip`, `attempted`) VALUES
(8, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:22:45'),
(7, 'ahmetkanbur@gmail.com', 1390340552, '2016-02-06 23:18:01'),
(9, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:14'),
(10, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:17'),
(11, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:18'),
(12, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:20'),
(13, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:23'),
(14, 'ahmetkanbur@gmail.com', 2147483647, '2016-02-07 15:23:25'),
(15, 'asd@asd.com', 1390340392, '2016-02-07 16:51:26'),
(16, 'asd@asd.com', 1390340392, '2016-02-07 16:51:28'),
(17, 'asd@asd.com', 1390340392, '2016-02-07 16:51:32'),
(18, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:30:41'),
(19, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:31:14'),
(20, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:31:43'),
(21, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:32:48'),
(22, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:38:02'),
(25, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:54:10'),
(26, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:54:34'),
(27, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:54:37'),
(28, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:55:06'),
(29, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 17:55:43'),
(30, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 18:05:33'),
(31, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 18:06:05'),
(46, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 21:54:20'),
(45, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 21:54:18'),
(44, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 19:09:18'),
(43, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 19:04:00'),
(42, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 19:03:40'),
(41, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 19:03:38'),
(40, 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 19:02:09'),
(118, 'test@test.com', 534740164, '2018-01-27 18:54:44'),
(117, 'amo@amo.com', 534740982, '2016-08-01 13:20:08'),
(116, 'amo@amo.com', 534740982, '2016-08-01 13:19:51'),
(115, 'amo@amo.com', 534740982, '2016-08-01 13:19:51'),
(114, 'amo@amo.com', 534740982, '2016-08-01 13:19:49'),
(113, 'kafile@kafile.com', 2147483647, '2016-02-11 13:57:51'),
(112, 'ahmetkanbur@gmail.comm', 1390340392, '2016-02-10 22:23:26'),
(111, 'ahmetkanbur@gma.com', 1390340392, '2016-02-09 23:09:26'),
(110, 'ahmet@agmet.com', 1390340392, '2016-02-09 22:20:39'),
(109, 'ahmetkan@ahmet.com', 1390340392, '2016-02-09 22:20:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `ip` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `guests`
--

INSERT INTO `guests` (`id`, `code`, `ip`, `created`) VALUES
(37, 'RAZY5hHYgkMkdx2oPHQeifwvz4OiED', 2147483647, '2016-03-04 18:12:38'),
(34, 'GS0A2iwJGvZkjDniq4zUHLPdv0uCFH', 2147483647, '2016-02-18 21:15:20'),
(35, 'guCzyLX3oud4lgNclPj5iNirNviP8d', 1390341040, '2016-02-28 19:16:49'),
(28, 'cWgfu9Q8ps8Gs1E9pJGc7JSkO9BBjx', 2147483647, '2016-02-17 01:02:40'),
(38, 'OifYYNqhd8g0Xq6CQhKi7N9qDneNyI', 2147483647, '2016-03-04 18:13:58'),
(39, 'UTthOw4kgDShE9a9TejsM6yGIelTky', 2147483647, '2016-03-31 01:10:49'),
(40, 'JGxkV1hHDKtcSGkPyOrjVFcvVzCWYe', 1390340832, '2016-06-27 21:57:06'),
(41, 'OqSk1Y3yoaGK2m7hvGtQcO7a0V257E', 1390340832, '2016-06-29 20:40:54'),
(42, 'BIJRnxt4j6bibddFVsWQvETmSktaQf', 1390340832, '2016-06-30 22:21:38'),
(44, 'jr7g0wZUo6VG1KsetoLikIPhH5xOgQ', 1390340776, '2016-08-01 00:41:58'),
(45, 'L00L6qpVwgeJGm9PwNh8unEA6PFaHY', 534740982, '2016-08-01 12:00:45'),
(46, 'AmaPbcANPGutw5U0MhtrL4w0iZNBfF', 534740982, '2016-08-01 13:33:44'),
(47, '5GhH7gEV1GGLD2d7rGeel0KB81o9rW', 534733583, '2016-08-03 17:18:16'),
(48, 'Q4zCqk7NYEDTb1M3Ev5H58N6G7wc7n', 2147483647, '2016-08-09 00:14:13'),
(49, 'iy2hCRTaOvw3zXWC6h9XW35fdQufxr', 2147483647, '2017-01-17 00:24:10'),
(51, '2frSRHoSqIaGrdjukYazAhinDQiiHr', 2147483647, '2018-02-18 21:36:35'),
(52, 'yC4ah333gMNYs7kP7B4lwxPOCPwzpJ', 1319457695, '2018-02-18 21:36:54'),
(53, 'bb5zrmOntpB9hzmzNrfoGAKkgOVjzY', 2147483647, '2018-02-18 21:37:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `login_throttle_records`
--

CREATE TABLE `login_throttle_records` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL,
  `user_email` text,
  `user_ip` int(11) DEFAULT NULL,
  `activated` datetime NOT NULL,
  `until` text NOT NULL,
  `delay` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `login_throttle_records`
--

INSERT INTO `login_throttle_records` (`id`, `type`, `user_email`, `user_ip`, `activated`, `until`, `delay`, `active`) VALUES
(81, 'ip', 'amo@amo.com', 534740982, '2016-08-01 13:20:08', '1470046833', 25, 0),
(80, 'ip', 'amo@amo.com', 534740982, '2016-08-01 13:19:51', '1470046806', 15, 0),
(79, 'ip', 'ada@ada.com', 1390340392, '2016-02-09 22:19:04', '1455049159', 15, 0),
(78, 'ip', 'ahmetkqnb@mai.com', 1390340392, '2016-02-09 22:15:10', '1455048925', 15, 0),
(77, 'ip', 'asd@asd.com', 1390340392, '2016-02-09 21:43:58', '1455047063', 25, 0),
(76, 'ip', 'asd@asd.com', 1390340392, '2016-02-09 21:43:27', '1455047032', 25, 0),
(75, 'ip', 'asd@asd.com', 1390340392, '2016-02-09 21:42:32', '1455046977', 25, 0),
(74, 'ip', 'asd@ad.com', 1390340392, '2016-02-09 21:42:02', '1455046947', 25, 0),
(73, 'ip', 'asd@asd.com', 1390340392, '2016-02-09 21:40:55', '1455046870', 15, 0),
(72, 'ip', 'ad@ad.com', 1390340392, '2016-02-09 21:34:49', '1455046504', 15, 0),
(71, 'all', '', 0, '2016-02-07 22:39:48', '1454877598', 10, 0),
(70, 'all', '', 0, '2016-02-07 22:38:15', '1454877505', 10, 0),
(69, 'all', '', 0, '2016-02-07 22:35:02', '1454877312', 10, 0),
(68, 'all', '', 0, '2016-02-07 22:28:39', '1454876929', 10, 0),
(67, 'all', '', 0, '2016-02-07 22:28:08', '1454876893', 5, 0),
(66, 'all', '', 0, '2016-02-07 22:27:50', '1454876875', 5, 0),
(65, 'ip', 'aka@aka.com', 1390340392, '2016-02-07 22:26:40', '1454876825', 25, 0),
(64, 'all', '', 0, '2016-02-07 22:26:08', '1454876773', 5, 0),
(63, 'all', '', 0, '2016-02-07 22:25:44', '1454876749', 5, 0),
(62, 'all', '', 0, '2016-02-07 22:25:16', '1454876721', 5, 0),
(61, 'all', '', 0, '2016-02-07 22:23:01', '1454876586', 5, 0),
(60, 'all', '', 0, '2016-02-07 22:22:35', '1454876560', 5, 0),
(59, 'ip', 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 22:21:42', '1454876527', 25, 0),
(58, 'ip', 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 22:21:10', '1454876485', 15, 0),
(57, 'ip', 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 22:11:46', '1454875931', 25, 0),
(56, 'all', '', 0, '2016-02-07 22:11:46', '1454875911', 5, 0),
(54, 'ip', 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 22:09:31', '1454875796', 25, 0),
(55, 'ip', 'ahmetkanbur@gmail.com', 1390340392, '2016-02-07 22:10:22', '1454875837', 15, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `meta_tags`
--

CREATE TABLE `meta_tags` (
  `id` int(11) NOT NULL,
  `meta_type` text NOT NULL,
  `title` text,
  `keywords` text,
  `type` text,
  `url` text,
  `site_name` text,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `meta_tags`
--

INSERT INTO `meta_tags` (`id`, `meta_type`, `title`, `keywords`, `type`, `url`, `site_name`, `description`) VALUES
(1, 'og', 'AhsapHobby Hobi & Sanatsal  {item_title}', 'ahsaphobby.com, ahsaphobby.net, ahsaphobby, ahsap, hobby, hobi, sanatsal, {item_keywords}', 'website', 'http://ahsaphobby.net/', 'AhsapHobby.net', 'Ahsap, pleks hobi urunleri ve malzemeler. {item_description}'),
(2, 'meta', 'AhsapHobby Hobi & Sanatsal {item_title}', 'ahsaphobby.com, ahsaphobby.net, ahsaphobby, ahsap, hobby, hobi, sanatsal, {item_keywords}', 'website', 'http://ahsaphobby.net/', 'AhsapHobby.net', 'Ahsap, pleks hobi urunleri ve malzemeler. {item_description}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` int(5) NOT NULL,
  `product_name` text NOT NULL,
  `price_1` float NOT NULL,
  `price_2` float DEFAULT '0',
  `price_3` float DEFAULT '0',
  `pure_price_1` float NOT NULL,
  `pure_price_2` float DEFAULT '0',
  `pure_price_3` float DEFAULT '0',
  `kdv_included` int(1) DEFAULT '0',
  `kdv_percentage` int(11) DEFAULT '18',
  `sale_percentage` float DEFAULT '0',
  `stock_code` text,
  `stock_amount` int(11) DEFAULT NULL,
  `desi` float DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `showcase_home` int(1) DEFAULT '0',
  `showcase_category` int(1) DEFAULT '0',
  `new` int(1) DEFAULT '0',
  `campaign` int(1) DEFAULT '0',
  `has_form` int(1) DEFAULT '0',
  `variant` int(1) DEFAULT '0',
  `shipment_cost` float DEFAULT NULL,
  `shipment_system_cost` int(11) DEFAULT '1',
  `material` text,
  `details` text,
  `picture_1` int(11) DEFAULT '0',
  `picture_2` int(11) DEFAULT '0',
  `picture_3` int(11) DEFAULT '0',
  `picture_4` int(11) DEFAULT '0',
  `similar_products` text,
  `seo_title` text,
  `seo_keywords` text,
  `seo_details` text
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `category`, `product_name`, `price_1`, `price_2`, `price_3`, `pure_price_1`, `pure_price_2`, `pure_price_3`, `kdv_included`, `kdv_percentage`, `sale_percentage`, `stock_code`, `stock_amount`, `desi`, `status`, `showcase_home`, `showcase_category`, `new`, `campaign`, `has_form`, `variant`, `shipment_cost`, `shipment_system_cost`, `material`, `details`, `picture_1`, `picture_2`, `picture_3`, `picture_4`, `similar_products`, `seo_title`, `seo_keywords`, `seo_details`) VALUES
(2, 6, 'Telefon Anahtarlık', 23.6, 0, 0, 20, 0, 0, 0, 18, 40, 'DFGJMNQQ13', 887, 3466, 1, 1, 1, 1, 1, 0, 0, 20, 0, 'MDF', 'ayfon bilgeyts', 1, 1, 0, 0, '11', 'Telefon Anahtarlık', 'telefon anahtarlik', 'telefon anahtarlik'),
(3, 3, 'Kuşlu Anahtarlık', 1180, 0, 0, 1000, 0, 0, 0, 18, 0, 'DFGG333NQ813', 10000, 0, 1, 1, 1, 1, 1, 0, 1, 10, 0, '0', 'Boy:45 cm En: 34 cm Yükseklik: 10 cm Kalınlık: 8 mm', 1, 0, 0, 0, '2', 'Ahşap CNC Tepsi', 'ahsap, tepsi, cnc, olaylar, olaylar, ahsaphobby', 'Bu tepsi acayip anasının gözü birşey.'),
(4, 12, 'Oval Pano', 36, 0, 0, 36, 0, 0, 1, 18, 0, 'KFGJMNQ813', 8877, 0, 1, 1, 0, 1, 1, 0, 1, 20, 0, 'MDF', '', 1, 1, 0, 0, '11', 'Ahşap CNC Tepsi', '', ''),
(5, 5, 'Ev Anahtarlık', 64.9, 0, 0, 55, 0, 0, 0, 18, 0, 'DFGJ3NQ813', 8877, 0, 1, 1, 1, 1, 0, 0, 1, 10, 1, '0', 'Boy:45 cm En: 34 cm Yükseklik: 10 cm Kalınlık: 8 mm', 1, 0, 0, 0, '11', 'Ahşap CNC Tepsi', 'ahsap, tepsi, cnc, olaylar, olaylar, ahsaphobby', 'Bu tepsi acayip anasının gözü birşey.'),
(6, 3, 'İsimli Pano', 45.3, 0, 0, 45.3, 0, 0, 1, 18, 0, 'DFGJMNQQ13', 7540, 0, 1, 1, 1, 1, 1, 0, 0, 20, 0, '', 'Boy:45 cm En: 34 cm Yükseklik: 10 cm Kalınlık: 8 mm', 1, 0, 0, 0, '11', 'Ahşap CNC Tepsi', 'ahsap, tepsi, cnc, olaylar, olaylar, ahsaphobby', 'Bu tepsi acayip anasının gözü birşey.'),
(42, 5, 'Tepsi', 118, 0, 0, 100, 0, 0, 0, 18, 0, 'AYNA13-42', 0, 0, 1, 1, 1, 1, 1, 0, 0, 20, 0, '0', '', 1, 0, 0, 0, '0', '', '', ''),
(102, 1, 'Lift me Up', 94.4, 0, 0, 80, 0, 0, 0, 18, 0, 'BLUE72-43', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'Others', '', 1, 0, 0, 0, '', '', '', ''),
(103, 1, 'ZKono', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'AYNA58-103', 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 'MDF', '', 0, 0, 0, 0, '', '', '', ''),
(104, 1, 'Hebere', 23.6, 0, 0, 20, 0, 0, 0, 18, 0, 'AYNA74-104', 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 'Pleks', '', 0, 0, 0, 0, '', '', '', ''),
(105, 1, 'Sandık', 29.5, 0, 0, 25, 0, 0, 0, 18, 0, 'AYNA16-105', 500, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'Kontra', '', 1, 1, 0, 1, '', '', '', ''),
(107, 5, 'İsme Özel Pano', 5, 0, 0, 5, 0, 0, 1, 18, 0, 'KONT24-106', 0, 0, 1, 0, 0, 1, 0, 1, 1, 0, 0, 'Kontra', 'Yukarıdaki fiyat harf adet fiyatıdır.', 1, 1, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products_deleted`
--

CREATE TABLE `products_deleted` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category` int(5) NOT NULL,
  `product_name` text NOT NULL,
  `price_1` float NOT NULL,
  `price_2` float DEFAULT '0',
  `price_3` float DEFAULT '0',
  `pure_price_1` float NOT NULL,
  `pure_price_2` float DEFAULT '0',
  `pure_price_3` float DEFAULT '0',
  `kdv_included` int(1) DEFAULT '0',
  `kdv_percentage` int(11) DEFAULT '18',
  `sale_percentage` float DEFAULT '0',
  `stock_code` text,
  `stock_amount` int(11) DEFAULT NULL,
  `desi` float DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `showcase_home` int(1) DEFAULT '0',
  `showcase_category` int(1) DEFAULT '0',
  `new` int(1) DEFAULT '0',
  `campaign` int(1) DEFAULT '0',
  `has_form` int(1) DEFAULT '0',
  `variant` int(1) DEFAULT '0',
  `shipment_cost` float DEFAULT NULL,
  `shipment_system_cost` int(11) DEFAULT '1',
  `details` text,
  `picture_1` text,
  `picture_2` text,
  `picture_3` text,
  `picture_4` text,
  `similar_products` text,
  `seo_title` text,
  `seo_keywords` text,
  `seo_details` text
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `products_deleted`
--

INSERT INTO `products_deleted` (`id`, `product_id`, `category`, `product_name`, `price_1`, `price_2`, `price_3`, `pure_price_1`, `pure_price_2`, `pure_price_3`, `kdv_included`, `kdv_percentage`, `sale_percentage`, `stock_code`, `stock_amount`, `desi`, `status`, `showcase_home`, `showcase_category`, `new`, `campaign`, `has_form`, `variant`, `shipment_cost`, `shipment_system_cost`, `details`, `picture_1`, `picture_2`, `picture_3`, `picture_4`, `similar_products`, `seo_title`, `seo_keywords`, `seo_details`) VALUES
(40, 91, 3, 'CC', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'BOYA36-71', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '1', '0', '0', '0', '', '', '', ''),
(37, 85, 41, 'Osman Abi', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'INTE42-75', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '0', '0', '0', '0', '', '', '', ''),
(29, 75, 1, 'Hebea', 23.6, 0, 0, 20, 0, 0, 0, 18, 0, 'AYNA50-75', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '0', '0', '0', '0', '', '', '', ''),
(59, 110, 67, 'Keko Mehmet Abi', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'USEN58-109', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '0', '0', '0', '0', '', '', '', ''),
(57, 106, 0, '', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 108, 3, 'FiveFingerDeathPunch', 236, 0, 0, 200, 0, 0, 0, 18, 0, 'BOYA9-108', 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '0', '0', '0', '0', '', '', '', ''),
(56, 101, 1, 'Testo Alt urun Test', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'TEST57-95', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, '', '0', '0', '0', '0', '', '', '', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `showcase_campaign`
--

CREATE TABLE `showcase_campaign` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `showcase_campaign`
--

INSERT INTO `showcase_campaign` (`id`, `product_id`, `order_no`) VALUES
(4, 3, 2),
(3, 2, 1),
(5, 4, 3),
(6, 6, 5),
(7, 42, 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `showcase_category`
--

CREATE TABLE `showcase_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `showcase_category`
--

INSERT INTO `showcase_category` (`id`, `category_id`, `product_id`, `order_no`) VALUES
(28, 1, 103, 2),
(34, 5, 5, 2),
(27, 1, 104, 1),
(23, 3, 6, 1),
(33, 6, 2, 1),
(24, 5, 42, 1),
(31, 3, 3, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `showcase_home`
--

CREATE TABLE `showcase_home` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `showcase_home`
--

INSERT INTO `showcase_home` (`id`, `product_id`, `order_no`) VALUES
(33, 6, 4),
(32, 4, 7),
(29, 5, 3),
(31, 103, 5),
(30, 42, 1),
(25, 3, 6),
(35, 2, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `showcase_new`
--

CREATE TABLE `showcase_new` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `showcase_new`
--

INSERT INTO `showcase_new` (`id`, `product_id`, `order_no`) VALUES
(1, 103, 1),
(2, 102, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sqlcache`
--

CREATE TABLE `sqlcache` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cache_ismi` varchar(64) NOT NULL DEFAULT '',
  `cache_icerik` longtext NOT NULL,
  `cache_zamani` varchar(30) NOT NULL DEFAULT '',
  `cache_suresi` int(10) NOT NULL DEFAULT '60'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `sqlcache`
--

INSERT INTO `sqlcache` (`id`, `cache_ismi`, `cache_icerik`, `cache_zamani`, `cache_suresi`) VALUES
(1, 'siparis', 'czo2NjUyOiI8dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMTVjbSB4IDE1Y20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9yZXNpbS1zaXBhcmlzLzM0L2Foc19pbWFnZV8wLnBuZz9yYW5kb209ODAxJWVuam95IHRoZSBzaWxlbmNlJUtvbnRyYSUxNQ0KCQkJCQk8L2xpPkFCT09PTzIyMjIyMjIyMjI8L3VsPjx1bD4NCgkJCQkJPGxpPmh0dHA6Ly9sb2NhbGhvc3QvYWhzYXBob2JieS9pbWcvdXBsb2FkL3Jlc2ltLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzAucG5nP3JhbmRvbT04MDElZW5qb3kgdGhlIHNpbGVuY2UlS29udHJhJTE1DQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy91cGxvYWQvcmVzaW0tc2lwYXJpcy8zNC9haHNfaW1hZ2VfMC5wbmc/cmFuZG9tPTM3JWhhbG8lS29udHJhJTQNCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9oYXJmLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzAucG5nP3JhbmRvbT02ODclQWhtZXQgYWJpJU1ERiU4DQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy91cGxvYWQvaGFyZi1zaXBhcmlzLzM0L2Foc19pbWFnZV8wLnBuZz9yYW5kb209MTM3JTEyMzEyMzIxMyVNREYlOQ0KCQkJCQk8L2xpPkFCT09PTzIyMjIyMjIyMjI8L3VsPjx1bD4NCgkJCQkJPGxpPmh0dHA6Ly9sb2NhbGhvc3QvYWhzYXBob2JieS9pbWcvdXBsb2FkL2hhcmYtc2lwYXJpcy8zNC9haHNfaW1hZ2VfNy5wbmc/cmFuZG9tPTMxNiUxMjMyMiVNREYlNQ0KCQkJCQk8L2xpPkFCT09PTzIyMjIyMjIyMjI8L3VsPjx1bD4NCgkJCQkJPGxpPmh0dHA6Ly9sb2NhbGhvc3QvYWhzYXBob2JieS9pbWcvdXBsb2FkL2hhcmYtc2lwYXJpcy8zNC9haHNfaW1hZ2VfNi5wbmc/cmFuZG9tPTMxMSVmcmVlbG92ZSVNREYlOA0KCQkJCQk8L2xpPkFCT09PTzIyMjIyMjIyMjI8L3VsPjx1bD4NCgkJCQkJPGxpPmh0dHA6Ly9sb2NhbGhvc3QvYWhzYXBob2JieS9pbWcvdXBsb2FkL2hhcmYtc2lwYXJpcy8zNC9haHNfaW1hZ2VfNy5wbmc/cmFuZG9tPTIxJWhpZGRlbiBjYXRjaCVNREYlMTENCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMTVjbSB4IDE1Y20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMzBjbSB4IDMwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMjVjbSB4IDI1Y20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMjBjbSB4IDIwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMTVjbSB4IDE1Y20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMTBjbSB4IDEwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMjBjbSB4IDIwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL296ZWxpc2xlci5wbmclTURGJUthcmUlMTBjbSB4IDEwY20NCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL29uaXpsZW1lLWltZy5wbmclQWglTURGJTINCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL29uaXpsZW1lLWltZy5wbmclb3Jpb24lTURGJTUNCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9oYXJmLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzEwLnBuZz9yYW5kb209Nzc3JUplc3RlciByYWNlMiVLb250cmElMTENCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9oYXJmLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzExLnBuZz9yYW5kb209NjkyJXdobyBpcyBpdD8lTURGJTgNCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9oYXJmLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzEyLnBuZz9yYW5kb209NDElbWF5a2lsIGpla3NpbiVNREYlMTINCgkJCQkJPC9saT5BQk9PT08yMjIyMjIyMjIyPC91bD48dWw+DQoJCQkJCTxsaT5odHRwOi8vbG9jYWxob3N0L2Foc2FwaG9iYnkvaW1nL3VwbG9hZC9oYXJmLXNpcGFyaXMvMzQvYWhzX2ltYWdlXzEzLnBuZz9yYW5kb209Mzc3JWZyaWVuZCBvZiBtaW1lJU1ERiUxMg0KCQkJCQk8L2xpPkFCT09PTzIyMjIyMjIyMjI8L3VsPjx1bD4NCgkJCQkJPGxpPmh0dHA6Ly9sb2NhbGhvc3QvYWhzYXBob2JieS9pbWcvdXBsb2FkL2hhcmYtc2lwYXJpcy8zNC9haHNfaW1hZ2VfMTQucG5nP3JhbmRvbT02MjQlQ2FkZW5jZSBvZiBoZXIgbGFzdCBicmVhdGglTURGJTIyDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy91cGxvYWQvcmVzaW0tc2lwYXJpcy8zNC9yZXNpbS1zaXBhcmlzMTU0N2Y3MjZiMjQ3NTNfMzQuanBnJUtvbnRyYSVEaWtkw7ZydGdlbiUyNC43Y20geCAxOGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy91cGxvYWQvcmVzaW0tc2lwYXJpcy8zNC9yZXNpbS1zaXBhcmlzMTU0N2Y3NTFmMDg5ZGNfMzQucG5nJU1ERiVLYXJlJTMwY20geCAzMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy91cGxvYWQvcmVzaW0tc2lwYXJpcy8zNC9yZXNpbS1zaXBhcmlzMTU0ODIwOTU4MzJkMDdfMzQuanBnJUtvbnRyYSVEaWtkw7ZydGdlbiUyNC43Y20geCAxOGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTMwY20geCAzMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTE1Y20geCAxNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTE1Y20geCAxNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTIwY20geCAyMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTEwY20geCAxMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTE1Y20geCAxNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTEwY20geCAxMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTMwY20geCAzMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTE1Y20geCAxNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTEwY20geCAxMGNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+PHVsPg0KCQkJCQk8bGk+aHR0cDovL2xvY2FsaG9zdC9haHNhcGhvYmJ5L2ltZy9vemVsaXNsZXIucG5nJU1ERiVLYXJlJTI1Y20geCAyNWNtDQoJCQkJCTwvbGk+QUJPT09PMjIyMjIyMjIyMjwvdWw+Ijs=', '1417952483', 2),
(4, 'Aragorn', '11', 'zaman', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `pass` text NOT NULL,
  `salt` text NOT NULL,
  `register` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `ip` text NOT NULL,
  `perm_level` int(11) NOT NULL,
  `email` text NOT NULL,
  `phone` text,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `pass`, `salt`, `register`, `last_login`, `ip`, `perm_level`, `email`, `phone`, `status`) VALUES
(12, 'Ahmet', '7a3e98fe326db5078ee1fab64151cbb648100fd1adcffeb1767b40832e3160f5', 'Á\"~>!jDM.UE/®iE¹ünX-ùåAàWþÞY\"½Ð.ýIÞkÂ;ùÝNSelcEé', '2016-02-06 14:00:06', '2016-08-01 00:33:52', '', 1, 'ahmetkanbur@gmail.com', '', 0),
(13, 'Amo', 'b689bbd2058e0f6b2554ec0c80df1ac4376182f23d89ab2905947b6a72215ede', 'Ë0ldLq2ó¸flyªöe·õX_@vã\0£ÐE£fQ\nÊ{²·;Õr ((ã/\"M´&xÏî<GÁ5', '2016-02-10 22:17:24', '2016-02-10 22:27:32', '', 1, 'meto@meto.com', '', 0),
(14, 'McCJ', '4eddedff86ede0121676e31587b189d2ef9eff75f6b0a164ce70a55914427437', 'kL¨]ìBCB±|n8~nãF¨ÎR·£=B¡(,Oº@É|9+ó/ù/D=?Z`Xó0<oµ', '2016-02-10 22:27:11', NULL, '', 1, 'comandtakearide@gm.com', '', 0),
(15, 'Kafile', '625017d682187f2fa3516b50b82b18ca4765adc71422d1a4ca63a36b6e1dafe6', '(»Fù!g;°UT\"õç32ôÕÃ÷?RA)Wÿæ÷Ö	c[ù+[	{çï_ÑHEðÉ6Å&Úf¼ö', '2016-02-11 13:57:28', '2016-02-11 13:58:01', '', 1, 'kafile@gmail.com', '', 0),
(30, 'Ivan', 'b3e4854d8f4f8ddc63f6e59a7b25bae301a7eb3cb1fd666605babedd6ae19e17', 'Ä(1=\ZÚ-yëgèrÿÓÝÙ(/ïBU±Ö¦ÃòºËñZ4iÌ~p\Z1±ÅÅû \0$Iy', '2016-02-12 22:05:22', NULL, '', 1, 'ivano@ivano.com', '', 0),
(31, 'Alvestam', '9e93d56fbbc92ec04266ccca3d9fb3eb46b6c33f6d4016cf5f94001a3f8aad3d', 'Û66¢ËËA«·THtÀ7Mô{ÊÛfL(/`)sa@eÃb\rDµJÈ¤§vIÆL\nüÈå', '2016-02-13 00:03:31', '2016-02-13 00:05:34', '', 1, 'alvestam@alvestam.com', '', 0),
(32, 'Disturbed', '14078494ff3ffcc941d35db1737f9da36e164b7d2bd75cdf25a19ddfeaadbbea', 'ë¨Ò¶ÚÌM`\0!q\"é§\nX5«.¯ìu¢ó_cÄ¨ËAØ_°Vl{¬ùþ¸fî\'ßç{4uC£åtðÖ¨', '2016-02-16 23:27:21', '2016-02-16 23:28:21', '', 1, 'disturbet@disturbet.com', '', 0),
(33, 'Ömer Serkan', '629a38e180e43117709cf19b230bd2bbb5b64d2cf8cc7e1f8615aedfe4fd1f6c', 'Ñ|¸áÊÕ³éÀ$ÌÅÄY÷ÒëÐ³/wÃ¬q&\0N¸Ü²ç:ykç?áÝ9îZí6¨aºXi0²', '2016-03-01 15:33:05', '2016-03-01 15:33:58', '', 1, 'omerakturk@outlook.com.tr', '', 0),
(34, 'Ahmet Kanbur', '2af0e02baa7ce41a3c6921b2ec425b1c60e6ab7ef8e4021075f1a7081b71db79', 'U\0BHú¨íÇS2X-µdçóÝUÇ×OvçqåR)+A²J÷`æd\0sÊã*&Î&ÒYèµúð', '2018-01-27 18:55:05', '2018-01-27 18:55:14', '', 1, 'darkage@darkage.com', '', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_carts`
--

CREATE TABLE `user_carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_id` text,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `user_carts`
--

INSERT INTO `user_carts` (`id`, `user_id`, `guest_id`, `created`) VALUES
(49, 0, 'BIJRnxt4j6bibddFVsWQvETmSktaQf', '2016-06-30 22:24:10'),
(48, 0, 'UTthOw4kgDShE9a9TejsM6yGIelTky', '2016-03-31 01:11:00'),
(42, 12, '', '2016-02-18 21:10:43'),
(45, 0, 'guCzyLX3oud4lgNclPj5iNirNviP8d', '2016-02-28 19:16:52'),
(46, 33, '', '2016-03-01 15:29:34'),
(47, 0, 'RAZY5hHYgkMkdx2oPHQeifwvz4OiED', '2016-03-04 18:13:00'),
(44, 0, 'GS0A2iwJGvZkjDniq4zUHLPdv0uCFH', '2016-02-18 21:15:29'),
(51, 0, 'L00L6qpVwgeJGm9PwNh8unEA6PFaHY', '2016-08-01 12:02:59'),
(52, 0, 'iy2hCRTaOvw3zXWC6h9XW35fdQufxr', '2017-01-17 00:24:37'),
(53, 34, '', '2017-08-31 12:52:41'),
(54, 0, 'yC4ah333gMNYs7kP7B4lwxPOCPwzpJ', '2018-02-18 21:38:01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `variant_defs`
--

CREATE TABLE `variant_defs` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `variant_defs`
--

INSERT INTO `variant_defs` (`id`, `variant_id`, `product_id`, `order_no`) VALUES
(131, 3, 107, 3),
(116, 2, 4, 0),
(33, 1, 4, 0),
(114, 1, 5, 0),
(129, 1, 107, 2),
(133, 43, 107, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `variant_main`
--

CREATE TABLE `variant_main` (
  `id` int(11) NOT NULL,
  `variant_name` text NOT NULL,
  `order_no` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `variant_main`
--

INSERT INTO `variant_main` (`id`, `variant_name`, `order_no`) VALUES
(1, 'Boyut', 0),
(2, 'Kalınlık', 0),
(3, 'Malzeme', 0),
(4, 'Renk', 0),
(5, 'Tip', 0),
(43, 'Font', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `variant_products`
--

CREATE TABLE `variant_products` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `variant_code` text NOT NULL,
  `price_1` float NOT NULL,
  `price_2` float DEFAULT '0',
  `price_3` float DEFAULT '0',
  `pure_price_1` float DEFAULT '0',
  `pure_price_2` float DEFAULT '0',
  `pure_price_3` float DEFAULT '0',
  `kdv_included` int(1) DEFAULT '0',
  `kdv_percentage` int(11) DEFAULT '18',
  `sale_percentage` float DEFAULT '0',
  `stock_code` text,
  `stock_amount` int(11) DEFAULT '9999',
  `desi` float DEFAULT '0',
  `status` int(1) DEFAULT '1',
  `shipment_cost` float DEFAULT '0',
  `shipment_system_cost` int(1) DEFAULT '1',
  `details` text,
  `picture_1` int(11) DEFAULT NULL,
  `order_no` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `variant_products`
--

INSERT INTO `variant_products` (`id`, `parent`, `product_name`, `variant_code`, `price_1`, `price_2`, `price_3`, `pure_price_1`, `pure_price_2`, `pure_price_3`, `kdv_included`, `kdv_percentage`, `sale_percentage`, `stock_code`, `stock_amount`, `desi`, `status`, `shipment_cost`, `shipment_system_cost`, `details`, `picture_1`, `order_no`) VALUES
(162, 2, '2 cm - 9mm - MDF', '1-4-5', 20, 0, 0, 20, 0, 0, 1, 18, 0, 'TELE62-162', 0, 0, 1, 10, 1, '', 1, 0),
(163, 2, '2 cm - 5mm - Kontra', '1-3-6', 50, 0, 0, 50, 0, 0, 1, 18, 0, 'TELE83-163', 0, 0, 1, 0, 1, '', 0, 0),
(164, 2, '3 cm - 5mm - Çam', '2-3-12', 20, 0, 0, 20, 0, 0, 1, 18, 0, 'TELE60-164', 0, 0, 1, 0, 1, '', 0, 0),
(165, 2, '2 cm - 5mm - Çam', '1-3-12', 40, 0, 0, 40, 0, 0, 1, 18, 0, 'TELE55-165', 0, 0, 1, 0, 1, '', 0, 0),
(161, 2, '2 cm - 5mm - MDF', '1-3-5', 50, 0, 0, 50, 0, 0, 1, 18, 0, 'TELE59-160', 0, 0, 1, 10, 1, '', 0, 0),
(167, 2, '4 cm - 9mm - Kontra', '10-4-6', 16.52, 0, 0, 14, 0, 0, 0, 18, 0, 'TELE57-166', 0, 0, 1, 0, 1, '', 0, 0),
(159, 5, '2 cm', '1', 59, 0, 0, 50, 0, 0, 0, 18, 0, 'EV A66-159', 0, 0, 1, 10, 1, '', 0, 0),
(180, 2, '90 cm - 5mm - MDF', '16-3-5', 2, 0, 0, 2, 0, 0, 1, 18, 0, 'TELE61-180', 0, 0, 1, 0, 1, '', 0, 0),
(179, 107, 'Times New Roman - 2 cm - MDF', '25-1-5', 0.7, 0, 0, 0.7, 0, 0, 1, 18, 0, 'ISM70-179', 0, 0, 1, 10, 1, '', 1, 0),
(177, 107, 'Airmole Strip - 2 cm - MDF', '23-1-5', 0.9, 0, 0, 0.9, 0, 0, 1, 18, 0, 'ISM74-168', 0, 0, 1, 10, 1, '', 1, 0),
(178, 107, 'Script Sans - 2 cm - MDF', '24-1-5', 5, 0, 0, 5, 0, 0, 1, 18, 0, 'ISM50-178', 0, 0, 1, 10, 1, '', 1, 0),
(181, 107, 'Harabara - 2 cm - MDF', '27-1-5', 0.5, 0, 0, 0.5, 0, 0, 1, 18, 0, 'ISM83-181', 0, 0, 1, 0, 1, '', 0, 0),
(182, 107, 'Corsiva - 2 cm - MDF', '28-1-5', 0.6, 0, 0, 0.6, 0, 0, 1, 18, 0, 'ISM65-182', 0, 0, 1, 0, 1, '', 0, 0),
(183, 107, 'Allegro - 2 cm - MDF', '29-1-5', 0.75, 0, 0, 0.75, 0, 0, 1, 18, 0, 'ISM89-183', 0, 0, 1, 0, 1, '', 0, 0),
(184, 107, 'Firenze - 2 cm - MDF', '30-1-5', 0.7, 0, 0, 0.7, 0, 0, 1, 18, 0, 'ISM8-184', 0, 0, 1, 0, 1, '', 0, 0),
(185, 107, 'Jupiter - 2 cm - MDF', '31-1-5', 1, 0, 0, 1, 0, 0, 1, 18, 0, 'ISM66-185', 0, 0, 1, 0, 1, '', 0, 0),
(186, 107, 'Airmole Strip - 2 cm - Kontra', '23-1-6', 0.85, 0, 0, 0.85, 0, 0, 1, 18, 0, 'ISME79-186', 0, 0, 1, 0, 1, '', 0, 0),
(187, 107, 'Airmole Strip - 2 cm - Çam', '23-1-12', 1.003, 0, 0, 0.85, 0, 0, 0, 18, 0, 'ISME30-187', 0, 0, 1, 0, 1, '', 0, 0),
(188, 4, '5mm - 2 cm', '3-1', 10, 0, 0, 10, 0, 0, 1, 18, 0, 'OVAL42-188', 0, 0, 1, 0, 1, '', 0, 0),
(189, 4, '5mm - 3 cm', '3-2', 10, 0, 0, 10, 0, 0, 1, 18, 0, 'OVAL41-189', 0, 0, 1, 0, 1, '', 0, 0),
(190, 4, '5mm - 6 cm', '3-13', 10, 0, 0, 10, 0, 0, 1, 18, 0, 'OVAL5-190', 0, 0, 1, 0, 1, '', 0, 0),
(191, 4, '9mm - 3 cm', '4-2', 10, 0, 0, 10, 0, 0, 1, 18, 0, 'OVAL59-191', 0, 0, 1, 0, 1, '', 0, 0),
(192, 4, '9mm - 5 cm', '4-11', 10, 0, 0, 10, 0, 0, 1, 18, 0, 'OVAL6-192', 0, 0, 1, 0, 1, '', 0, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `variant_sub`
--

CREATE TABLE `variant_sub` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `variant_name` text NOT NULL,
  `order_no` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `variant_sub`
--

INSERT INTO `variant_sub` (`id`, `parent`, `variant_name`, `order_no`) VALUES
(1, 1, '2 cm', 0),
(2, 1, '3 cm', 0),
(3, 2, '5mm', 0),
(4, 2, '9mm', 0),
(5, 3, 'MDF', 0),
(6, 3, 'Kontra', 0),
(7, 4, 'Kırmızı', 0),
(8, 4, 'Sarı', 0),
(16, 1, '90 cm', 0),
(10, 1, '4 cm', 0),
(11, 1, '5 cm', 0),
(12, 3, 'Çam', 0),
(13, 1, '6 cm', 0),
(14, 1, '7 cm', 0),
(15, 1, '1 cm', 0),
(23, 43, 'Airmole Strip', 0),
(18, 11, 'Popup', 0),
(19, 8, 'Grave', 0),
(20, 9, 'Obaa', 0),
(21, 5, 'Kayık', 0),
(22, 5, 'Kürek', 0),
(24, 43, 'Script Sans', 0),
(25, 43, 'Times New Roman', 0),
(27, 43, 'Harabara', 0),
(28, 43, 'Corsiva', 0),
(29, 43, 'Allegro', 0),
(30, 43, 'Firenze', 0),
(31, 43, 'Jupiter', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `webtrator`
--

CREATE TABLE `webtrator` (
  `id` int(11) NOT NULL,
  `isim` text NOT NULL,
  `eposta` text NOT NULL,
  `pass` text NOT NULL,
  `cookie` text NOT NULL,
  `son_giris` datetime NOT NULL,
  `durum` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin5;

--
-- Tablo döküm verisi `webtrator`
--

INSERT INTO `webtrator` (`id`, `isim`, `eposta`, `pass`, `cookie`, `son_giris`, `durum`) VALUES
(3, 'Ahmet Kanbur', '6c996f3ce83ad557eea1980ca8ca065b', '6c996f3ce83ad557eea1980ca8ca065b', 'e23ae1b61bf5bd142be9a8dea31f3524', '2015-12-31 17:37:47', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `activation_codes`
--
ALTER TABLE `activation_codes`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cart_contents`
--
ALTER TABLE `cart_contents`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `login_throttle_records`
--
ALTER TABLE `login_throttle_records`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `meta_tags`
--
ALTER TABLE `meta_tags`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products_deleted`
--
ALTER TABLE `products_deleted`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `showcase_campaign`
--
ALTER TABLE `showcase_campaign`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `showcase_category`
--
ALTER TABLE `showcase_category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `showcase_home`
--
ALTER TABLE `showcase_home`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `showcase_new`
--
ALTER TABLE `showcase_new`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `sqlcache`
--
ALTER TABLE `sqlcache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cache_ismi` (`cache_ismi`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user_carts`
--
ALTER TABLE `user_carts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `variant_defs`
--
ALTER TABLE `variant_defs`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `variant_main`
--
ALTER TABLE `variant_main`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `variant_products`
--
ALTER TABLE `variant_products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `variant_sub`
--
ALTER TABLE `variant_sub`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `webtrator`
--
ALTER TABLE `webtrator`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `activation_codes`
--
ALTER TABLE `activation_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `cart_contents`
--
ALTER TABLE `cart_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Tablo için AUTO_INCREMENT değeri `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Tablo için AUTO_INCREMENT değeri `guests`
--
ALTER TABLE `guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Tablo için AUTO_INCREMENT değeri `login_throttle_records`
--
ALTER TABLE `login_throttle_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Tablo için AUTO_INCREMENT değeri `meta_tags`
--
ALTER TABLE `meta_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Tablo için AUTO_INCREMENT değeri `products_deleted`
--
ALTER TABLE `products_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Tablo için AUTO_INCREMENT değeri `showcase_campaign`
--
ALTER TABLE `showcase_campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `showcase_category`
--
ALTER TABLE `showcase_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `showcase_home`
--
ALTER TABLE `showcase_home`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Tablo için AUTO_INCREMENT değeri `showcase_new`
--
ALTER TABLE `showcase_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `sqlcache`
--
ALTER TABLE `sqlcache`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `user_carts`
--
ALTER TABLE `user_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Tablo için AUTO_INCREMENT değeri `variant_defs`
--
ALTER TABLE `variant_defs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- Tablo için AUTO_INCREMENT değeri `variant_main`
--
ALTER TABLE `variant_main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Tablo için AUTO_INCREMENT değeri `variant_products`
--
ALTER TABLE `variant_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- Tablo için AUTO_INCREMENT değeri `variant_sub`
--
ALTER TABLE `variant_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `webtrator`
--
ALTER TABLE `webtrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
