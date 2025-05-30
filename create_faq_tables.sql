-- Create FAQ tables
CREATE TABLE `faq_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `faq_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data from existing JSON
INSERT INTO `faq_categories` (`name`, `display_order`) VALUES
('General Questions', 1),
('Volunteer Related', 2),
('Donations', 3);

INSERT INTO `faqs` (`category_id`, `question`, `answer`, `display_order`) VALUES
(1, 'What is Smiling Foundation?', 'Smiling Foundation is a non-profit organization dedicated to creating positive change through sustainable community development and empowering underprivileged communities in Bangladesh.', 1),
(1, 'How can I get involved?', 'You can get involved by volunteering, donating, or participating in our events. Visit our volunteer page to register as a volunteer or our donation page to make a contribution.', 2),
(2, 'What are the requirements to become a volunteer?', 'To become a volunteer, you must be at least 18 years old, have a valid NID, and be willing to commit time to our causes. Some roles may require specific skills or experience.', 1),
(2, 'How much time do I need to commit?', 'Time commitment varies based on the type of volunteering you choose. Regular volunteers typically commit 4-8 hours per week, while event-based volunteers participate as per event schedules.', 2),
(3, 'Is my donation tax-deductible?', 'Yes, all donations to Smiling Foundation are tax-deductible. You will receive a receipt for your donation that you can use for tax purposes.', 1),
(3, 'How is my donation used?', 'Your donations directly support our community programs, including education initiatives, healthcare projects, and emergency relief efforts. We maintain full transparency in fund allocation.', 2);
