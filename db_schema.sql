
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `temporary_user_data` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `whatsapp_mobile` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `subscription_plan` int(11) NOT NULL,
  `referral_code` varchar(100) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `csrf` varchar(255) NOT NULL,
  `session` varchar(255) NOT NULL,
  `subscription_start_date` datetime DEFAULT current_timestamp(),
  `subscription_end_date` datetime DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `whatsapp_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `subscription_plan` varchar(50) DEFAULT NULL,
  `subscription_start_date` datetime DEFAULT current_timestamp(),
  `subscription_end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `temporary_user_data`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `temporary_user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

