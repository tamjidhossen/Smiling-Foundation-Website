-- Create donations table for the Smiling Foundation
CREATE TABLE donations (
    id int(11) NOT NULL AUTO_INCREMENT,
    donor_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    phone varchar(20) DEFAULT NULL,
    amount_usd decimal(10,2) NOT NULL,
    amount_bdt decimal(10,2) NOT NULL,
    purpose varchar(100) NOT NULL,
    is_anonymous tinyint(1) DEFAULT 0,
    message text DEFAULT NULL,
    transaction_id varchar(100) DEFAULT NULL,
    payment_status enum('pending','completed','failed') DEFAULT 'pending',
    payment_method varchar(50) DEFAULT NULL,
    receipt_generated tinyint(1) DEFAULT 0,
    donated_at timestamp DEFAULT current_timestamp(),
    created_at timestamp DEFAULT current_timestamp(),
    updated_at timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (id),
    INDEX idx_purpose (purpose),
    INDEX idx_payment_status (payment_status),
    INDEX idx_donated_at (donated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create donation purposes reference data
CREATE TABLE donation_purposes (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description text DEFAULT NULL,
    is_active tinyint(1) DEFAULT 1,
    created_at timestamp DEFAULT current_timestamp(),
    PRIMARY KEY (id),
    UNIQUE KEY uk_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default donation purposes
INSERT INTO donation_purposes (name, description) VALUES
('Education', 'Support educational programs and scholarships'),
('Medicine', 'Provide medical aid and healthcare services'),
('Flood Relief', 'Emergency assistance for flood-affected communities'),
('Winter Cloth', 'Distribute warm clothing during winter season'),
('Clean Water', 'Water purification and sanitation projects'),
('Food Security', 'Provide food assistance to communities in need'),
('General', 'General donation for organizational activities');
