-- Create gallery table
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data from existing JSON
INSERT INTO `gallery` (`title`, `description`, `image`, `category`) VALUES
('Annual Charity Gala 2024', 'Highlights from our annual fundraising gala', 'gala-2024-thumb.jpg', 'events'),
('Clean Water Project', 'Installing water pumps in rural communities', 'water-project-thumb.jpg', 'projects'),
('Community Health Camp', 'Free health checkups for the community', 'health-camp-thumb.jpg', 'events');
