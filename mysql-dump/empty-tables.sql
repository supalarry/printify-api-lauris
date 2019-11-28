-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: mysqlDB
-- Generation Time: Nov 27, 2019 at 10:14 AM
-- Server version: 8.0.18
-- PHP Version: 7.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE EVENT country_codes_counter_reset
ON SCHEDULE EVERY 1 MINUTE
STARTS CURRENT_TIMESTAMP
DO
UPDATE country_codes SET requests = 0;

--
-- Database: `printify-products`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_products`
--

CREATE TABLE `orders_products` (
  `id` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `productType` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders_products`
--
ALTER TABLE `orders_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `country_codes`
--

CREATE TABLE `country_codes` (
  `id` int(11) NOT NULL,
  `country` varchar(3) NOT NULL,
  `requests` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `country_codes`
--

INSERT INTO `country_codes` (`id`, `country`, `requests`) VALUES
(1, 'AF', 0),
(2, 'AX', 0),
(3, 'AL', 0),
(4, 'DZ', 0),
(5, 'AS', 0),
(6, 'AD', 0),
(7, 'AO', 0),
(8, 'AI', 0),
(9, 'AQ', 0),
(10, 'AG', 0),
(11, 'AR', 0),
(12, 'AM', 0),
(13, 'AW', 0),
(14, 'AU', 0),
(15, 'AT', 0),
(16, 'AZ', 0),
(17, 'BS', 0),
(18, 'BH', 0),
(19, 'BD', 0),
(20, 'BB', 0),
(21, 'BY', 0),
(22, 'BE', 0),
(23, 'BZ', 0),
(24, 'BJ', 0),
(25, 'BM', 0),
(26, 'BT', 0),
(27, 'BO', 0),
(28, 'BQ', 0),
(29, 'BA', 0),
(30, 'BW', 0),
(31, 'BV', 0),
(32, 'BR', 0),
(33, 'IO', 0),
(34, 'BN', 0),
(35, 'BG', 0),
(36, 'BF', 0),
(37, 'BI', 0),
(38, 'KH', 0),
(39, 'CM', 0),
(40, 'CA', 0),
(41, 'CV', 0),
(42, 'KY', 0),
(43, 'CF', 0),
(44, 'TD', 0),
(45, 'CL', 0),
(46, 'CN', 0),
(47, 'CX', 0),
(48, 'CC', 0),
(49, 'CO', 0),
(50, 'KM', 0),
(51, 'CG', 0),
(52, 'CD', 0),
(53, 'CK', 0),
(54, 'CR', 0),
(55, 'CI', 0),
(56, 'HR', 0),
(57, 'CU', 0),
(58, 'CW', 0),
(59, 'CY', 0),
(60, 'CZ', 0),
(61, 'DK', 0),
(62, 'DJ', 0),
(63, 'DM', 0),
(64, 'DO', 0),
(65, 'EC', 0),
(66, 'EG', 0),
(67, 'SV', 0),
(68, 'GQ', 0),
(69, 'ER', 0),
(70, 'EE', 0),
(71, 'ET', 0),
(72, 'FK', 0),
(73, 'FO', 0),
(74, 'FJ', 0),
(75, 'FI', 0),
(76, 'FR', 0),
(77, 'GF', 0),
(78, 'PF', 0),
(79, 'TF', 0),
(80, 'GA', 0),
(81, 'GM', 0),
(82, 'GE', 0),
(83, 'DE', 0),
(84, 'GH', 0),
(85, 'GI', 0),
(86, 'GR', 0),
(87, 'GL', 0),
(88, 'GD', 0),
(89, 'GP', 0),
(90, 'GU', 0),
(91, 'GT', 0),
(92, 'GG', 0),
(93, 'GN', 0),
(94, 'GW', 0),
(95, 'GY', 0),
(96, 'HT', 0),
(97, 'HM', 0),
(98, 'VA', 0),
(99, 'HN', 0),
(100, 'HK', 0),
(101, 'HU', 0),
(102, 'IS', 0),
(103, 'IN', 0),
(104, 'ID', 0),
(105, 'IR', 0),
(106, 'IQ', 0),
(107, 'IE', 0),
(108, 'IM', 0),
(109, 'IL', 0),
(110, 'IT', 0),
(111, 'JM', 0),
(112, 'JP', 0),
(113, 'JE', 0),
(114, 'JO', 0),
(115, 'KZ', 0),
(116, 'KE', 0),
(117, 'KI', 0),
(118, 'KP', 0),
(119, 'KR', 0),
(120, 'KW', 0),
(121, 'KG', 0),
(122, 'LA', 0),
(123, 'LV', 0),
(124, 'LB', 0),
(125, 'LS', 0),
(126, 'LR', 0),
(127, 'LY', 0),
(128, 'LI', 0),
(129, 'LT', 0),
(130, 'LU', 0),
(131, 'MO', 0),
(132, 'MK', 0),
(133, 'MG', 0),
(134, 'MW', 0),
(135, 'MY', 0),
(136, 'MV', 0),
(137, 'ML', 0),
(138, 'MT', 0),
(139, 'MH', 0),
(140, 'MQ', 0),
(141, 'MR', 0),
(142, 'MU', 0),
(143, 'YT', 0),
(144, 'MX', 0),
(145, 'FM', 0),
(146, 'MD', 0),
(147, 'MC', 0),
(148, 'MN', 0),
(149, 'ME', 0),
(150, 'MS', 0),
(151, 'MA', 0),
(152, 'MZ', 0),
(153, 'MM', 0),
(154, 'NA', 0),
(155, 'NR', 0),
(156, 'NP', 0),
(157, 'NL', 0),
(158, 'NC', 0),
(159, 'NZ', 0),
(160, 'NI', 0),
(161, 'NE', 0),
(162, 'NG', 0),
(163, 'NU', 0),
(164, 'NF', 0),
(165, 'MP', 0),
(166, 'NO', 0),
(167, 'OM', 0),
(168, 'PK', 0),
(169, 'PW', 0),
(170, 'PS', 0),
(171, 'PA', 0),
(172, 'PG', 0),
(173, 'PY', 0),
(174, 'PE', 0),
(175, 'PH', 0),
(176, 'PN', 0),
(177, 'PL', 0),
(178, 'PT', 0),
(179, 'PR', 0),
(180, 'QA', 0),
(181, 'RE', 0),
(182, 'RO', 0),
(183, 'RU', 0),
(184, 'RW', 0),
(185, 'BL', 0),
(186, 'SH', 0),
(187, 'KN', 0),
(188, 'LC', 0),
(189, 'MF', 0),
(190, 'PM', 0),
(191, 'VC', 0),
(192, 'WS', 0),
(193, 'SM', 0),
(194, 'ST', 0),
(195, 'SA', 0),
(196, 'SN', 0),
(197, 'RS', 0),
(198, 'SC', 0),
(199, 'SL', 0),
(200, 'SG', 0),
(201, 'SX', 0),
(202, 'SK', 0),
(203, 'SI', 0),
(204, 'SB', 0),
(205, 'SO', 0),
(206, 'ZA', 0),
(207, 'GS', 0),
(208, 'SS', 0),
(209, 'ES', 0),
(210, 'LK', 0),
(211, 'SD', 0),
(212, 'SR', 0),
(213, 'SJ', 0),
(214, 'SZ', 0),
(215, 'SE', 0),
(216, 'CH', 0),
(217, 'SY', 0),
(218, 'TW', 0),
(219, 'TJ', 0),
(220, 'TZ', 0),
(221, 'TH', 0),
(222, 'TL', 0),
(223, 'TG', 0),
(224, 'TK', 0),
(225, 'TO', 0),
(226, 'TT', 0),
(227, 'TN', 0),
(228, 'TR', 0),
(229, 'TM', 0),
(230, 'TC', 0),
(231, 'TV', 0),
(232, 'UG', 0),
(233, 'UA', 0),
(234, 'AE', 0),
(235, 'GB', 0),
(236, 'US', 0),
(237, 'UM', 0),
(238, 'UY', 0),
(239, 'UZ', 0),
(240, 'VU', 0),
(241, 'VE', 0),
(242, 'VN', 0),
(243, 'VG', 0),
(244, 'VI', 0),
(245, 'WF', 0),
(246, 'EH', 0),
(247, 'YE', 0),
(248, 'ZM', 0),
(249, 'ZW', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country_codes`
--
ALTER TABLE `country_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `country_codes`
--
ALTER TABLE `country_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
