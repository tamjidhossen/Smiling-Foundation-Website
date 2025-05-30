-- Create volunteers table
CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `nid` varchar(50) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `volunteer_type` varchar(50) NOT NULL,
  `special_skills` text DEFAULT NULL,
  `present_division` varchar(50) NOT NULL,
  `present_address` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nid` (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
