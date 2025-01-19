-- =========================================
-- 1. Database Creation
-- =========================================
CREATE DATABASE IF NOT EXISTS youdemy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE youdemy_db;

-- =========================================
-- 2. Users Table
-- =========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL,
    status ENUM('pending', 'active', 'blocked') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================================
-- 3. Categories Table
-- =========================================
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- 4. Courses Table
-- =========================================
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content TEXT,
    image_url VARCHAR(255),
    video_url VARCHAR(255),
    teacher_id INT,
    category_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- =========================================
-- 5. Tags Table
-- =========================================
CREATE TABLE IF NOT EXISTS tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- 6. Course_Tags Relation Table
-- =========================================
CREATE TABLE IF NOT EXISTS course_tags (
    course_id INT,
    tag_id INT,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- =========================================
-- 7. Enrollments Table
-- =========================================
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    course_id INT,
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completion_date TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- =========================================
-- 8. Comments Table (Optional)
-- =========================================
CREATE TABLE IF NOT EXISTS comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT NOT NULL,
    user_id INT,
    course_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- =========================================
-- 9. Ratings Table (Optional)
-- =========================================
CREATE TABLE IF NOT EXISTS ratings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    user_id INT,
    course_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rating (user_id, course_id)
);

-- =========================================
-- 10. Insert Sample Categories
-- =========================================
INSERT INTO categories (name, description)
VALUES
('Programming & Development', 'Learn coding, web development, and software engineering skills'),
('Data & Analytics', 'Master data science, business analytics, and statistical analysis'),
('Digital Marketing', 'Explore modern marketing strategies and online advertising techniques'),
('Design & Creative', 'Develop skills in graphic design, UI/UX, and digital art'),
('Business & Management', 'Learn project management, leadership, and business administration'),
('Information Technology', 'Master IT infrastructure, cloud computing, and system administration'),
('Languages & Communication', 'Improve language skills and communication abilities'),
('Personal Development', 'Enhance soft skills, productivity, and personal growth'),
('Health & Wellness', 'Learn about fitness, nutrition, and mental health'),
('Music & Arts', 'Develop artistic skills and musical abilities');

-- =========================================
-- 11. Insert Sample Users
-- =========================================
INSERT INTO users (firstname, lastname, email, password, role, status)
VALUES
('John', 'Smith', 'john.smith@email.com', 'hashedpassword123', 'teacher', 'active'),
('Sarah', 'Johnson', 'sarah.j@email.com', 'hashedpassword123', 'teacher', 'active'),
('Mohammed', 'Ali', 'mohammed.ali@email.com', 'hashedpassword123', 'teacher', 'active'),
('Lisa', 'Chen', 'lisa.chen@email.com', 'hashedpassword123', 'teacher', 'active'),
('David', 'Wilson', 'david.w@email.com', 'hashedpassword123', 'teacher', 'active');

-- =========================================
-- 12. Insert Sample Courses
-- =========================================
INSERT INTO courses (title, description, content, image_url, video_url, teacher_id, category_id)
VALUES 
('Web Development Fundamentals', 'Learn HTML, CSS, and JavaScript basics', 'Front-end development basics', 'web-dev.jpg', 'web-dev.mp4', 1, 1),
('Data Science Essentials', 'Learn data analysis with Python', 'Comprehensive data science content', 'data-science.jpg', 'data-science.mp4', 2, 2),
('Digital Marketing Masterclass', 'Master digital marketing strategies', 'Full guide to online marketing', 'digital-marketing.jpg', 'marketing.mp4', 3, 3);

-- =========================================
-- 13. Show All Tables
-- =========================================
SHOW TABLES;

-- =========================================
-- 14. Verify Data
-- =========================================
SELECT * FROM users;
SELECT * FROM categories;
SELECT * FROM courses;


-- table course adjustement

ALTER TABLE courses
ADD COLUMN content_type ENUM('video', 'document') AFTER content,
ADD COLUMN content_url VARCHAR(255) AFTER content_type;