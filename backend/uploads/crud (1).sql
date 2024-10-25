-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2024 at 01:03 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `audit_id` int NOT NULL,
  `shop_id` int DEFAULT NULL,
  `old_quantity` int DEFAULT NULL,
  `new_quantity` int DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `audit`
--

INSERT INTO `audit` (`audit_id`, `shop_id`, `old_quantity`, `new_quantity`, `type`, `created_at`) VALUES
(105, 46, 6, 4, 'sold', '2023-12-21 17:12:36'),
(106, 53, 20, 19, 'sold', '2023-12-21 17:12:37'),
(107, 53, 19, 18, 'sold', '2023-12-21 17:14:12'),
(108, 46, 4, 3, 'sold', '2023-12-22 05:23:39'),
(109, 46, 3, 2, 'sold', '2023-12-22 05:24:29'),
(110, 53, 18, 17, 'sold', '2023-12-22 05:24:29'),
(111, 46, 2, 1, 'sold', '2023-12-23 08:44:24'),
(112, 53, 17, 16, 'sold', '2023-12-28 04:44:38'),
(114, 46, 1, 6, 'inbound', '2024-04-14 08:11:08'),
(115, 46, 6, 5, 'sold', '2024-04-14 08:13:21'),
(116, 46, 5, 4, 'sold', '2024-04-14 08:14:40'),
(117, 46, 4, 3, 'sold', '2024-04-14 08:14:45'),
(118, 46, 3, 2, 'sold', '2024-04-14 08:14:50'),
(119, 46, 2, 0, 'sold', '2024-04-14 08:15:39'),
(120, 46, 0, 2, 'inbound', '2024-04-14 19:29:23'),
(121, 46, 2, -3, 'inbound', '2024-04-14 23:54:27'),
(122, 46, -3, 3, 'inbound', '2024-04-14 23:54:33'),
(123, 46, 3, 1, 'sold', '2024-05-05 01:42:53'),
(124, 53, 16, 15, 'sold', '2024-05-09 03:41:54'),
(125, 53, 15, 14, 'sold', '2024-05-09 03:41:58'),
(126, 46, 1, 3, 'inbound', '2024-08-02 04:57:58'),
(127, 46, 3, 5, 'inbound', '2024-08-03 03:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `book_id` int NOT NULL,
  `id` int NOT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime NOT NULL,
  `adult` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `child` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `specialRequest` varchar(16000) NOT NULL,
  `room_id` int NOT NULL,
  `booking_status` varchar(10) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `downpayment` double NOT NULL,
  `message` varchar(200) NOT NULL,
  `booking_qr` text NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `id`, `checkin`, `checkout`, `adult`, `child`, `specialRequest`, `room_id`, `booking_status`, `payment_method`, `downpayment`, `message`, `booking_qr`, `expiration`) VALUES
(207, 102, '2024-05-13 11:13:00', '2024-05-13 17:13:00', NULL, NULL, 'ds', 7, 'declined', 'online', 21, 'Booking declined.', '102', '2024-05-13 17:13:00'),
(208, 102, '2024-08-29 18:10:00', '2024-08-30 15:10:00', NULL, NULL, 'ad', 21, 'pending', 'online', 0, '', '102', '2024-08-30 15:10:00'),
(209, 102, '2024-08-31 18:11:00', '2024-09-01 15:11:00', NULL, NULL, 'sdf', 21, 'pending', 'cash', 111, '', '102', '2024-09-01 15:11:00'),
(210, 102, '2024-08-15 13:01:00', '2024-08-16 10:01:00', NULL, NULL, 'lknj', 7, 'pending', 'online', 300, '', '102', '2024-08-16 10:01:00'),
(211, 102, '2024-08-15 13:03:00', '2024-08-16 10:03:00', NULL, NULL, 'sda', 7, 'pending', 'online', 200, '', '102', '2024-08-16 10:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `id` int NOT NULL,
  `shop_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `id`, `shop_id`, `quantity`) VALUES
(153, 102, 53, 1),
(154, 102, 46, 2),
(155, 100, 46, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cottage`
--

CREATE TABLE `cottage` (
  `cottage_id` int NOT NULL,
  `cottage_image` varchar(200) DEFAULT NULL,
  `cottage_name` text NOT NULL,
  `cottage_description` varchar(200) NOT NULL,
  `cottage_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cottage`
--

INSERT INTO `cottage` (`cottage_id`, `cottage_image`, `cottage_name`, `cottage_description`, `cottage_price`) VALUES
(1, 'coffee-1-1-1664301131.jpg', 'aeda', 'dsa', 200);

-- --------------------------------------------------------

--
-- Table structure for table `cottagebooking`
--

CREATE TABLE `cottagebooking` (
  `cottagebooking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `selectedTime` datetime NOT NULL,
  `selectedTimeout` datetime NOT NULL,
  `cottage_id` int NOT NULL,
  `cottagebooking_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cottagebooking`
--

INSERT INTO `cottagebooking` (`cottagebooking_id`, `user_id`, `selectedTime`, `selectedTimeout`, `cottage_id`, `cottagebooking_status`, `created_at`) VALUES
(1, 102, '2024-09-02 00:53:00', '2024-09-02 06:53:00', 1, 'pending', '2024-09-01 16:53:14'),
(2, 102, '2024-09-02 03:17:00', '2024-09-02 09:17:00', 1, 'pending', '2024-09-01 19:17:59'),
(3, 102, '2024-09-02 04:55:00', '2024-09-02 10:55:00', 1, 'pending', '2024-09-01 20:55:41'),
(4, 102, '2024-09-10 11:10:00', '2024-09-10 17:10:00', 1, 'pending', '2024-09-10 03:10:04'),
(5, 100, '2024-09-11 11:48:00', '2024-09-11 17:48:00', 1, 'pending', '2024-09-11 03:48:35'),
(6, 100, '2024-09-11 12:02:00', '2024-09-11 18:02:00', 1, 'pending', '2024-09-11 04:02:05'),
(7, 102, '2024-09-11 13:02:00', '2024-09-11 19:02:00', 1, 'pending', '2024-09-11 05:02:37'),
(8, 102, '2024-09-11 13:09:00', '2024-09-11 19:09:00', 1, 'pending', '2024-09-11 05:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `enroll_id` int NOT NULL,
  `id` int NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `contact_number` int NOT NULL,
  `experience` varchar(50) NOT NULL,
  `lesson_date` date NOT NULL,
  `enrollment_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enroll_id`, `id`, `fullname`, `age`, `contact_number`, `experience`, `lesson_date`, `enrollment_status`) VALUES
(12, 102, 'asa', 21, 2147483647, 'skdjsdjad', '2024-04-01', 'pending'),
(13, 102, 'sndadn', 12, 92971012, 'dsadasda', '2024-04-02', 'pending'),
(14, 102, 'sadada', 21, 92139212, 'asdadada', '2024-04-02', 'pending'),
(17, 102, 'sada', 21, 92319823, 'Beginner', '2024-04-02', 'declined'),
(18, 102, 'John', 21, 2147483647, 'Beginner', '2024-04-01', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_date`
--

CREATE TABLE `enrollment_date` (
  `enrolldate_id` int NOT NULL,
  `availableDates` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrollment_date`
--

INSERT INTO `enrollment_date` (`enrolldate_id`, `availableDates`) VALUES
(1, '2024-03-19'),
(2, '2024-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int NOT NULL,
  `id` int NOT NULL,
  `eventName` varchar(50) NOT NULL,
  `eventTheme` varchar(50) NOT NULL,
  `eventStatus` varchar(10) NOT NULL,
  `eventDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `id`, `eventName`, `eventTheme`, `eventStatus`, `eventDate`) VALUES
(8, 102, 'ok', 'fd', 'pending', '2024-04-14 23:26:00'),
(9, 102, 'ew', 'ew', 'pending', '2024-04-14 23:27:00'),
(10, 102, 'ZX', 'zx', 'pending', '2024-04-14 23:33:00'),
(11, 102, 'as', 'sa', 'pending', '2024-04-14 23:34:00'),
(12, 102, 'Birthday', 'ANY', 'pending', '2024-04-23 15:43:00');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feed_id` int NOT NULL,
  `id` int NOT NULL,
  `feedback` varchar(16000) NOT NULL,
  `is_hidden` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int NOT NULL,
  `id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `id`, `created_at`, `order_id`) VALUES
(285, 102, '2024-04-14 15:05:37', 181),
(286, 102, '2024-04-14 16:07:30', 182),
(287, 102, '2024-04-14 16:09:32', 183),
(288, 102, '2024-04-14 16:11:29', 184),
(289, 102, '2024-04-14 16:15:20', 185),
(290, 102, '2024-04-14 16:25:03', 186),
(291, 102, '2024-04-14 23:16:09', 187),
(292, 102, '2024-04-15 07:52:26', 188),
(294, 102, '2024-05-09 11:34:01', 190),
(295, 102, '2024-08-08 05:05:36', 191);

-- --------------------------------------------------------

--
-- Table structure for table `manifest`
--

CREATE TABLE `manifest` (
  `manifest_id` int NOT NULL,
  `manifest` varchar(16000) NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `manifest`
--

INSERT INTO `manifest` (`manifest_id`, `manifest`, `id`) VALUES
(23, 'ewqq', 100),
(24, 'sda', 100),
(25, 'sfsfs', 100);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int NOT NULL,
  `item_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `item_name` text NOT NULL,
  `item_category` text NOT NULL,
  `item_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `item_image`, `item_name`, `item_category`, `item_price`) VALUES
(1, NULL, 'Beef Tapa', 'breakfast', 230),
(2, NULL, 'Corned Beef', 'breakfast', 230),
(3, NULL, 'Chicken Tocino', 'breakfast', 230),
(4, NULL, 'Boneless Bangus', 'breakfast', 230),
(5, NULL, 'Daing na Biya', 'breakfast', 230),
(6, NULL, 'Hotdog', 'breakfast', 230),
(7, NULL, 'Longganisa', 'breakfast', 230),
(8, NULL, 'Chicken Longganisa', 'breakfast', 230),
(9, NULL, 'Bread and Omelete', 'breakfast', 230);

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

CREATE TABLE `notif` (
  `notif_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL,
  `shop_id` int NOT NULL,
  `total` int NOT NULL,
  `token` varchar(200) NOT NULL,
  `notif_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `id` int NOT NULL,
  `message` varchar(255) NOT NULL,
  `notifstatus` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `id`, `message`, `notifstatus`) VALUES
(19, 102, 'Your booking for Deluxe 1 has been accepted.', ''),
(20, 102, 'Your booking has been declined.', ''),
(21, 102, 'Your order has been accepted.', ''),
(22, 102, 'Your order has been accepted.', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` int NOT NULL,
  `order_payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `id`, `created_at`, `order_status`, `updated_at`, `total_price`, `order_payment_method`) VALUES
(181, 102, '2024-04-14 23:05:37', 'paid', '2024-04-14 23:05:37', 100, 'paypal'),
(182, 102, '2024-04-15 00:07:30', 'paid', '2024-04-15 00:07:30', 100, 'paypal'),
(183, 102, '2023-04-15 00:09:32', 'paid', '2024-04-15 00:09:32', 100, 'credit_card'),
(184, 102, '2024-04-15 00:11:29', 'paid', '2024-04-15 00:11:29', 100, 'paypal'),
(185, 102, '2024-04-15 00:15:20', 'paid', '2024-05-08 00:15:20', 200, 'cash'),
(186, 102, '2024-04-15 00:25:03', 'declined', '2024-04-15 00:25:03', 2121, 'paypal'),
(187, 102, '2024-04-15 07:16:09', 'paid', '2024-04-15 07:16:09', 100, 'paypal'),
(188, 102, '2024-04-15 15:52:26', 'paid', '2024-04-15 15:52:26', 100, 'credit_card'),
(190, 102, '2024-05-09 19:34:01', 'paid', '2024-05-09 19:34:01', 100, 'cash'),
(191, 102, '2024-08-08 13:05:36', 'pending', '2024-08-08 13:05:36', 100, 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `menu_item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `reservation_id`, `menu_item_id`, `quantity`, `created_at`) VALUES
(5, 5, 1, 1, '2024-09-14 12:46:21'),
(6, 6, 1, 1, '2024-09-14 12:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `order_list_id` int NOT NULL,
  `order_id` int NOT NULL,
  `shop_id` int NOT NULL,
  `quantity` int NOT NULL,
  `final_price` int NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`order_list_id`, `order_id`, `shop_id`, `quantity`, `final_price`, `id`) VALUES
(175, 181, 46, 1, 100, 102),
(176, 182, 46, 1, 100, 102),
(177, 183, 46, 1, 100, 102),
(178, 184, 46, 1, 100, 102),
(179, 185, 46, 2, 200, 102),
(180, 186, 54, 1, 2121, 102),
(181, 187, 53, 1, 100, 102),
(182, 188, 53, 1, 100, 102),
(184, 190, 53, 1, 100, 102),
(185, 191, 53, 1, 100, 102);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int NOT NULL,
  `invoice_id` int DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `table_id` int NOT NULL,
  `reservation_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `table_id`, `reservation_time`, `created_at`) VALUES
(5, 102, 1, '2024-09-21 12:46:00', '2024-09-14 12:46:21'),
(6, 102, 1, '2024-09-26 12:47:00', '2024-09-14 12:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` varchar(50) NOT NULL,
  `bed` int NOT NULL,
  `bath` int NOT NULL,
  `packs` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `room_status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `image`, `image2`, `image3`, `room_name`, `price`, `bed`, `bath`, `packs`, `description`, `room_status`) VALUES
(7, 'Deluxe 1.jpg', 'Deluxe 1.jpg', 'Deluxe 1.jpg', 'Deluxe 1', '2,500', 1, 1, 3, '1 Queen-size Bed with Pull Out Bed', 'pending'),
(8, 'Family Room 1.jpg', 'Family Room 1.jpg', 'Family Room 1.jpg', 'Family Room 1', '4,000', 2, 1, 4, '1 Queen-size Bed and 1 Single Bed with Pull Out Bed', 'pending'),
(18, 'Studio Type 1.jpg', 'Studio Type 1.jpg', 'Studio Type 1.jpg', 'Studio Type 1', '1,000', 4, 1, 6, '1 Queen-size Bed, 1 Double Decker and 1 Single Bed with Pull Out Bed', 'pending'),
(20, 'Studio Type 2.jpg', 'Studio Type 2.jpg', 'Studio Type 2.jpg', 'Studio Type 2', '1,000', 4, 1, 6, '3 Double Decker', 'pending'),
(21, 'Family Room 2.jpg', 'Family Room 2.jpg', 'Family Room 2.jpg', 'Family Room 2', '4,000', 2, 1, 4, '1 Queen-size Bed and 1 Single Bed with Pull Out Bed', 'pending'),
(25, '7be372c0-ecb1-4bc9-b536-e4919f67ce1a.jpg', NULL, NULL, 'sdz', '333', 1, 1, 3, 'dasa', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shop_id` int NOT NULL,
  `prod_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prod_name` varchar(50) NOT NULL,
  `prod_desc` varchar(16000) NOT NULL,
  `prod_price` int NOT NULL,
  `prod_quantity` int NOT NULL,
  `rating` varchar(10) NOT NULL,
  `ratings_count` int NOT NULL,
  `total_ratings` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`shop_id`, `prod_img`, `prod_name`, `prod_desc`, `prod_price`, `prod_quantity`, `rating`, `ratings_count`, `total_ratings`) VALUES
(46, 'coffee-1-1-1664301131.jpg', 'Coffee', 'Hot Brew', 100, 5, '2.83333333', 6, 17),
(53, 'maxresdefault.jpg', 'Taro Milk Tea', 'test', 100, 14, '5', 1, 5),
(54, 'maxresdefault.jpg', 'a;sjdas', 'aslksakd', 2121, 22, '4', 2, 8),
(58, 'WIN_20240508_12_12_57_Pro.jpg', 'asa', 'asda2', 2, 2, '', 0, 0),
(59, 'images-removebg-preview.png', 'as', 'sdsa', 22, 22, '', 0, 0),
(60, '66e040730fd37.png', 'sda', 'sda', 22, 33, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int NOT NULL,
  `staff_image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `staff_name` varchar(50) NOT NULL,
  `staff_email` varchar(50) NOT NULL,
  `contactNum` int NOT NULL,
  `hide_staff` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_image`, `staff_name`, `staff_email`, `contactNum`, `hide_staff`) VALUES
(2, 'IMG_20220302_083334.jpg', 'John Lloyd', 'Caringal@gmail.com', 923267221, ''),
(4, 'FB_IMG_16286529045750473.jpg', 'Matthew', 'balmes@gmail.com', 9324291, ''),
(5, 'Picture1.jpg', 'Joanne', 'manalo@gmail.com', 2147483647, ''),
(9, 'vero_WP_5000x3125.jpg', 'ashbdas', 'asldmad@gmail.com', 1928371312, '1'),
(13, '422746525_850032250213199_6709534189734238524_n.jpg', 'Clarish', 'jrishh2902@gmail.com', 98123962, '1');

-- --------------------------------------------------------

--
-- Table structure for table `swimming_sched`
--

CREATE TABLE `swimming_sched` (
  `id` int NOT NULL,
  `swimming_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `swimming_sched`
--

INSERT INTO `swimming_sched` (`id`, `swimming_date`) VALUES
(1, '2024-04-03'),
(2, '2024-04-02'),
(3, '2024-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `table_orders`
--

CREATE TABLE `table_orders` (
  `table_order_id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `order_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `table_orders`
--

INSERT INTO `table_orders` (`table_order_id`, `reservation_id`, `order_time`, `created_at`) VALUES
(1, 5, '2024-09-21 12:46:00', '2024-09-14 12:46:21'),
(2, 6, '2024-09-26 12:47:00', '2024-09-14 12:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbltable`
--

CREATE TABLE `tbltable` (
  `table_id` int NOT NULL,
  `table_name` varchar(10) NOT NULL,
  `table_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `table_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbltable`
--

INSERT INTO `tbltable` (`table_id`, `table_name`, `table_description`, `table_price`) VALUES
(1, 'table 1', 'sajdadaksd', 100);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` varchar(10) NOT NULL,
  `role` varchar(10) NOT NULL,
  `token` varchar(200) NOT NULL,
  `ver_token` varchar(500) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `address`, `number`, `email`, `password`, `status`, `role`, `token`, `ver_token`, `expiry`) VALUES
(100, 'JohnLloyd', '', '0', 'caringaljohnlloyd17@gmail.com', '$2y$10$/KoZLn7WG5K1ma0U4bUg2.fSpJ2qR1mDwNC.tCLVyNb8iwQfNuOKG', 'active', 'admin', 'R{$@o3}6],#BJw7ZlWr<aCA(z5F.ghk[*tPOpGVXLNS)qj=Mmn', '0', '2024-02-20 12:40:22'),
(102, 'jl', '', '0', 'caringaljohnlloyddd17@gmail.com', '$2y$10$oNWq8TsP9BIco3wA3nelKu5uGPIls99YLXUgM.mKpG3XG82E6AtTi', 'active', 'user', 'Kre=x_ztG{idUACl?*oVQ8Bb.mEwTZhsOa6WyIYHD)#-nNS0%c', '0', '2024-02-20 12:40:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `id` (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `id` (`id`,`shop_id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `cottage`
--
ALTER TABLE `cottage`
  ADD PRIMARY KEY (`cottage_id`);

--
-- Indexes for table `cottagebooking`
--
ALTER TABLE `cottagebooking`
  ADD PRIMARY KEY (`cottagebooking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cottage_id` (`cottage_id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`enroll_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `enrollment_date`
--
ALTER TABLE `enrollment_date`
  ADD PRIMARY KEY (`enrolldate_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feed_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `id` (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `manifest`
--
ALTER TABLE `manifest`
  ADD PRIMARY KEY (`manifest_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`order_list_id`),
  ADD KEY `order_id` (`order_id`,`shop_id`,`id`),
  ADD KEY `id` (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `swimming_sched`
--
ALTER TABLE `swimming_sched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_orders`
--
ALTER TABLE `table_orders`
  ADD PRIMARY KEY (`table_order_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `tbltable`
--
ALTER TABLE `tbltable`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `audit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `book_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `cottage`
--
ALTER TABLE `cottage`
  MODIFY `cottage_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cottagebooking`
--
ALTER TABLE `cottagebooking`
  MODIFY `cottagebooking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enroll_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `enrollment_date`
--
ALTER TABLE `enrollment_date`
  MODIFY `enrolldate_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feed_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `manifest`
--
ALTER TABLE `manifest`
  MODIFY `manifest_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `order_list_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shop_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `swimming_sched`
--
ALTER TABLE `swimming_sched`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `table_orders`
--
ALTER TABLE `table_orders`
  MODIFY `table_order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbltable`
--
ALTER TABLE `tbltable`
  MODIFY `table_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit`
--
ALTER TABLE `audit`
  ADD CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`shop_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`shop_id`);

--
-- Constraints for table `cottagebooking`
--
ALTER TABLE `cottagebooking`
  ADD CONSTRAINT `cottagebooking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cottagebooking_ibfk_2` FOREIGN KEY (`cottage_id`) REFERENCES `cottage` (`cottage_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `manifest`
--
ALTER TABLE `manifest`
  ADD CONSTRAINT `manifest_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu` (`menu_id`);

--
-- Constraints for table `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `order_list_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_list_ibfk_3` FOREIGN KEY (`shop_id`) REFERENCES `shop` (`shop_id`);

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`table_id`) REFERENCES `tbltable` (`table_id`);

--
-- Constraints for table `table_orders`
--
ALTER TABLE `table_orders`
  ADD CONSTRAINT `table_orders_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
