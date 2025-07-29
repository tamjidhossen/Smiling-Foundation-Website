-- ===============================================
-- DATABASE MIGRATION SCRIPT
-- From Old Version to New Version (July 29, 2025)
-- This script will update the database structure while preserving existing data
-- ===============================================

-- Start transaction to ensure data integrity
START TRANSACTION;

-- ===============================================
-- 1. CREATE NEW TABLES
-- ===============================================

-- Create contact_messages table (completely new)
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create news table (completely new)
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `external_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================================
-- 2. MODIFY EXISTING TABLES
-- ===============================================

-- Modify gallery table to add new columns for multimedia support
ALTER TABLE `gallery` 
ADD COLUMN IF NOT EXISTS `type` varchar(10) NOT NULL DEFAULT 'image' COMMENT 'Type of media: image or video' AFTER `is_deleted`,
ADD COLUMN IF NOT EXISTS `video_url` varchar(255) DEFAULT NULL COMMENT 'URL for the youtube video' AFTER `type`;

-- Update existing gallery records to have 'image' type (since they were all images before)
UPDATE `gallery` SET `type` = 'image' WHERE `type` IS NULL OR `type` = '';

-- ===============================================
-- 3. ADD MISSING INDEXES FOR PERFORMANCE
-- ===============================================

-- Add indexes to contact_messages table (already included in CREATE TABLE above)
-- Add any other missing indexes if needed

-- ===============================================
-- 4. UPDATE AUTO_INCREMENT VALUES (Optional - will auto-adjust based on existing data)
-- ===============================================

-- The AUTO_INCREMENT values will automatically adjust based on existing data
-- No manual adjustment needed as MySQL will use MAX(id) + 1

-- ===============================================
-- 5. VERIFY DATA INTEGRITY
-- ===============================================

-- Check if all tables exist
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME,
    UPDATE_TIME
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN (
    'about_content', 'admins', 'blogs', 'categories', 'contact_messages',
    'donations', 'donation_purposes', 'faqs', 'faq_categories', 
    'gallery', 'impact_stats', 'mission_content', 'news', 'projects',
    'team_members', 'volunteers'
);

-- ===============================================
-- 6. OPTIONAL: INSERT SAMPLE DATA FOR NEW TABLES (if needed)
-- ===============================================

-- You can uncomment and modify these if you want to add the sample data from the new version
-- But typically you'd want to keep your existing data only

/*
-- Sample contact messages (uncomment if needed)
INSERT INTO `contact_messages` (`name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
('Sample User', 'sample@example.com', '01234567890', 'Test Subject', 'This is a test message.', NOW());

-- Sample news entries (uncomment if needed)
INSERT INTO `news` (`title`, `thumbnail`, `external_url`, `created_at`) VALUES
('Sample News Title', 'sample-thumbnail.jpg', 'https://example.com/news', NOW());
*/

-- ===============================================
-- 7. FINAL VERIFICATION QUERIES
-- ===============================================

-- Show table structures to verify changes
SHOW CREATE TABLE `gallery`;
SHOW CREATE TABLE `contact_messages`;
SHOW CREATE TABLE `news`;

-- Count records in all tables
SELECT 'about_content' as table_name, COUNT(*) as record_count FROM about_content
UNION ALL
SELECT 'admins', COUNT(*) FROM admins
UNION ALL
SELECT 'blogs', COUNT(*) FROM blogs
UNION ALL
SELECT 'categories', COUNT(*) FROM categories
UNION ALL
SELECT 'contact_messages', COUNT(*) FROM contact_messages
UNION ALL
SELECT 'donations', COUNT(*) FROM donations
UNION ALL
SELECT 'donation_purposes', COUNT(*) FROM donation_purposes
UNION ALL
SELECT 'faqs', COUNT(*) FROM faqs
UNION ALL
SELECT 'faq_categories', COUNT(*) FROM faq_categories
UNION ALL
SELECT 'gallery', COUNT(*) FROM gallery
UNION ALL
SELECT 'impact_stats', COUNT(*) FROM impact_stats
UNION ALL
SELECT 'mission_content', COUNT(*) FROM mission_content
UNION ALL
SELECT 'news', COUNT(*) FROM news
UNION ALL
SELECT 'projects', COUNT(*) FROM projects
UNION ALL
SELECT 'team_members', COUNT(*) FROM team_members
UNION ALL
SELECT 'volunteers', COUNT(*) FROM volunteers;

-- Commit the transaction
COMMIT;

-- ===============================================
-- MIGRATION COMPLETE
-- ===============================================

SELECT 'Database migration completed successfully!' as status;
SELECT 'Your existing data has been preserved.' as message;
SELECT 'New tables: contact_messages, news' as new_features;
SELECT 'Modified tables: gallery (added type and video_url columns)' as modifications;

-- ===============================================
-- POST-MIGRATION NOTES
-- ===============================================

/*
IMPORTANT NOTES AFTER RUNNING THIS MIGRATION:

1. BACKUP FIRST: Always backup your database before running this migration!

2. EXISTING DATA: All your existing data will be preserved:
   - All donations, volunteers, projects, blogs, etc. will remain intact
   - Gallery items will be marked as 'image' type by default

3. NEW FEATURES AVAILABLE:
   - Contact form submissions will be stored in contact_messages table
   - News section with external links in news table  
   - Gallery now supports both images and YouTube videos

4. CODE COMPATIBILITY:
   - Your new code will work with the updated database structure
   - Old functionality will continue to work unchanged
   - New features (contact form, news, video gallery) will be available

5. TESTING RECOMMENDED:
   - Test all existing functionality after migration
   - Test new contact form submission
   - Test gallery with both images and videos
   - Test news section if implemented

6. IF ISSUES OCCUR:
   - Restore from your backup
   - Check error logs
   - Verify all table structures match the new requirements

7. OPTIONAL ADDITIONS:
   - You can manually add sample data to new tables if needed
   - Uncomment the INSERT statements above for sample data
*/
