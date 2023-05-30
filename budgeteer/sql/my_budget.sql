-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 05:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_budget`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expense_amount` decimal(8,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `expense_comment` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `user_id`, `expense_amount`, `expense_date`, `payment_method_id`, `category_id`, `expense_comment`) VALUES
(7, 12, 500.00, '2023-04-21', 1, 1, 'jabee'),
(8, 12, 700.00, '2023-05-21', 1, 1, ''),
(9, 12, 500.00, '2023-05-21', 1, 2, ''),
(10, 12, 1000.00, '2023-05-21', 2, 2, NULL),
(11, 12, 100.00, '2023-05-22', 1, 1, ''),
(12, 12, 200.00, '2023-05-23', 1, 1, ''),
(13, 12, 300.00, '2023-05-20', 1, 1, ''),
(14, 12, 350.75, '2023-05-23', 3, 8, ''),
(16, 12, 700.00, '2023-05-25', 1, 3, ''),
(17, 12, 2000.00, '2023-05-26', 1, 9, ''),
(18, 15, 1003.00, '2023-05-29', 1, 1, ''),
(19, 15, 2000.00, '2023-05-29', 1, 1, ''),
(20, 12, 85.50, '2023-05-29', 1, 1, 'hatdog');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `category_id` int(11) NOT NULL,
  `expense_category` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`category_id`, `expense_category`) VALUES
(1, 'Food'),
(2, 'House'),
(3, 'Transport'),
(4, 'Telecom'),
(5, 'Healthcare'),
(6, 'Clothing'),
(7, 'Hygiene'),
(8, 'Kids'),
(9, 'Entertainment'),
(10, 'Trip'),
(11, 'Trainings'),
(12, 'Books'),
(13, 'Savings'),
(14, 'Old Age Pension'),
(15, 'Debt Repayment'),
(16, 'Donation'),
(17, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `income_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `income_amount` decimal(8,2) NOT NULL,
  `income_date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `income_comment` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`income_id`, `user_id`, `income_amount`, `income_date`, `category_id`, `income_comment`) VALUES
(40, 12, 20000.00, '2023-04-21', 1, ''),
(41, 12, 6.50, '2023-05-21', 4, ''),
(42, 12, 1000.00, '2023-05-23', 2, ''),
(43, 12, 5000.00, '2023-05-26', 1, ''),
(44, 12, 185.00, '2023-05-27', 1, ''),
(46, 12, 500.00, '2023-05-27', 1, ''),
(47, 12, 189.00, '2023-05-27', 1, ''),
(48, 12, 189.00, '2023-05-27', 1, ''),
(49, 12, 189.00, '2023-05-27', 1, ''),
(50, 12, 561.00, '2023-05-27', 1, ''),
(51, 12, 561.00, '2023-05-27', 1, ''),
(52, 15, 20000.00, '2023-05-29', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `income_categories`
--

CREATE TABLE `income_categories` (
  `category_id` int(11) NOT NULL,
  `income_category` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income_categories`
--

INSERT INTO `income_categories` (`category_id`, `income_category`) VALUES
(1, 'Salary'),
(2, 'Bank Interest'),
(3, 'Vending'),
(4, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`payment_method_id`, `payment_method`) VALUES
(1, 'Cash'),
(2, 'Debit Card'),
(3, 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`) VALUES
(12, 'Johnss', 'Vincent', 'user', 'user@gmail.com', '$2y$10$uuTf4mMjLXLzqToYbhA4dO8Wo.blAhtFKlRNvExG3uFlvWPuJ.i6G'),
(13, 'JV Renzz', 'Cimafranca', 'jv renz', 'jvrenz@gmail.com', '$2y$10$uuTf4mMjLXLzqToYbhA4dO8Wo.blAhtFKlRNvExG3uFlvWPuJ.i6G'),
(14, 'JV Renz', 'Cimafranca', 'jvrenz', 'jv@gmail.com', '$2y$10$uuTf4mMjLXLzqToYbhA4dO8Wo.blAhtFKlRNvExG3uFlvWPuJ.i6G'),
(15, 'blesse', 'borromeo', 'blesss', 'blessebor@gmail.com', '$2y$10$AwmBUhPJRLjAqUtYIBNuG.cRwCF7xCSlcXfGqy/saevk59g1952q.'),
(16, 'blesse', 'borromeo', 'blesss', 'blesse@gmail.com', '$2y$10$9IMnhY7/A8/ezR/2PXS5betesLmXBzZ.oYvcoA.7Jc6vaP2/i5/vK'),
(17, 'mm', 'mm', 'user', 'userr@gmail.com', '$2y$10$rkqHrsbEonj20WM7p1Mweez5T3d3ibIrom7aatenEQWLt57l6qPlm'),
(18, 'blesse', 'borromeo', 'user', 'bbor@gmail.com', '$2y$10$H4lJfKq8qh2kGYDfbq8OkOt1/NuYlM4vCl7vKD1okpEGoYFLy9b2K'),
(19, 'blesse', 'borromeo', 'bbor', 'bles@gmail.com', '$2y$10$UvTy/H.n9LTtXM6OSH5dFuhDgQp1hGK.GqBXMlXxWapCFVBFKuZay'),
(20, 'Renz', 'JV', 'jrc', 'jrc@gmail.com', '$2y$10$JYqAJLjwdcvHKrg03ntINOB3heuUl3PWaIKlV/il0mw.QLVu9wYGG');

-- --------------------------------------------------------

--
-- Table structure for table `user_expense_category`
--

CREATE TABLE `user_expense_category` (
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_expense_category`
--

INSERT INTO `user_expense_category` (`user_id`, `category_id`) VALUES
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(12, 6),
(12, 7),
(12, 8),
(12, 9),
(12, 10),
(12, 11),
(12, 12),
(12, 13),
(12, 14),
(12, 15),
(12, 16),
(12, 17),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(14, 6),
(14, 7),
(14, 8),
(14, 9),
(14, 10),
(14, 11),
(14, 12),
(14, 13),
(14, 14),
(14, 15),
(14, 16),
(14, 17),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(15, 5),
(15, 6),
(15, 7),
(15, 8),
(15, 9),
(15, 10),
(15, 11),
(15, 12),
(15, 13),
(15, 14),
(15, 15),
(15, 16),
(15, 17),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(16, 5),
(16, 6),
(16, 7),
(16, 8),
(16, 9),
(16, 10),
(16, 11),
(16, 12),
(16, 13),
(16, 14),
(16, 15),
(16, 16),
(16, 17),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(17, 5),
(17, 6),
(17, 7),
(17, 8),
(17, 9),
(17, 10),
(17, 11),
(17, 12),
(17, 13),
(17, 14),
(17, 15),
(17, 16),
(17, 17),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(18, 7),
(18, 8),
(18, 9),
(18, 10),
(18, 11),
(18, 12),
(18, 13),
(18, 14),
(18, 15),
(18, 16),
(18, 17),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(19, 5),
(19, 6),
(19, 7),
(19, 8),
(19, 9),
(19, 10),
(19, 11),
(19, 12),
(19, 13),
(19, 14),
(19, 15),
(19, 16),
(19, 17),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(20, 7),
(20, 8),
(20, 9),
(20, 10),
(20, 11),
(20, 12),
(20, 13),
(20, 14),
(20, 15),
(20, 16),
(20, 17);

-- --------------------------------------------------------

--
-- Table structure for table `user_income_category`
--

CREATE TABLE `user_income_category` (
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_income_category`
--

INSERT INTO `user_income_category` (`user_id`, `category_id`) VALUES
(12, 1),
(12, 2),
(12, 3),
(12, 4),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(15, 1),
(15, 2),
(15, 3),
(15, 4),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(20, 1),
(20, 2),
(20, 3),
(20, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_method`
--

CREATE TABLE `user_payment_method` (
  `user_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payment_method`
--

INSERT INTO `user_payment_method` (`user_id`, `payment_method_id`) VALUES
(12, 1),
(12, 2),
(12, 3),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(15, 2),
(15, 3),
(16, 1),
(16, 2),
(16, 3),
(17, 1),
(17, 2),
(17, 3),
(18, 1),
(18, 2),
(18, 3),
(19, 1),
(19, 2),
(19, 3),
(20, 1),
(20, 2),
(20, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`income_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_expense_category`
--
ALTER TABLE `user_expense_category`
  ADD PRIMARY KEY (`user_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user_income_category`
--
ALTER TABLE `user_income_category`
  ADD PRIMARY KEY (`user_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD PRIMARY KEY (`user_id`,`payment_method_id`),
  ADD KEY `payment_method_id` (`payment_method_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `income_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`payment_method_id`),
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`category_id`);

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `income_categories` (`category_id`),
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_expense_category`
--
ALTER TABLE `user_expense_category`
  ADD CONSTRAINT `user_expense_category_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_expense_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`category_id`);

--
-- Constraints for table `user_income_category`
--
ALTER TABLE `user_income_category`
  ADD CONSTRAINT `user_income_category_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_income_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `income_categories` (`category_id`);

--
-- Constraints for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD CONSTRAINT `user_payment_method_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_payment_method_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`payment_method_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
