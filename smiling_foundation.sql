-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 07:26 AM
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
-- Database: `smiling_foundation`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_content`
--

CREATE TABLE `about_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `section` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_content`
--

INSERT INTO `about_content` (`id`, `title`, `content`, `section`, `created_at`, `is_deleted`) VALUES
(1, 'Our Vision', 'To create a world where every individual has access to basic necessities and opportunities for growth', 'vision', '2025-02-14 01:27:42', 0),
(2, 'Our Values', '[{\"title\":\"Integrity\",\"description\":\"We maintain highest ethical standards in all our actions\"},{\"title\":\"Compassion\",\"description\":\"We serve with empathy and understanding\"},{\"title\":\"Sustainability\",\"description\":\"We create lasting positive impact\"}]', 'values', '2025-02-14 01:27:42', 0),
(3, 'Our Vision', 'To create a world where every individual has access to basic necessities and opportunities for growth lol', 'vision', '2025-02-14 01:46:46', 0),
(4, 'Our Vision', 'To create a world where every individual has access to basic necessities and opportunities for growth', 'vision', '2025-02-14 01:46:59', 0),
(5, 'Our Mission', 'To create positive change through sustainable community development and support.', 'mission', '2025-02-14 02:10:31', 0),
(6, 'Our Mission', 'To create positive change through sustainable community development and support. wow', 'mission', '2025-02-14 02:14:31', 0),
(7, 'Our Mission', 'To create positive change through sustainable community development and support', 'mission', '2025-02-14 02:14:41', 0),
(8, 'Our Values', '[{\"title\":\"Integrity\",\"description\":\"We maintain highest ethical standards in all our actions\"},{\"title\":\"Compassion\",\"description\":\"We serve with empathy and understanding\"},{\"title\":\"Sustainability\",\"description\":\"We create lasting positive impact\"}]', 'values', '2025-02-14 02:21:29', 0),
(9, 'Our Values', '[{\"title\":\"Integrity\",\"description\":\"We maintain highest ethical standards in all our actions\"},{\"title\":\"Compassion\",\"description\":\"We serve with empathy and understanding\"},{\"title\":\"Sustainability\",\"description\":\"We create lasting positive impact\"}]', 'values', '2025-02-14 02:22:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$RuhV2y6rGLUXGpHkl4ibDeG.JUGQZW2QitSR0qW.ECFQV8LHh.rOa', 'admin@example.com', '2025-02-14 01:02:07');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `blog_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `impact_stats`
--

CREATE TABLE `impact_stats` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mission_content`
--

CREATE TABLE `mission_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `type` enum('mission','vision','approach') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('ongoing','completed') DEFAULT 'ongoing',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `image`, `status`, `created_at`, `is_deleted`) VALUES
(4, 'Healthcare Access', 'At Smiling Foundation, we are dedicated to improving the health and well-being of underserved populations in Bangladesh. Our \"Healthcare Access\" project was a pivotal initiative aimed at bridging the gap between marginalized communities and essential healthcare services.\r\n\r\nProject Overview\r\n\r\nLaunched in 2020, the Healthcare Access project focused on providing comprehensive medical services to remote and underserved areas. Recognizing the challenges faced by these communities, including limited access to quality healthcare, we implemented a multifaceted approach to address their needs.\r\n\r\nKey Objectives\r\n\r\nExpand Healthcare Reach: Establish mobile clinics and health camps to deliver medical services directly to remote communities.\r\n\r\nEnhance Medical Infrastructure: Collaborate with local health facilities to improve infrastructure and ensure the availability of essential medical equipment.\r\n\r\nCommunity Health Education: Conduct awareness programs focusing on preventive healthcare, hygiene, and nutrition to empower individuals with knowledge for better health management.\r\n\r\nCapacity Building: Train local healthcare workers to ensure sustainability and continuity of care within the community.\r\n\r\nImplementation Strategy\r\n\r\nMobile Clinics: We deployed mobile medical units staffed with qualified healthcare professionals to provide on-site consultations, treatments, and referrals.\r\n\r\nPartnerships: Collaborated with local non-governmental organizations (NGOs) and government health departments to leverage resources and expertise.\r\n\r\nHealth Camps: Organized regular health camps offering services such as immunizations, maternal and child health check-ups, and screenings for common diseases.\r\n\r\nEducational Workshops: Held workshops to educate community members on topics like sanitation, disease prevention, and healthy lifestyle practices.\r\n\r\nAchievements\r\n\r\nBy the conclusion of the Healthcare Access project in 2023, we achieved significant milestones:\r\n\r\nIncreased Healthcare Coverage: Provided medical services to over 15,000 individuals across 25 remote villages.\r\n\r\nImproved Health Outcomes: Notable reductions in the prevalence of common illnesses and improved maternal and child health indicators in the target areas.\r\n\r\nEmpowered Communities: Enhanced health literacy among community members, leading to better health-seeking behaviors.\r\n\r\nSustainable Impact: Trained 50 local healthcare workers, ensuring ongoing medical support within the communities.\r\n\r\nConclusion\r\n\r\nThe Healthcare Access project exemplifies our dedication to creating sustainable health solutions for underserved populations. Through strategic interventions and community engagement, we have made a lasting impact on the health and well-being of numerous individuals in Bangladesh. We remain committed to continuing our efforts to ensure equitable healthcare for all.', '67aed34b6e1fb.jpg', 'completed', '2025-02-14 05:23:23', 0),
(5, 'Education For All', 'At the Smiling Foundation, we are committed to creating a brighter future for all children in Bangladesh. Our \"Education For All\" project was a testament to this commitment, aiming to provide quality education to underprivileged and marginalized children across the country.\r\n\r\nProject Overview\r\n\r\nLaunched in [Year], the \"Education For All\" initiative sought to bridge the educational gap for children who lacked access to formal schooling due to socio-economic challenges. We focused on:\r\n\r\nInclusive Education: Ensuring that children from diverse backgrounds, including those with disabilities, had equal access to learning opportunities.\r\nCommunity Engagement: Collaborating with local communities to raise awareness about the importance of education and to encourage enrollment.\r\nCapacity Building: Training teachers to handle diverse classrooms and to implement inclusive teaching methodologies.\r\nKey Achievements\r\n\r\nThroughout the project\'s duration, we accomplished several milestones:\r\n\r\nEnrollment Drive: Successfully enrolled [Number] children into primary education programs in [Regions/Districts].\r\nTeacher Training: Conducted workshops for [Number] teachers, enhancing their skills in inclusive education practices.\r\nCommunity Workshops: Organized [Number] community sessions to sensitize parents and guardians about the value of education.\r\nChallenges and Solutions\r\n\r\nWhile implementing the project, we encountered challenges such as:\r\n\r\nCultural Barriers: In some communities, traditional beliefs hindered children\'s education, especially for girls and children with disabilities. We addressed this by engaging local leaders and conducting awareness campaigns to shift perceptions.\r\nResource Limitations: Limited access to educational materials was a significant hurdle. We partnered with organizations to source and distribute necessary supplies to the schools involved.\r\nImpact and Legacy\r\n\r\nThe \"Education For All\" project has left a lasting impact:\r\n\r\nImproved Literacy Rates: There was a noticeable increase in literacy rates among the participating communities.\r\nSustainable Practices: The training provided to teachers and the awareness raised in communities have fostered a culture that values education, ensuring the project\'s benefits continue beyond its completion.\r\nConclusion\r\n\r\nThe success of the \"Education For All\" project underscores the importance of inclusive and accessible education. At the Smiling Foundation, we are proud of the strides we\'ve made and remain dedicated to empowering every child through learning opportunities.', '67aed2dce61fc.jpg', 'completed', '2025-02-14 05:21:32', 0),
(6, 'Clean Water Initiative', 'At the Smiling Foundation, we are dedicated to improving the lives of communities across Bangladesh. One of our key ongoing projects is the Clean Water Initiative, which aims to provide sustainable access to safe and clean drinking water for underserved populations.\r\n\r\nProject Overview\r\n\r\nAccess to clean water is a fundamental human right and essential for maintaining health and well-being. In many parts of Bangladesh, families struggle with contaminated water sources, leading to serious health issues, particularly among children. Our Clean Water Initiative focuses on addressing this critical need by implementing comprehensive solutions that ensure long-term water security for vulnerable communities.\r\n\r\nObjectives\r\n\r\nImprove Health Outcomes: By providing access to clean water, we aim to reduce the prevalence of waterborne diseases, thereby improving overall community health.\r\n\r\nEmpower Communities: We engage local communities in the planning and implementation of water projects to foster ownership and ensure sustainability.\r\n\r\nPromote Hygiene Practices: Alongside providing clean water, we conduct educational programs to raise awareness about proper hygiene and sanitation practices.\r\n\r\nKey Activities\r\n\r\nCommunity Engagement: We work closely with community members to assess their specific needs and involve them in decision-making processes.\r\n\r\nInfrastructure Development: Our team constructs wells, installs water purification systems, and develops distribution networks to ensure reliable access to clean water.\r\n\r\nEducation and Training: We organize workshops and distribute materials to educate communities on the importance of clean water and hygiene practices.\r\n\r\nImpact\r\n\r\nSince the inception of the Clean Water Initiative, we have witnessed significant positive changes in the communities we serve:\r\n\r\nHealth Improvements: There has been a notable decrease in waterborne illnesses, leading to healthier families and reduced healthcare costs.\r\n\r\nEconomic Benefits: With better health, community members can engage more effectively in economic activities, contributing to improved livelihoods.\r\n\r\nSustainable Development: The initiative has empowered communities to manage and maintain their water resources, ensuring long-term sustainability.\r\n\r\nLooking Ahead\r\n\r\nWe are committed to expanding the Clean Water Initiative to reach more communities in need. Future plans include exploring innovative water purification technologies, strengthening partnerships with local organizations, and enhancing our educational programs to promote lasting behavioral change.\r\n\r\nGet Involved\r\n\r\nYour support is crucial to the success of the Clean Water Initiative. Whether through donations, volunteering, or spreading the word, you can make a significant impact. Together, we can ensure that every person in Bangladesh has access to the clean water they deserve.\r\n\r\nFor more information on how to get involved, please contact us directly.', '67aed243318ee.jpg', 'ongoing', '2025-02-14 05:18:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `type` enum('board','core') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_content`
--
ALTER TABLE `about_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`blog_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `impact_stats`
--
ALTER TABLE `impact_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mission_content`
--
ALTER TABLE `mission_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `impact_stats`
--
ALTER TABLE `impact_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mission_content`
--
ALTER TABLE `mission_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD CONSTRAINT `blog_tags_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`),
  ADD CONSTRAINT `blog_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
