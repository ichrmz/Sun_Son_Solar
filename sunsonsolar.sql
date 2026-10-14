SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `sunsonsolar`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sunsonsolar`;



CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(50) NOT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `uq_department_name` (`department_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Prefer not to say') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phonenum` varchar(15) NOT NULL,
  `address` varchar(200) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_logins` tinyint(3) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_consent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `uq_customers_email` (`email`),
  UNIQUE KEY `uq_customers_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Prefer not to say') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phonenum` varchar(15) NOT NULL,
  `address` varchar(200) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_logins` tinyint(3) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_by_admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `uq_admins_email` (`email`),
  UNIQUE KEY `uq_admins_username` (`username`),
  CONSTRAINT `fk_admins_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `fk_admins_creator` FOREIGN KEY (`created_by_admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Prefer not to say') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phonenum` varchar(15) NOT NULL,
  `address` varchar(200) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_logins` tinyint(3) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_by_type` enum('employee','admin') DEFAULT NULL,   -- HR employee or admin
  `created_by_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `uq_employees_email` (`email`),
  UNIQUE KEY `uq_employees_username` (`username`),
  CONSTRAINT `fk_employees_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `customer_preferences` (
  `customer_id` int(11) NOT NULL,
  `want_reminders` tinyint(1) NOT NULL DEFAULT 1,
  `want_promotions` tinyint(1) NOT NULL DEFAULT 1,
  `want_system_updates` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`customer_id`),
  CONSTRAINT `fk_prefs_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `account_tokens` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` enum('customer','employee','admin') NOT NULL,
  `account_id` int(11) NOT NULL,
  `purpose` enum('verify_email','reset_password') NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `category` enum('Panels','Inverters','Batteries','Racking & Mounting','Wires') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`service_id`),
  UNIQUE KEY `uq_service_name` (`service_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `company_info` (
  `info_key` varchar(50) NOT NULL,
  `info_value` text NOT NULL,
  PRIMARY KEY (`info_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `request_type` enum('Free Quote','Schedule Service') NOT NULL,
  `property_details` varchar(500) DEFAULT NULL,
  `site_address` varchar(200) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `scheduled_start` datetime DEFAULT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `status` enum('Pending','Approved','Declined','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `decline_reason` varchar(255) DEFAULT NULL,
  `handled_by_type` enum('employee','admin') DEFAULT NULL,  -- dispatcher or admin
  `handled_by_id` int(11) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`booking_id`),
  CONSTRAINT `fk_book_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `fk_book_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('Cash','Bank Transfer','GCash','Card') NOT NULL,
  `status` enum('Pending','Paid','Refunded') NOT NULL DEFAULT 'Pending',
  `receipt_no` varchar(30) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `uq_receipt_no` (`receipt_no`),
  CONSTRAINT `fk_pay_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  CONSTRAINT `fk_pay_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  UNIQUE KEY `uq_feedback_booking` (`booking_id`),
  CONSTRAINT `fk_fb_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  CONSTRAINT `fk_fb_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `chk_fb_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`testimonial_id`),
  CONSTRAINT `fk_testi_feedback` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`feedback_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_type` enum('customer','employee','admin') NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `type` enum('Reminder','Update','Schedule Change','Promotion','System') NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `status` enum('Open','In Progress','Closed') NOT NULL DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ticket_id`),
  CONSTRAINT `fk_ticket_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `ticket_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `sender_type` enum('customer','employee','admin') NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`),
  CONSTRAINT `fk_tmsg_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`ticket_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `booking_assignments` (
  `booking_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `assigned_by_type` enum('employee','admin') DEFAULT NULL,
  `assigned_by_id` int(11) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_id`,`employee_id`),
  CONSTRAINT `fk_assign_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_assign_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `notification_settings` (
  `type` enum('Reminder','Update','Schedule Change','Promotion','System') NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_type` enum('employee','admin') DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `details` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `time_in` datetime NOT NULL DEFAULT current_timestamp(),
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `is_offline` tinyint(1) NOT NULL DEFAULT 0,
  `synced_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`attendance_id`),
  CONSTRAINT `fk_att_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `fk_att_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('General','Reminder','Safety','Urgent') NOT NULL DEFAULT 'General',
  `department_id` int(11) DEFAULT NULL,
  `posted_by_type` enum('employee','admin') DEFAULT NULL,
  `posted_by_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`announcement_id`),
  CONSTRAINT `fk_ann_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `problem_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `report_type` enum('Equipment','Customer Concern','Safety','Other') NOT NULL,
  `description` text NOT NULL,
  `status` enum('New','In Review','Resolved') NOT NULL DEFAULT 'New',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`report_id`),
  CONSTRAINT `fk_rep_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `fk_rep_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `technician_availability` (
  `availability_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `status` enum('Available','Unavailable','On Leave') NOT NULL DEFAULT 'Available',
  PRIMARY KEY (`availability_id`),
  UNIQUE KEY `uq_emp_date` (`employee_id`,`available_date`),
  CONSTRAINT `fk_avail_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE VIEW `v_attendance` AS
SELECT a.attendance_id, a.employee_id,
       CONCAT(e.firstname, ' ', e.lastname) AS employee_name,
       a.time_in, a.latitude, a.longitude, a.is_offline
FROM `attendance` a
JOIN `employees` e ON e.employee_id = a.employee_id;

CREATE VIEW `v_assigned_jobs` AS
SELECT ba.employee_id, b.booking_id,
       CONCAT(c.firstname, ' ', c.lastname) AS customer_name,
       COALESCE(b.site_address, c.address) AS address,
       s.service_name, b.scheduled_start, b.status
FROM `booking_assignments` ba
JOIN `bookings` b ON b.booking_id = ba.booking_id
JOIN `customers` c ON c.customer_id = b.customer_id
JOIN `services` s ON s.service_id = b.service_id;

CREATE VIEW `v_customer_history` AS
SELECT b.customer_id, b.booking_id, s.service_name, b.scheduled_start,
       p.amount, p.method, p.status AS payment_status, p.receipt_no, p.paid_at
FROM `bookings` b
JOIN `services` s ON s.service_id = b.service_id
LEFT JOIN `payments` p ON p.booking_id = b.booking_id
WHERE b.status = 'Completed';



INSERT INTO `departments` (`department_name`) VALUES
('Administration'), ('IT'), ('Dispatch'), ('Accounting'),
('HR'), ('Marketing'), ('Sales'), ('Customer Service');

INSERT INTO `services` (`service_name`) VALUES
('Consultation'), ('Designing'), ('Permitting'), ('Installation'),
('Maintenance'), ('Repair'), ('Monitoring');

INSERT INTO `notification_settings` (`type`, `is_enabled`) VALUES
('Reminder', 1), ('Update', 1), ('Schedule Change', 1), ('Promotion', 1), ('System', 1);

INSERT INTO `company_info` (`info_key`, `info_value`) VALUES
('company_name', 'Sun Son Solar'),
('tagline', 'Power Your Future with Solar'),
('description', 'Update: products, services and location.'),
('benefit_savings', 'Cost savings: Update'),
('benefit_green', 'Sustainability: Update'),
('benefit_energy', 'Energy independence: Update'),
('email', 'katherine.sinagaraw@sunsonsolar.com'),
('phone', '09291230983'),
('address', 'Update company address'),
('facebook_url', 'Update'),
('instagram_url', 'Update'),
('legal_notice', 'Update: terms, copyright notice.'),
('privacy_notice', 'Update: shown at registration.');

INSERT INTO `customers`
(`firstname`,`middlename`,`lastname`,`birthdate`,`gender`,`email`,`phonenum`,`address`,`username`,`password`,`email_verified`,`privacy_consent`) VALUES
('Katherine','Olap','Sinagaraw','1990-07-01','Prefer not to say',
 'katherine.sinagaraw@sunsonsolar.com','09291230983','Update address',
 'KittyKat16','$2b$10$Gyvaul9tOXTQo7Cbko7bAuFUEjUPVoq0qxTSPc5fbkjvoc1CgW566',1,1);

INSERT INTO `admins`
(`department_id`,`position`,`firstname`,`middlename`,`lastname`,`birthdate`,`gender`,`email`,`phonenum`,`address`,`username`,`password`) VALUES
(2,'IT Head','Sol','Sun','Solis','1967-01-08','Prefer not to say',
 'sol.solis@sunsonsolar.com','09291230983','Update address',
 'admin','$2b$10$qAP..5dTgyfk27fZx1UnmeVqnJO1Jemsw64RU5IxRYCg5sqpk1J7.');

INSERT INTO `customer_preferences` (`customer_id`) VALUES (1);
