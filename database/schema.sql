-- ============================================
-- Craft Platform - Database Schema
-- University Project - Craftsmen Services
-- ============================================

CREATE DATABASE IF NOT EXISTS craft_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE craft_db;

-- ============================================
-- 1. Users
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('homeowner', 'craftsman', 'admin') NOT NULL DEFAULT 'homeowner',
    avatar VARCHAR(255) DEFAULT NULL,
    preferred_lang ENUM('en', 'ar') NOT NULL DEFAULT 'en',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_users_email (email),
    INDEX idx_users_role (role),
    INDEX idx_users_active (is_active)
) ENGINE=InnoDB;

-- ============================================
-- 2. Wilayas (58 Algerian provinces)
-- ============================================
CREATE TABLE wilayas (
    id INT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,

    INDEX idx_wilayas_name_en (name_en)
) ENGINE=InnoDB;

-- ============================================
-- 3. Communes
-- ============================================
CREATE TABLE communes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wilaya_id INT NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    post_code VARCHAR(10) DEFAULT NULL,

    FOREIGN KEY (wilaya_id) REFERENCES wilayas(id) ON DELETE CASCADE,
    INDEX idx_communes_wilaya (wilaya_id)
) ENGINE=InnoDB;

-- ============================================
-- 4. Categories (Dynamic, admin-managed)
-- ============================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT 'tools',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_categories_active (is_active)
) ENGINE=InnoDB;

-- ============================================
-- 5. Craftsman Profiles
-- ============================================
CREATE TABLE craftsman_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    category_id INT NOT NULL,
    bio TEXT DEFAULT NULL,
    experience_years INT NOT NULL DEFAULT 0,
    wilaya_id INT NOT NULL,
    commune_id INT DEFAULT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    avg_rating DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    total_reviews INT NOT NULL DEFAULT 0,
    total_jobs_done INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (wilaya_id) REFERENCES wilayas(id) ON DELETE RESTRICT,
    FOREIGN KEY (commune_id) REFERENCES communes(id) ON DELETE SET NULL,

    INDEX idx_cp_category (category_id),
    INDEX idx_cp_wilaya (wilaya_id),
    INDEX idx_cp_verified (is_verified),
    INDEX idx_cp_available (is_available),
    INDEX idx_cp_rating (avg_rating DESC)
) ENGINE=InnoDB;

-- ============================================
-- 6. Portfolio Items
-- ============================================
CREATE TABLE portfolio_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    craftsman_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (craftsman_id) REFERENCES craftsman_profiles(id) ON DELETE CASCADE,
    INDEX idx_portfolio_craftsman (craftsman_id)
) ENGINE=InnoDB;

-- ============================================
-- 7. Jobs
-- ============================================
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    budget_min DECIMAL(10,2) DEFAULT NULL,
    budget_max DECIMAL(10,2) DEFAULT NULL,
    wilaya_id INT NOT NULL,
    commune_id INT DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    urgency ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (wilaya_id) REFERENCES wilayas(id) ON DELETE RESTRICT,
    FOREIGN KEY (commune_id) REFERENCES communes(id) ON DELETE SET NULL,

    INDEX idx_jobs_user (user_id),
    INDEX idx_jobs_category (category_id),
    INDEX idx_jobs_wilaya (wilaya_id),
    INDEX idx_jobs_status (status),
    INDEX idx_jobs_urgency (urgency),
    INDEX idx_jobs_created (created_at DESC)
) ENGINE=InnoDB;

-- ============================================
-- 8. Job Images
-- ============================================
CREATE TABLE job_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    INDEX idx_jobimg_job (job_id)
) ENGINE=InnoDB;

-- ============================================
-- 9. Applications (Bids)
-- ============================================
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    craftsman_id INT NOT NULL,
    message TEXT NOT NULL,
    proposed_price DECIMAL(10,2) DEFAULT NULL,
    status ENUM('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (craftsman_id) REFERENCES craftsman_profiles(id) ON DELETE CASCADE,

    UNIQUE KEY uq_application (job_id, craftsman_id),
    INDEX idx_app_job (job_id),
    INDEX idx_app_craftsman (craftsman_id),
    INDEX idx_app_status (status)
) ENGINE=InnoDB;

-- ============================================
-- 10. Bookings
-- ============================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    craftsman_id INT NOT NULL,
    job_id INT DEFAULT NULL,
    description TEXT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME DEFAULT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (craftsman_id) REFERENCES craftsman_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL,

    INDEX idx_booking_user (user_id),
    INDEX idx_booking_craftsman (craftsman_id),
    INDEX idx_booking_status (status),
    INDEX idx_booking_date (booking_date)
) ENGINE=InnoDB;

-- ============================================
-- 11. Reviews
-- ============================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    craftsman_id INT NOT NULL,
    booking_id INT DEFAULT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (craftsman_id) REFERENCES craftsman_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,

    UNIQUE KEY uq_review (user_id, craftsman_id, booking_id),
    INDEX idx_review_craftsman (craftsman_id),
    INDEX idx_review_rating (rating)
) ENGINE=InnoDB;

-- ============================================
-- 12. Conversations
-- ============================================
CREATE TABLE conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    last_message_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,

    UNIQUE KEY uq_conversation (user1_id, user2_id),
    INDEX idx_conv_user1 (user1_id),
    INDEX idx_conv_user2 (user2_id),
    INDEX idx_conv_last_msg (last_message_at DESC)
) ENGINE=InnoDB;

-- ============================================
-- 13. Messages
-- ============================================
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    body TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_msg_conversation (conversation_id, created_at),
    INDEX idx_msg_unread (conversation_id, is_read)
) ENGINE=InnoDB;

-- ============================================
-- 14. Notifications
-- ============================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_notif_user (user_id, is_read),
    INDEX idx_notif_created (created_at DESC)
) ENGINE=InnoDB;

-- ============================================
-- Default Admin User (password: admin123)
-- ============================================
INSERT INTO users (name, email, password, role, is_active) VALUES
('Admin', 'admin@craft.dz', '$2y$12$LJ3m4ys3Wz0G8Q5r1K7K5eN3v2X8p6R4t9Y1u0W2s5A7d8F3g6H9j', 'admin', 1);

-- ============================================
-- Default Categories
-- ============================================
INSERT INTO categories (name_en, name_ar, icon, sort_order) VALUES
('Plumbing', 'سباكة', 'plumbing', 1),
('Electrical Work', 'كهرباء', 'electrical', 2),
('Painting', 'دهان', 'painting', 3),
('Carpentry', 'نجارة', 'carpentry', 4),
('Masonry & Tiling', 'بناء و تبليط', 'masonry', 5),
('Welding & Ironwork', 'لحام و حدادة', 'welding', 6),
('AC & Heating', 'تكييف و تدفئة', 'ac', 7),
('Cleaning', 'تنظيف', 'cleaning', 8),
('Gardening', 'بستنة', 'gardening', 9),
('Appliance Repair', 'إصلاح أجهزة', 'appliance', 10);
