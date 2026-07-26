-- CREATE DATABASE ArcherFinddb;
-- USE archerfinddb;

-- =========================================================================
-- 1. INDEPENDENT & LOOKUP TABLES
-- =========================================================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL, -- Application logic should enforce @dlsu.edu.ph
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Student', 'Staff', 'Admin') NOT NULL DEFAULT 'Student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	deleted ENUM('1','0') NOT NULL DEFAULT '0'
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    deleted ENUM('1','0') NOT NULL DEFAULT '0'
) ;

CREATE TABLE brands (
    brand_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,       -- e.g., "Apple", "Samsung", "AquaFlask", "Uniqlo"
    deleted ENUM('1','0') NOT NULL DEFAULT '0' 
) ;

-- JUNCTION TABLE: Links Brands to Categories for relational filtering. Needed because each brand can have multiple categories
CREATE TABLE category_brands (
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    PRIMARY KEY (category_id, brand_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE CASCADE
) ;

-- =========================================================================
-- 2. LOCATION INFRASTRUCTURE TABLES
-- =========================================================================

CREATE TABLE buildings (
    building_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,       -- e.g., "Don Enrique T. Yuchengco Hall"
    max_level INT,
    abbreviation ENUM('LS','HSSH', 'AG', 'Y', 'G', 'V', 'SJ', 'SM', 'BC', 'WH', 'ER', 'MM', 'STR', 'BH') UNIQUE NOT NULL,
    deleted ENUM('1','0') NOT NULL DEFAULT '0'
    
) ;

-- For specific classrooms, auditoriums, with 4 walls
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,                -- e.g., "Y402", "G304B", "AG1906", "Discussion Room 8D"
    building_id INT NOT NULL,
    level INT,
    deleted ENUM('1','0') NOT NULL DEFAULT '0',
    
    FOREIGN KEY (building_id) REFERENCES buildings(building_id) ON DELETE CASCADE
) ;

-- For open areas, places like canteen, hallway, garden, amphitheater, study area
CREATE TABLE areas (
    area_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,               -- e.g., , "Perico's Canteen", "Kitchen City Canteen", "Seating Area", "Level 6 Library area"
    building_id INT NOT NULL,
    level INT,
    deleted ENUM('1','0') NOT NULL DEFAULT '0',
    
    FOREIGN KEY (building_id) REFERENCES buildings(building_id) ON DELETE CASCADE
) ;

-- =========================================================================
-- 3. CORE TRANSACTIONAL TABLES
-- =========================================================================

CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    category_id INT,
    brand_id INT, 
    surrendered_by INT, -- Student finder
    claimed_by INT,     -- Student claimer
    room_id INT,
    area_id INT,
    when_found DATETIME NOT NULL,
    status ENUM('In Storage', 'Claimed', 'Disposed') NOT NULL DEFAULT 'In Storage',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted ENUM('1','0') NOT NULL DEFAULT '0',
    
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE SET NULL,
    FOREIGN KEY (surrendered_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (claimed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE SET NULL,
    FOREIGN KEY (area_id) REFERENCES areas(area_id) ON DELETE SET NULL
);

CREATE TABLE items_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    img_filepath VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);

CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    item_description TEXT NOT NULL,
    category_id INT,
    brand_id INT,
    item_id INT, 
    room_id INT,
    area_id INT,
    when_found DATETIME, -- For Surrender Form
    when_lost DATETIME,  -- For Claim request or Lost Report
    extra_details TEXT,
    reviewed_by INT,    -- Linked to Staff User ID
    status ENUM('Active', 'Closed', 'Accepted', 'Resolved', 'Rejected') NOT NULL DEFAULT 'Active',
    type ENUM('Claim request', 'Loss Report', 'Surrender Form'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted ENUM('1','0') NOT NULL DEFAULT '0',
    
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (brand_id) REFERENCES brands(brand_id) ON DELETE SET NULL,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE SET NULL,
    FOREIGN KEY (area_id) REFERENCES areas(area_id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE reports_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    img_filepath VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE
);

CREATE TABLE contacts_received (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    inquiry ENUM('Issue with claiming an item', 'Issue with reporting an item', 'Account / Verification issues', 'General inquiry / Feedback') NOT NULL,
    message TEXT NOT NULL,
    status ENUM('Active','Closed') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

-- =========================================================================
-- 4. AUDIT LOG TABLES
-- =========================================================================

CREATE TABLE items_update_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    old_status ENUM('In Storage', 'Claimed', 'Disposed') NOT NULL,
    new_status ENUM('In Storage', 'Claimed', 'Disposed') NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE SET NULL
) ;


CREATE TABLE reports_update_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT,
    staff_id INT,
    old_status ENUM('Active', 'Closed', 'Accepted', 'Resolved', 'Rejected') NOT NULL,
    new_status ENUM('Active', 'Closed', 'Accepted', 'Resolved', 'Rejected') NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE SET NULL,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE SET NULL
) ;