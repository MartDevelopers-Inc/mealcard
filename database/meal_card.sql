-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2021 at 05:05 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meal_card`
--

-- --------------------------------------------------------

--
-- Table structure for table `mailer_setttings`
--

CREATE TABLE `mailer_setttings` (
  `mailer_host` varchar(200) NOT NULL,
  `mailer_username` varchar(200) NOT NULL,
  `mailer_from_email` varchar(200) NOT NULL,
  `mailer_password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mailer_setttings`
--

INSERT INTO `mailer_setttings` (`mailer_host`, `mailer_username`, `mailer_from_email`, `mailer_password`) VALUES
('smtp.gmail.com', 'martdevelopers254@gmail.com', 'sandbox@martdev.info', '0704031263');

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `meal_id` varchar(200) NOT NULL,
  `meal_category_id` varchar(200) NOT NULL,
  `meal_name` varchar(200) DEFAULT NULL,
  `meal_img` varchar(200) DEFAULT NULL,
  `meal_details` longtext DEFAULT NULL,
  `meal_price` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`meal_id`, `meal_category_id`, `meal_name`, `meal_img`, `meal_details`, `meal_price`) VALUES
('0887a61603854c68c049892fccbd7b1105c270354f', 'bf13b0986358ea8e0836a1a588e4ffe73b09f4c927', 'Beef with Broccoli ', 'Meal_IMG_1635241078.webp', 'Chopping the beef into small pieces first means everything cooks through in about five minutes. ', '500'),
('5043b66aff066c89bb5b62f0cdd274f3cf39e56fd2', 'ede2326d7602212b187dba745d31730121ee615088', 'Oven-Baked French Bread Pizza', 'Meal_IMG_1635241100.webp', 'Pizza night is the best night — especially when everyone gets to build their dream slice. (Or three!) ', '800'),
('7656e72fc55df3eeb734e947e943106a99c66366b2', '88cb942ef57d35c2fb892d751aeb7919cf92948eb3', 'Stuffed Sweet Potatoes', 'Meal_IMG_1635241121.jpg', 'It\'s packed with protein and fiber, and you can add toppings — like avocado or sour cream — depending on what\'s in your fridge.', '150'),
('8964150bfeafe5104af54e48c186d4016a5bce8aa3', '6bb1cbb385fddfef7ac1d9a6d71025c76bfcc78e39', 'Upgraded Ramen ', NULL, 'It takes all of five minutes to fry some bacon + an egg. (Plus some green onions if you\'re feelin\' fancy.) ', '500'),
('add1f7e54c0b37fd68eee55b0d8da1a8ad2b024258', '6bb1cbb385fddfef7ac1d9a6d71025c76bfcc78e39', 'Pesto Chicken & Veggies ', 'Meal_IMG_1635241111.webp', 'The only thing better than one super easy dinner is being able to make *four* of them at once. This handy meal prep version means you can stash a few away for future Hungry You. ', '800'),
('ea2729d5ce03f9e346217d3507cda082bb71be933d', '6bb1cbb385fddfef7ac1d9a6d71025c76bfcc78e39', 'Light Breakfast', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '250'),
('fc8920fbb857a4a8f3d20d4d622c09ccffbf4ebbae', '88cb942ef57d35c2fb892d751aeb7919cf92948eb3', 'Buffet Breakfast', 'Meal_IMG_1635150575.webp', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '500'),
('ff250fd936783962b7be1f7f4bc87e83d5b201c369', 'bf13b0986358ea8e0836a1a588e4ffe73b09f4c927', 'Vegan Chickpea Shakshuka ', 'Meal_IMG_1635241129.jpg', 'This vegan-friendly version swaps eggs for protein-packed chickpeas, and makes enough for four. ', '1000');

-- --------------------------------------------------------

--
-- Table structure for table `meal_cards`
--

CREATE TABLE `meal_cards` (
  `card_id` varchar(200) NOT NULL,
  `card_owner_id` varchar(200) NOT NULL,
  `card_code_number` varchar(200) DEFAULT NULL,
  `card_loaded_amount` varchar(200) DEFAULT NULL,
  `card_status` varchar(200) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meal_cards`
--

INSERT INTO `meal_cards` (`card_id`, `card_owner_id`, `card_code_number`, `card_loaded_amount`, `card_status`) VALUES
('79b13e83fb95bc4edb4c5e98c68eddd6082623f0f8', 'a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'FARZX91538', '8250', 'Active'),
('c7cfbfad63f73138897ec8c524e4b75eb11cc0bc67', '769f83760fe0f4ab8881f411dc0412406611ac7d52', 'OWAIZ60582', '10000', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `meal_categories`
--

CREATE TABLE `meal_categories` (
  `category_id` varchar(200) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_details` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meal_categories`
--

INSERT INTO `meal_categories` (`category_id`, `category_name`, `category_details`) VALUES
('560a7e961312eb52042fc5f72820d342d026be6970', 'Tea Break', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
('6bb1cbb385fddfef7ac1d9a6d71025c76bfcc78e39', 'Lunch', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. '),
('88cb942ef57d35c2fb892d751aeb7919cf92948eb3', 'Breakfast', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. '),
('bf13b0986358ea8e0836a1a588e4ffe73b09f4c927', 'Supper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
('ede2326d7602212b187dba745d31730121ee615088', 'Dinner', '  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(200) NOT NULL,
  `order_user_id` varchar(200) NOT NULL,
  `order_meal_id` varchar(200) NOT NULL,
  `order_quantity` varchar(200) DEFAULT NULL,
  `order_status` varchar(200) DEFAULT 'Pending',
  `order_payment_status` varchar(200) DEFAULT 'Pending',
  `order_date_posted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_user_id`, `order_meal_id`, `order_quantity`, `order_status`, `order_payment_status`, `order_date_posted`) VALUES
('1c58a5c4e6e6d703fe98d18468eacc3d5439ab457a', '769f83760fe0f4ab8881f411dc0412406611ac7d52', 'ea2729d5ce03f9e346217d3507cda082bb71be933d', '1', 'Pending', 'Paid', '2021-10-26 10:05:21'),
('282fec39003059b124f4a15be2a85004f18bdd846f', 'a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'ea2729d5ce03f9e346217d3507cda082bb71be933d', '1', 'Pending', 'Paid', '2021-10-25 12:38:23'),
('8171e37201e434631f67b52f18bdbdbee20f96780e', 'a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'add1f7e54c0b37fd68eee55b0d8da1a8ad2b024258', '1', 'Pending', 'Pending', '2021-10-26 13:05:33'),
('8e6053b82412a4978d421e007fdf8f9f17e42648a5', 'a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'ff250fd936783962b7be1f7f4bc87e83d5b201c369', '1', 'Pending', 'Paid', '2021-10-26 14:22:45'),
('9da26767221395636c018a7dbf7e4bffbed3976518', 'a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'fc8920fbb857a4a8f3d20d4d622c09ccffbf4ebbae', '1', 'Pending', 'Paid', '2021-10-26 10:03:27'),
('fc277ce7f99702257e2467bc7b4b16c13867896621', '769f83760fe0f4ab8881f411dc0412406611ac7d52', 'fc8920fbb857a4a8f3d20d4d622c09ccffbf4ebbae', '1', 'Pending', 'Paid', '2021-10-25 14:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` varchar(200) NOT NULL,
  `payment_order_id` varchar(200) NOT NULL,
  `payment_confirmation_code` varchar(200) NOT NULL,
  `payment_amount` varchar(200) NOT NULL,
  `payment_date_posted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_means` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_order_id`, `payment_confirmation_code`, `payment_amount`, `payment_date_posted`, `payment_means`) VALUES
('0075774a04c5a36efad242f1f0c4a4879e0c0b7239', 'fc277ce7f99702257e2467bc7b4b16c13867896621', 'I63572XGPY', '500', '2021-10-25 14:38:11', 'Mpesa'),
('45be52e5d7ef12d7fc382ed501943ad64d01bd1151', '282fec39003059b124f4a15be2a85004f18bdd846f', '7NJO6FZWDS', '250', '2021-10-25 12:38:22', 'Meal Card Swipe'),
('b5b368d7b672b4de925fe18fe49b7d8a621986174e', '9da26767221395636c018a7dbf7e4bffbed3976518', 'P6IVYMGD27', '500', '2021-10-26 10:03:27', 'Meal Card Swipe'),
('be7573739fb543c123054a23adea00794c476c9b31', '8e6053b82412a4978d421e007fdf8f9f17e42648a5', 'N6SIM4JWTG', '1000', '2021-10-26 14:22:45', 'Meal Card Swipe'),
('cf36020f1b83cbd361772787a29b4781a7fc7ac141', '1c58a5c4e6e6d703fe98d18468eacc3d5439ab457a', '6PY18XVQAF', '250', '2021-10-26 10:05:21', 'Mpesa');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `sys_id` int(200) NOT NULL,
  `sys_name` longtext DEFAULT NULL,
  `sys_tagline` longtext DEFAULT NULL,
  `sys_paybill_no` varchar(200) DEFAULT NULL,
  `sys_paypal_link` varchar(200) DEFAULT NULL,
  `sys_paypal_email` varchar(200) DEFAULT NULL,
  `sys_standard_amount_loaded` varchar(200) DEFAULT NULL,
  `sys_contacts` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`sys_id`, `sys_name`, `sys_tagline`, `sys_paybill_no`, `sys_paypal_link`, `sys_paypal_email`, `sys_standard_amount_loaded`, `sys_contacts`) VALUES
(1, 'KCA University Meal Card System', 'A Premier Business and Technology University of Choice', '300078', NULL, 'corporate@kca.ac.ke', '10000', '+254 719 034333 / +254 792 793059');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(200) NOT NULL,
  `user_number` varchar(200) DEFAULT NULL,
  `user_name` varchar(200) DEFAULT NULL,
  `user_email` varchar(200) DEFAULT NULL,
  `user_password` varchar(200) DEFAULT NULL,
  `user_phone_no` varchar(200) DEFAULT NULL,
  `user_access_level` varchar(200) DEFAULT NULL,
  `user_profile_pic` longtext DEFAULT NULL,
  `user_date_created` varchar(200) DEFAULT NULL,
  `user_has_card` varchar(200) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_number`, `user_name`, `user_email`, `user_password`, `user_phone_no`, `user_access_level`, `user_profile_pic`, `user_date_created`, `user_has_card`) VALUES
('2621f3342873b53c4eea37672aa12935b2a80dd7cd', 'STF-0001', 'System Administrator', 'martdevelopers254@gmail.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+2547372297099', 'admin', NULL, '12/12/1990', 'No'),
('769f83760fe0f4ab8881f411dc0412406611ac7d52', 'KCA/OKIAS/82614', 'Musyoka Margaret', 'kcaokias82614@kca.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+254122390869', 'student', NULL, '23 Oct 2021', 'Yes'),
('a7e37ba0bd7263687ef7bffa189f28b7942e2b5bf0', 'KCA/OGXJY/28905', 'Johnes Doe', 'jhdoe@mail.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+2547123678', 'student', NULL, '23 Oct 2021', 'Yes'),
('ab0c5f6cb97335ba7046ca49b830b169ac6885ef0f', 'EFPDO59637', 'James Doe', 'jamesdoe@gmail.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+254737229776', 'cashier', NULL, '23 Oct 2021', 'No'),
('ba199283cea017094ae90b59d7c63cea2f79079603', 'RVEKY07416', 'James Doe', 'jamesdoe@mail2.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+25408931312', 'cashier', NULL, '23 Oct 2021', 'No'),
('de307ea213cc1b6c2d736072a051bd3a14c5f7275b', 'RWZEY09427', 'Margaret Musyoka', 'maggiesyoks@gmail.com', 'a69681bcf334ae130217fea4505fd3c994f5683f', '+2547372297', 'cashier', NULL, '23 Oct 2021', 'No');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mailer_setttings`
--
ALTER TABLE `mailer_setttings`
  ADD PRIMARY KEY (`mailer_host`);

--
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`meal_id`),
  ADD KEY `Meal_Category` (`meal_category_id`);

--
-- Indexes for table `meal_cards`
--
ALTER TABLE `meal_cards`
  ADD PRIMARY KEY (`card_id`),
  ADD KEY `Card_Owner` (`card_owner_id`);

--
-- Indexes for table `meal_categories`
--
ALTER TABLE `meal_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `Order_Meal` (`order_meal_id`),
  ADD KEY `Order_Owner` (`order_user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `PAyment_Order_Id` (`payment_order_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`sys_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `sys_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meals`
--
ALTER TABLE `meals`
  ADD CONSTRAINT `Meal_Category` FOREIGN KEY (`meal_category_id`) REFERENCES `meal_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meal_cards`
--
ALTER TABLE `meal_cards`
  ADD CONSTRAINT `Card_Owner` FOREIGN KEY (`card_owner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `Order_Meal` FOREIGN KEY (`order_meal_id`) REFERENCES `meals` (`meal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `PAyment_Order_Id` FOREIGN KEY (`payment_order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
