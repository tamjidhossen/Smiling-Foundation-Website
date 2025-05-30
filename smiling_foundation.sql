-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 02:05 PM
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
(9, 'Our Values', '[{\"title\":\"Integrity\",\"description\":\"We maintain highest ethical standards in all our actions\"},{\"title\":\"Compassion\",\"description\":\"We serve with empathy and understanding\"},{\"title\":\"Sustainability\",\"description\":\"We create lasting positive impact\"}]', 'values', '2025-02-14 02:22:06', 0),
(10, 'Our Values', '[{\"title\":\"Integrity\",\"description\":\"We maintain highest ethical standards in all our actions\"},{\"title\":\"Compassion\",\"description\":\"We serve with empathy and understanding\"},{\"title\":\"Sustainability\",\"description\":\"We create lasting positive impact\"}]', 'values', '2025-05-22 11:45:14', 0);

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
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `image`, `author`, `category_id`, `created_at`) VALUES
(1, 'Making a Difference: Our Latest Water Project', '<h1>Providing Clean Water: A Step Toward Better Health</h1><p>Water scarcity remains a pressing issue in many parts of Bangladesh. In January 2024, Smiling Foundation launched an initiative to provide clean drinking water to villages suffering from unsafe water sources.</p><p>We installed 20 tube wells across five remote villages, ensuring thousands of people now have access to safe drinking water. This project not only improves health but also reduces the burden on women and children who previously had to walk miles to fetch water.</p><p>Community involvement was crucial in making this project successful. Local volunteers and donors played a vital role in funding and executing the installations.</p><p>Our next goal is to expand this initiative to more districts in need. Join us in our mission to provide safe water for everyone.</p>', 'blog1.jpg', 'John Smith', 1, '2024-02-14 18:00:00'),
(2, 'Education Initiative Success Stories', '<h1>Breaking Barriers: Education for Every Child</h1><p>Education is a powerful tool for change, yet millions of children in Bangladesh lack access to quality learning opportunities.</p><p>Our \'Education for All\' program has successfully enrolled over 500 children in primary schools across rural areas. We provide school supplies, uniforms, and meals to ensure that financial constraints do not hinder their education.</p><p>Meet Rina, a 10-year-old girl from a small village in Rangpur. Before joining our program, she had never attended school. Today, she excels in her studies and dreams of becoming a doctor.</p><p>Through continued support, we aim to reach thousands more children. Your contribution can help shape a brighter future for them.</p>', 'blog2.jpg', 'Jane Doe', 3, '2024-01-31 18:00:00'),
(3, 'Emergency Relief: Helping Cyclone Victims', '<h1>Rebuilding Lives After the Storm</h1><p>In December 2023, Cyclone Amphan struck the coastal regions of Bangladesh, leaving thousands homeless. Smiling Foundation responded immediately with emergency relief efforts.</p><p>We distributed food, medicine, and clean water to over 2,000 affected families. Additionally, temporary shelters were built to provide safety and comfort to those who lost their homes.</p><p>Our volunteers worked tirelessly to support communities during this difficult time. We are now focusing on long-term rehabilitation, including rebuilding homes and providing livelihood support.</p><p>Your donations can help families recover and rebuild their lives.</p>', 'blog3.jpg', 'Rahman Ali', 2, '2024-02-04 18:00:00'),
(4, 'Empowering Women Through Skill Development Yayaya', '<h1>Creating Opportunities for Women</h1>\r\n<p data-start=\"137\" data-end=\"582\"><span class=\"_fadeIn_m1hgl_8\">Empowering </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">begins </span><span class=\"_fadeIn_m1hgl_8\">with </span><span class=\"_fadeIn_m1hgl_8\">enabling </span><span class=\"_fadeIn_m1hgl_8\">them </span><span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">stand </span><span class=\"_fadeIn_m1hgl_8\">on </span><span class=\"_fadeIn_m1hgl_8\">their </span><span class=\"_fadeIn_m1hgl_8\">own </span><span class=\"_fadeIn_m1hgl_8\">feet&mdash;</span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">at </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">heart </span><span class=\"_fadeIn_m1hgl_8\">of </span><span class=\"_fadeIn_m1hgl_8\">this </span><span class=\"_fadeIn_m1hgl_8\">empowerment </span><span class=\"_fadeIn_m1hgl_8\">lies </span><span class=\"_fadeIn_m1hgl_8\">financial </span><span class=\"_fadeIn_m1hgl_8\">independence. </span><span class=\"_fadeIn_m1hgl_8\">Recognizing </span><span class=\"_fadeIn_m1hgl_8\">this, </span><span class=\"_fadeIn_m1hgl_8\">we </span><span class=\"_fadeIn_m1hgl_8\">initiated </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">comprehensive </span><strong data-start=\"320\" data-end=\"349\"><span class=\"_fadeIn_m1hgl_8\">Skill </span><span class=\"_fadeIn_m1hgl_8\">Development </span><span class=\"_fadeIn_m1hgl_8\">Program</span></strong> <span class=\"_fadeIn_m1hgl_8\">aimed </span><span class=\"_fadeIn_m1hgl_8\">at </span><span class=\"_fadeIn_m1hgl_8\">equipping </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">with </span><span class=\"_fadeIn_m1hgl_8\">practical, </span><span class=\"_fadeIn_m1hgl_8\">income-</span><span class=\"_fadeIn_m1hgl_8\">generating </span><span class=\"_fadeIn_m1hgl_8\">skills. </span><span class=\"_fadeIn_m1hgl_8\">This </span><span class=\"_fadeIn_m1hgl_8\">program </span><span class=\"_fadeIn_m1hgl_8\">provides </span><span class=\"_fadeIn_m1hgl_8\">training </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">areas </span><span class=\"_fadeIn_m1hgl_8\">such </span><span class=\"_fadeIn_m1hgl_8\">as </span><strong data-start=\"465\" data-end=\"510\"><span class=\"_fadeIn_m1hgl_8\">sewing, </span><span class=\"_fadeIn_m1hgl_8\">handicrafts, </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">digital </span><span class=\"_fadeIn_m1hgl_8\">literacy</span></strong><span class=\"_fadeIn_m1hgl_8\">, </span><span class=\"_fadeIn_m1hgl_8\">opening </span><span class=\"_fadeIn_m1hgl_8\">doors </span><span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">both </span><span class=\"_fadeIn_m1hgl_8\">traditional </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">modern </span><span class=\"_fadeIn_m1hgl_8\">employment </span><span class=\"_fadeIn_m1hgl_8\">opportunities.</span></p>\r\n<p data-start=\"584\" data-end=\"990\"><span class=\"_fadeIn_m1hgl_8\">Since </span><span class=\"_fadeIn_m1hgl_8\">its </span><span class=\"_fadeIn_m1hgl_8\">inception, </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">program </span><span class=\"_fadeIn_m1hgl_8\">has </span><span class=\"_fadeIn_m1hgl_8\">had </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">transformative </span><span class=\"_fadeIn_m1hgl_8\">impact. </span><strong data-start=\"650\" data-end=\"668\"><span class=\"_fadeIn_m1hgl_8\">Over </span><span class=\"_fadeIn_m1hgl_8\">300 </span><span class=\"_fadeIn_m1hgl_8\">women</span></strong> <span class=\"_fadeIn_m1hgl_8\">from </span><span class=\"_fadeIn_m1hgl_8\">diverse </span><span class=\"_fadeIn_m1hgl_8\">backgrounds </span><span class=\"_fadeIn_m1hgl_8\">have </span><span class=\"_fadeIn_m1hgl_8\">successfully </span><span class=\"_fadeIn_m1hgl_8\">completed </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">training. </span><span class=\"_fadeIn_m1hgl_8\">Many </span><span class=\"_fadeIn_m1hgl_8\">of </span><span class=\"_fadeIn_m1hgl_8\">them </span><span class=\"_fadeIn_m1hgl_8\">have </span><span class=\"_fadeIn_m1hgl_8\">gone </span><span class=\"_fadeIn_m1hgl_8\">on </span><span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">build </span><span class=\"_fadeIn_m1hgl_8\">small </span><span class=\"_fadeIn_m1hgl_8\">businesses, </span><span class=\"_fadeIn_m1hgl_8\">while </span><span class=\"_fadeIn_m1hgl_8\">others </span><span class=\"_fadeIn_m1hgl_8\">have </span><span class=\"_fadeIn_m1hgl_8\">found </span><span class=\"_fadeIn_m1hgl_8\">meaningful </span><span class=\"_fadeIn_m1hgl_8\">employment </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">local </span><span class=\"_fadeIn_m1hgl_8\">enterprises. </span><span class=\"_fadeIn_m1hgl_8\">These </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">are </span><span class=\"_fadeIn_m1hgl_8\">no </span><span class=\"_fadeIn_m1hgl_8\">longer </span><span class=\"_fadeIn_m1hgl_8\">just </span><span class=\"_fadeIn_m1hgl_8\">earning; </span><span class=\"_fadeIn_m1hgl_8\">they </span><span class=\"_fadeIn_m1hgl_8\">are </span><span class=\"_fadeIn_m1hgl_8\">thriving, </span><span class=\"_fadeIn_m1hgl_8\">contributing </span><span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">their </span><span class=\"_fadeIn_m1hgl_8\">families, </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">inspiring </span><span class=\"_fadeIn_m1hgl_8\">others </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">their </span><span class=\"_fadeIn_m1hgl_8\">communities.</span></p>\r\n<p data-start=\"992\" data-end=\"1518\"><span class=\"_fadeIn_m1hgl_8\">One </span><span class=\"_fadeIn_m1hgl_8\">inspiring </span><span class=\"_fadeIn_m1hgl_8\">story </span><span class=\"_fadeIn_m1hgl_8\">is </span><span class=\"_fadeIn_m1hgl_8\">that </span><span class=\"_fadeIn_m1hgl_8\">of </span><strong data-start=\"1023\" data-end=\"1033\">Ayesha</strong><span class=\"_fadeIn_m1hgl_8\">, </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">resilient </span><span class=\"_fadeIn_m1hgl_8\">mother </span><span class=\"_fadeIn_m1hgl_8\">of </span><span class=\"_fadeIn_m1hgl_8\">two. </span><span class=\"_fadeIn_m1hgl_8\">Like </span><span class=\"_fadeIn_m1hgl_8\">many </span><span class=\"_fadeIn_m1hgl_8\">others, </span><span class=\"_fadeIn_m1hgl_8\">she </span><span class=\"_fadeIn_m1hgl_8\">once </span><span class=\"_fadeIn_m1hgl_8\">faced </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">daily </span><span class=\"_fadeIn_m1hgl_8\">challenge </span><span class=\"_fadeIn_m1hgl_8\">of </span><span class=\"_fadeIn_m1hgl_8\">making </span><span class=\"_fadeIn_m1hgl_8\">ends </span><span class=\"_fadeIn_m1hgl_8\">meet. </span><span class=\"_fadeIn_m1hgl_8\">With </span><span class=\"_fadeIn_m1hgl_8\">limited </span><span class=\"_fadeIn_m1hgl_8\">education </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">no </span><span class=\"_fadeIn_m1hgl_8\">stable </span><span class=\"_fadeIn_m1hgl_8\">income, </span><span class=\"_fadeIn_m1hgl_8\">her </span><span class=\"_fadeIn_m1hgl_8\">options </span><span class=\"_fadeIn_m1hgl_8\">were </span><span class=\"_fadeIn_m1hgl_8\">few. </span><span class=\"_fadeIn_m1hgl_8\">However, </span><span class=\"_fadeIn_m1hgl_8\">after </span><span class=\"_fadeIn_m1hgl_8\">enrolling </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">our </span><span class=\"_fadeIn_m1hgl_8\">sewing </span><span class=\"_fadeIn_m1hgl_8\">course, </span><span class=\"_fadeIn_m1hgl_8\">Ayesha </span><span class=\"_fadeIn_m1hgl_8\">discovered </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">talent </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">passion </span><span class=\"_fadeIn_m1hgl_8\">she </span><span class=\"_fadeIn_m1hgl_8\">never </span><span class=\"_fadeIn_m1hgl_8\">knew </span><span class=\"_fadeIn_m1hgl_8\">she </span><span class=\"_fadeIn_m1hgl_8\">had. </span><span class=\"_fadeIn_m1hgl_8\">With </span><span class=\"_fadeIn_m1hgl_8\">guidance </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">support, </span><span class=\"_fadeIn_m1hgl_8\">she </span><span class=\"_fadeIn_m1hgl_8\">launched </span><span class=\"_fadeIn_m1hgl_8\">her </span><span class=\"_fadeIn_m1hgl_8\">own </span><strong data-start=\"1361\" data-end=\"1394\"><span class=\"_fadeIn_m1hgl_8\">home-</span><span class=\"_fadeIn_m1hgl_8\">based </span><span class=\"_fadeIn_m1hgl_8\">tailoring </span><span class=\"_fadeIn_m1hgl_8\">business</span></strong><span class=\"_fadeIn_m1hgl_8\">. </span><span class=\"_fadeIn_m1hgl_8\">Today, </span><span class=\"_fadeIn_m1hgl_8\">Ayesha </span><span class=\"_fadeIn_m1hgl_8\">not </span><span class=\"_fadeIn_m1hgl_8\">only </span><span class=\"_fadeIn_m1hgl_8\">supports </span><span class=\"_fadeIn_m1hgl_8\">her </span><span class=\"_fadeIn_m1hgl_8\">family </span><span class=\"_fadeIn_m1hgl_8\">but </span><span class=\"_fadeIn_m1hgl_8\">also </span><span class=\"_fadeIn_m1hgl_8\">takes </span><span class=\"_fadeIn_m1hgl_8\">pride </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">being </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">role </span><span class=\"_fadeIn_m1hgl_8\">model </span><span class=\"_fadeIn_m1hgl_8\">for </span><span class=\"_fadeIn_m1hgl_8\">other </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">her </span><span class=\"_fadeIn_m1hgl_8\">neighborhood.</span></p>\r\n<p data-start=\"1520\" data-end=\"1773\"><span class=\"_fadeIn_m1hgl_8\">Encouraged </span><span class=\"_fadeIn_m1hgl_8\">by </span><span class=\"_fadeIn_m1hgl_8\">stories </span><span class=\"_fadeIn_m1hgl_8\">like </span><span class=\"_fadeIn_m1hgl_8\">Ayesha&rsquo;s, </span><span class=\"_fadeIn_m1hgl_8\">we </span><span class=\"_fadeIn_m1hgl_8\">are </span><span class=\"_fadeIn_m1hgl_8\">determined </span><span class=\"_fadeIn_m1hgl_8\">to </span><strong data-start=\"1578\" data-end=\"1604\"><span class=\"_fadeIn_m1hgl_8\">expand </span><span class=\"_fadeIn_m1hgl_8\">this </span><span class=\"_fadeIn_m1hgl_8\">initiative</span></strong> <span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">reach </span><span class=\"_fadeIn_m1hgl_8\">even </span><span class=\"_fadeIn_m1hgl_8\">more </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">need&mdash;</span><span class=\"_fadeIn_m1hgl_8\">especially </span><span class=\"_fadeIn_m1hgl_8\">those </span><span class=\"_fadeIn_m1hgl_8\">in </span><span class=\"_fadeIn_m1hgl_8\">underprivileged </span><span class=\"_fadeIn_m1hgl_8\">or </span><span class=\"_fadeIn_m1hgl_8\">rural </span><span class=\"_fadeIn_m1hgl_8\">areas. </span><span class=\"_fadeIn_m1hgl_8\">We </span><span class=\"_fadeIn_m1hgl_8\">believe </span><span class=\"_fadeIn_m1hgl_8\">that </span><span class=\"_fadeIn_m1hgl_8\">when </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">woman </span><span class=\"_fadeIn_m1hgl_8\">is </span><span class=\"_fadeIn_m1hgl_8\">empowered, </span><span class=\"_fadeIn_m1hgl_8\">entire </span><span class=\"_fadeIn_m1hgl_8\">families </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">communities </span><span class=\"_fadeIn_m1hgl_8\">benefit.</span></p>\r\n<p>&nbsp;</p>\r\n<p data-start=\"1775\" data-end=\"2060\"><strong data-start=\"1775\" data-end=\"1844\"><span class=\"_fadeIn_m1hgl_8\">Your </span><span class=\"_fadeIn_m1hgl_8\">support </span><span class=\"_fadeIn_m1hgl_8\">can </span><span class=\"_fadeIn_m1hgl_8\">help </span><span class=\"_fadeIn_m1hgl_8\">us </span><span class=\"_fadeIn_m1hgl_8\">scale </span><span class=\"_fadeIn_m1hgl_8\">this </span><span class=\"_fadeIn_m1hgl_8\">program </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">touch </span><span class=\"_fadeIn_m1hgl_8\">more </span><span class=\"_fadeIn_m1hgl_8\">lives.</span></strong> <span class=\"_fadeIn_m1hgl_8\">Whether </span><span class=\"_fadeIn_m1hgl_8\">it&rsquo;s </span><span class=\"_fadeIn_m1hgl_8\">through </span><span class=\"_fadeIn_m1hgl_8\">funding, </span><span class=\"_fadeIn_m1hgl_8\">resources, </span><span class=\"_fadeIn_m1hgl_8\">or </span><span class=\"_fadeIn_m1hgl_8\">spreading </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">word, </span><span class=\"_fadeIn_m1hgl_8\">every </span><span class=\"_fadeIn_m1hgl_8\">contribution </span><span class=\"_fadeIn_m1hgl_8\">counts. </span><span class=\"_fadeIn_m1hgl_8\">Together, </span><span class=\"_fadeIn_m1hgl_8\">we </span><span class=\"_fadeIn_m1hgl_8\">can </span><span class=\"_fadeIn_m1hgl_8\">create </span><span class=\"_fadeIn_m1hgl_8\">a </span><span class=\"_fadeIn_m1hgl_8\">future </span><span class=\"_fadeIn_m1hgl_8\">where </span><span class=\"_fadeIn_m1hgl_8\">more </span><span class=\"_fadeIn_m1hgl_8\">women </span><span class=\"_fadeIn_m1hgl_8\">have </span><span class=\"_fadeIn_m1hgl_8\">the </span><span class=\"_fadeIn_m1hgl_8\">skills, </span><span class=\"_fadeIn_m1hgl_8\">confidence, </span><span class=\"_fadeIn_m1hgl_8\">and </span><span class=\"_fadeIn_m1hgl_8\">opportunity </span><span class=\"_fadeIn_m1hgl_8\">to </span><span class=\"_fadeIn_m1hgl_8\">shape </span><span class=\"_fadeIn_m1hgl_8\">their </span><span class=\"_fadeIn_m1hgl_8\">own </span><span class=\"_fadeIn_m1hgl_8\">destinies.</span></p>', 'blog4.jpg', 'Fatima Begum', 1, '2024-01-24 18:00:00'),
(8, 'How to do business in a slum', 'How to Do Business in a Slum: Opportunities, Challenges, and Strategies\r\nWhen people think of business opportunities, slums rarely come to mind. Yet, these densely populated areas are full of untapped potential, vibrant communities, and eager entrepreneurs. With the right mindset and approach, doing business in slums can be both socially impactful and economically viable.\r\n\r\nWhy Consider Doing Business in a Slum?\r\nSlums are not just areas of poverty—they are hubs of human resilience, creativity, and ambition. Millions live in informal settlements with limited access to formal jobs or services. This creates a demand for affordable, practical solutions in areas like food, clothing, education, healthcare, and technology.\r\n\r\nBusinesses that enter slums with a social mission and sustainable model can not only generate profit but also create jobs, improve living standards, and empower marginalized populations.\r\n\r\n1. Understand the Community First\r\nBefore you start, spend time in the community. Understand the people, their culture, challenges, and daily routines. Talk to local leaders, women, youth, and informal workers. You’ll uncover insights that no market report can give you.\r\n\r\n💡 Tip: Partner with a local NGO or community-based organization (CBO) to build trust and navigate social dynamics.\r\n\r\n2. Identify Real Needs and Gaps\r\nSlum residents are often underserved by mainstream markets. Start by identifying the basic needs that are unmet:\r\n\r\nAffordable food or clean water\r\n\r\nLow-cost education or tutoring\r\n\r\nTailoring or clothing repairs\r\n\r\nMobile phone repair and digital services\r\n\r\nHygiene and sanitation products\r\n\r\nChildcare services\r\n\r\nMicro-financing or savings groups\r\n\r\nChoose a business model that solves a local problem in a simple, affordable way.\r\n\r\n3. Start Small, Stay Lean\r\nYou don’t need a lot of capital to begin. In fact, the best businesses in slums start with minimal investment and grow by word of mouth. Use locally available resources, hire local people, and keep your operations simple.\r\n\r\n✔️ Example: A small home-based tailoring shop, a roadside food stall, or a mobile repair kiosk can scale up over time.\r\n\r\n4. Employ and Empower Locals\r\nHire people from the community. They understand the culture, speak the language, and can help you gain credibility. Consider offering skill training for youth or women as part of your business model—it builds loyalty and long-term value.\r\n\r\n🧵 Story: A woman-led sewing cooperative in a Dhaka slum now makes school uniforms for local schools and provides income to over 25 women.\r\n\r\n5. Price Smartly, Focus on Volume\r\nResidents in slums are price-sensitive, so pricing must be accessible. Offer smaller quantities, bundle services, or provide pay-as-you-go options. The key is to keep margins low but sell consistently and at volume.\r\n\r\n6. Build Trust and Word-of-Mouth Marketing\r\nForget expensive ads. In slum communities, reputation is everything. Deliver quality, be honest, and respect your customers. Satisfied customers will become your best marketers.\r\n\r\n7. Overcome Infrastructure Challenges\r\nYes, there will be challenges:\r\n\r\nPoor roads or drainage\r\n\r\nLimited electricity or internet\r\n\r\nLack of legal permits\r\n\r\nBut these are also opportunities to innovate. Use solar panels, mobile-based services, offline apps, or community spaces to overcome such barriers.\r\n\r\n8. Think Long-Term: Social Enterprise Approach\r\nA business in a slum can grow beyond survival. Think of it as a social enterprise—a business that does good while making money. Look for grants, impact investors, or collaborations that can help you scale while staying true to your mission.\r\n\r\nExamples of Successful Slum-Based Businesses\r\nSELCO India: Brings solar-powered energy solutions to slums.\r\n\r\nSanergy (Kenya): Builds low-cost sanitation and turns waste into bio-fuel.\r\n\r\nRags2Riches (Philippines): Empowers women in slums to produce fashion accessories from scrap materials.\r\n\r\nConclusion: Where Others See Risk, See Opportunity\r\nDoing business in a slum is not about charity—it’s about inclusive capitalism. It requires empathy, patience, and innovation. But the rewards go beyond profit. You’ll be part of a movement that uplifts communities, builds livelihoods, and creates lasting change.\r\n\r\nReady to Start?\r\nStart small. Listen deeply. Solve real problems. Empower people. That’s how sustainable business is done—even in the most unlikely places.\r\n\r\n', '68306b1c99303.jpg', 'Tamjid Hossen', 2, '2025-05-23 12:33:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Projects', '2025-05-22 12:31:38'),
(2, 'Stories', '2025-05-22 12:31:38'),
(3, 'Updates', '2025-05-22 12:31:38');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `amount_usd` decimal(10,2) NOT NULL,
  `amount_bdt` decimal(10,2) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `message` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `receipt_generated` tinyint(1) DEFAULT 0,
  `donated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `email`, `phone`, `amount_usd`, `amount_bdt`, `purpose`, `is_anonymous`, `message`, `transaction_id`, `payment_status`, `payment_method`, `receipt_generated`, `donated_at`, `created_at`, `updated_at`) VALUES
(1, 'Test Donor', 'test@example.com', '01234567890', 41.84, 5000.00, 'Education', 0, 'Test donation message', 'TXN_1748584022_8602', 'completed', NULL, 1, '2025-05-30 05:47:02', '2025-05-30 05:47:02', '2025-05-30 05:47:02'),
(2, 'Test User', 'test@example.com', '01234567890', 16.74, 2000.00, 'Education', 0, 'Test donation', 'TXN_1748584321_8545', 'completed', NULL, 1, '2025-05-30 05:52:01', '2025-05-30 05:52:01', '2025-05-30 05:52:01'),
(3, 'Tamjid Hossen', 'tamjidhossen420@gmail.com', '01759023201', 8.36, 999.00, 'Medicine', 0, '', 'TXN_1748584818_2727', 'completed', NULL, 1, '2025-05-30 06:00:18', '2025-05-30 06:00:18', '2025-05-30 06:00:18'),
(4, 'Tamjid Hossen', 'tamjidhossen420@gmail.com', '01759023201', 0.07, 8.00, 'Clean Water', 1, 'hwllo wofjaso', 'TXN_1748585366_2909', 'completed', NULL, 1, '2025-05-30 06:09:26', '2025-05-30 06:09:26', '2025-05-30 06:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `donation_purposes`
--

CREATE TABLE `donation_purposes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_purposes`
--

INSERT INTO `donation_purposes` (`id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, 'Education', 'Support educational programs and scholarships', 1, '2025-05-30 05:05:11'),
(2, 'Medicine', 'Provide medical aid and healthcare services', 1, '2025-05-30 05:05:11'),
(3, 'Flood Relief', 'Emergency assistance for flood-affected communities', 1, '2025-05-30 05:05:11'),
(4, 'Winter Cloth', 'Distribute warm clothing during winter season', 1, '2025-05-30 05:05:11'),
(5, 'Clean Water', 'Water purification and sanitation projects', 1, '2025-05-30 05:05:11'),
(6, 'Food Security', 'Provide food assistance to communities in need', 1, '2025-05-30 05:05:11'),
(7, 'General', 'General donation for organizational activities', 1, '2025-05-30 05:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category_id`, `question`, `answer`, `display_order`, `created_at`, `is_deleted`) VALUES
(1, 1, 'What is Smiling Foundation?', 'Smiling Foundation is a non-profit organization dedicated to creating positive change through sustainable community development and empowering underprivileged communities in Bangladesh.', 1, '2025-05-30 06:36:31', 0),
(2, 1, 'How can I get involved?', 'You can get involved by volunteering, donating, or participating in our events. Visit our volunteer page to register as a volunteer or our donation page to make a contribution.', 2, '2025-05-30 06:36:31', 0),
(3, 2, 'What are the requirements to become a volunteer?', 'To become a volunteer, you must be at least 18 years old, have a valid NID, and be willing to commit time to our causes. Some roles may require specific skills or experience.', 1, '2025-05-30 06:36:31', 0),
(4, 2, 'How much time do I need to commit?', 'Time commitment varies based on the type of volunteering you choose. Regular volunteers typically commit 4-8 hours per week, while event-based volunteers participate as per event schedules.', 2, '2025-05-30 06:36:31', 0),
(5, 3, 'Is my donation tax-deductible?', 'Yes, all donations to Smiling Foundation are tax-deductible. You will receive a receipt for your donation that you can use for tax purposes.', 1, '2025-05-30 06:36:31', 0),
(6, 3, 'How is my donation used?', 'Your donations directly support our community programs, including education initiatives, healthcare projects, and emergency relief efforts. We maintain full transparency in fund allocation.', 2, '2025-05-30 06:36:31', 0),
(7, 2, 'Can we become online volunteers?', 'Yes, you absolutely can.', 0, '2025-05-30 06:52:11', 0),
(8, 3, 'Can I donate in Dollars?', 'No', 0, '2025-05-30 11:17:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `name`, `display_order`, `created_at`, `is_deleted`) VALUES
(1, 'General Questions', 1, '2025-05-30 06:36:31', 0),
(2, 'Volunteer Related', 2, '2025-05-30 06:36:31', 0),
(3, 'Donations', 3, '2025-05-30 06:36:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image`, `category`, `created_at`, `is_deleted`) VALUES
(1, 'Annual Charity Gala 2024', 'Highlights from our annual fundraising gala', 'gala-2024-thumb.jpg', 'events', '2025-05-30 06:36:23', 0),
(2, 'Clean Water Project', 'Installing water pumps in rural communities', 'water-project-thumb.jpg', 'projects', '2025-05-30 06:36:23', 0),
(3, 'Community Health Camp', 'Free health checkups for the community', 'health-camp-thumb.jpg', 'events', '2025-05-30 06:36:23', 0),
(4, 'Slum Business visits', 'We visited a slum', '6839554259f0f.jpg', 'general', '2025-05-30 06:50:42', 0),
(5, 'Hello world', 'This is hello world', '683993f74b94f.jpg', 'general', '2025-05-30 11:18:15', 0);

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
  `status` enum('ongoing','completed','paused','cancelled') DEFAULT 'ongoing',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `image`, `status`, `created_at`, `is_deleted`, `end_date`) VALUES
(4, 'Healthcare Access', 'At Smiling Foundation, we are dedicated to improving the health and well-being of underserved populations in Bangladesh. Our \"Healthcare Access\" project was a pivotal initiative aimed at bridging the gap between marginalized communities and essential healthcare services.\r\n\r\nProject Overview\r\n\r\nLaunched in 2020, the Healthcare Access project focused on providing comprehensive medical services to remote and underserved areas. Recognizing the challenges faced by these communities, including limited access to quality healthcare, we implemented a multifaceted approach to address their needs.\r\n\r\nKey Objectives\r\n\r\nExpand Healthcare Reach: Establish mobile clinics and health camps to deliver medical services directly to remote communities.\r\n\r\nEnhance Medical Infrastructure: Collaborate with local health facilities to improve infrastructure and ensure the availability of essential medical equipment.\r\n\r\nCommunity Health Education: Conduct awareness programs focusing on preventive healthcare, hygiene, and nutrition to empower individuals with knowledge for better health management.\r\n\r\nCapacity Building: Train local healthcare workers to ensure sustainability and continuity of care within the community.\r\n\r\nImplementation Strategy\r\n\r\nMobile Clinics: We deployed mobile medical units staffed with qualified healthcare professionals to provide on-site consultations, treatments, and referrals.\r\n\r\nPartnerships: Collaborated with local non-governmental organizations (NGOs) and government health departments to leverage resources and expertise.\r\n\r\nHealth Camps: Organized regular health camps offering services such as immunizations, maternal and child health check-ups, and screenings for common diseases.\r\n\r\nEducational Workshops: Held workshops to educate community members on topics like sanitation, disease prevention, and healthy lifestyle practices.\r\n\r\nAchievements\r\n\r\nBy the conclusion of the Healthcare Access project in 2023, we achieved significant milestones:\r\n\r\nIncreased Healthcare Coverage: Provided medical services to over 15,000 individuals across 25 remote villages.\r\n\r\nImproved Health Outcomes: Notable reductions in the prevalence of common illnesses and improved maternal and child health indicators in the target areas.\r\n\r\nEmpowered Communities: Enhanced health literacy among community members, leading to better health-seeking behaviors.\r\n\r\nSustainable Impact: Trained 50 local healthcare workers, ensuring ongoing medical support within the communities.\r\n\r\nConclusion\r\n\r\nThe Healthcare Access project exemplifies our dedication to creating sustainable health solutions for underserved populations. Through strategic interventions and community engagement, we have made a lasting impact on the health and well-being of numerous individuals in Bangladesh. We remain committed to continuing our efforts to ensure equitable healthcare for all.', '67aed34b6e1fb.jpg', 'completed', '2025-02-14 05:23:23', 0, NULL),
(5, 'Education For All', 'At the Smiling Foundation, we are committed to creating a brighter future for all children in Bangladesh. Our \"Education For All\" project was a testament to this commitment, aiming to provide quality education to underprivileged and marginalized children across the country.\r\n\r\nProject Overview\r\n\r\nLaunched in [Year], the \"Education For All\" initiative sought to bridge the educational gap for children who lacked access to formal schooling due to socio-economic challenges. We focused on:\r\n\r\nInclusive Education: Ensuring that children from diverse backgrounds, including those with disabilities, had equal access to learning opportunities.\r\nCommunity Engagement: Collaborating with local communities to raise awareness about the importance of education and to encourage enrollment.\r\nCapacity Building: Training teachers to handle diverse classrooms and to implement inclusive teaching methodologies.\r\nKey Achievements\r\n\r\nThroughout the project\'s duration, we accomplished several milestones:\r\n\r\nEnrollment Drive: Successfully enrolled [Number] children into primary education programs in [Regions/Districts].\r\nTeacher Training: Conducted workshops for [Number] teachers, enhancing their skills in inclusive education practices.\r\nCommunity Workshops: Organized [Number] community sessions to sensitize parents and guardians about the value of education.\r\nChallenges and Solutions\r\n\r\nWhile implementing the project, we encountered challenges such as:\r\n\r\nCultural Barriers: In some communities, traditional beliefs hindered children\'s education, especially for girls and children with disabilities. We addressed this by engaging local leaders and conducting awareness campaigns to shift perceptions.\r\nResource Limitations: Limited access to educational materials was a significant hurdle. We partnered with organizations to source and distribute necessary supplies to the schools involved.\r\nImpact and Legacy\r\n\r\nThe \"Education For All\" project has left a lasting impact:\r\n\r\nImproved Literacy Rates: There was a noticeable increase in literacy rates among the participating communities.\r\nSustainable Practices: The training provided to teachers and the awareness raised in communities have fostered a culture that values education, ensuring the project\'s benefits continue beyond its completion.\r\nConclusion\r\n\r\nThe success of the \"Education For All\" project underscores the importance of inclusive and accessible education. At the Smiling Foundation, we are proud of the strides we\'ve made and remain dedicated to empowering every child through learning opportunities.', '67aed2dce61fc.jpg', 'completed', '2025-02-14 05:21:32', 0, NULL),
(6, 'Clean Water Initiative', 'At the Smiling Foundation, we are dedicated to improving the lives of communities across Bangladesh. One of our key ongoing projects is the Clean Water Initiative, which aims to provide sustainable access to safe and clean drinking water for underserved populations.\r\n\r\nProject Overview\r\n\r\nAccess to clean water is a fundamental human right and essential for maintaining health and well-being. In many parts of Bangladesh, families struggle with contaminated water sources, leading to serious health issues, particularly among children. Our Clean Water Initiative focuses on addressing this critical need by implementing comprehensive solutions that ensure long-term water security for vulnerable communities.\r\n\r\nObjectives\r\n\r\nImprove Health Outcomes: By providing access to clean water, we aim to reduce the prevalence of waterborne diseases, thereby improving overall community health.\r\n\r\nEmpower Communities: We engage local communities in the planning and implementation of water projects to foster ownership and ensure sustainability.\r\n\r\nPromote Hygiene Practices: Alongside providing clean water, we conduct educational programs to raise awareness about proper hygiene and sanitation practices.\r\n\r\nKey Activities\r\n\r\nCommunity Engagement: We work closely with community members to assess their specific needs and involve them in decision-making processes.\r\n\r\nInfrastructure Development: Our team constructs wells, installs water purification systems, and develops distribution networks to ensure reliable access to clean water.\r\n\r\nEducation and Training: We organize workshops and distribute materials to educate communities on the importance of clean water and hygiene practices.\r\n\r\nImpact\r\n\r\nSince the inception of the Clean Water Initiative, we have witnessed significant positive changes in the communities we serve:\r\n\r\nHealth Improvements: There has been a notable decrease in waterborne illnesses, leading to healthier families and reduced healthcare costs.\r\n\r\nEconomic Benefits: With better health, community members can engage more effectively in economic activities, contributing to improved livelihoods.\r\n\r\nSustainable Development: The initiative has empowered communities to manage and maintain their water resources, ensuring long-term sustainability.\r\n\r\nLooking Ahead\r\n\r\nWe are committed to expanding the Clean Water Initiative to reach more communities in need. Future plans include exploring innovative water purification technologies, strengthening partnerships with local organizations, and enhancing our educational programs to promote lasting behavioral change.\r\n\r\nGet Involved\r\n\r\nYour support is crucial to the success of the Clean Water Initiative. Whether through donations, volunteering, or spreading the word, you can make a significant impact. Together, we can ensure that every person in Bangladesh has access to the clean water they deserve.\r\n\r\nFor more information on how to get involved, please contact us directly.', '67aed243318ee.jpg', 'ongoing', '2025-02-14 05:18:59', 0, NULL),
(12, 'All the good foods', 'This is just a food event', '68399e55576e8.jpg', 'completed', '2025-05-30 12:02:29', 0, '2025-05-30'),
(13, 'Another Food Project', 'Food is Goood', '68399e9e0310c.jpeg', 'ongoing', '2025-05-30 12:03:42', 0, '2025-06-07');

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
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `image`, `bio`, `linkedin`, `type`, `created_at`) VALUES
(1, 'Tamjid Hossen', 'President', '682ff08c73849.jpg', '', 'www.linkedin.com/tamjidhossen', 'core', '2025-05-23 03:50:36'),
(2, 'John Doe', 'Vice - President', '682ff3834cd75.jpg', 'I am the vp of this org.', '', 'core', '2025-05-23 04:03:15'),
(3, 'Alice Rephl', 'HR', '682ff978be515.jpg', '', '', 'board', '2025-05-23 04:28:40'),
(7, 'Rafael Mahmud', 'Hr Intern', '68306d7c5bf66.jpg', 'This is a cool guy', 'www.linkedin.com/raff', 'board', '2025-05-23 12:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
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
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`id`, `name`, `phone`, `email`, `facebook`, `nid`, `occupation`, `volunteer_type`, `special_skills`, `present_division`, `present_address`, `status`, `submitted_at`, `approved_at`, `approved_by`) VALUES
(1, 'Tamjid Hossen', '01759023201', 'tamjidhossen420@gmail.com', '', '1276121131134', 'student', 'regular', 'Graphics Design', 'rajshahi', 'Muslim Nagar, Joypurhat', 'approved', '2025-05-30 04:31:36', '2025-05-30 04:35:42', 1),
(2, 'Mushfik Ahmed', '01759023201', 'mfs@gmail.com', '', '9276121131134', 'employed', 'event', 'First Aid, Event Management', 'mymensingh', 'Muslim Nagar, Joypurhat', 'approved', '2025-05-30 04:38:17', '2025-05-30 04:38:39', 1),
(3, 'Tamjid Hossen', '01759023201', 'dakhsd@gmail.com', '', '3276121131134', 'employed', 'online', '', 'khulna', 'Muslim Nagar, Joypurhat', 'rejected', '2025-05-30 04:39:22', NULL, NULL);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_purpose` (`purpose`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_donated_at` (`donated_at`);

--
-- Indexes for table `donation_purposes`
--
ALTER TABLE `donation_purposes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_name` (`name`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_end_date` (`end_date`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nid` (`nid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_content`
--
ALTER TABLE `about_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donation_purposes`
--
ALTER TABLE `donation_purposes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
