CREATE DATABASE IF NOT EXISTS KYAU_Computer_Club;

USE KYAU_Computer_Club;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    designation ENUM('teacher', 'student', 'alumny') NOT NULL, -- Added designation
    batch VARCHAR(10) DEFAULT NULL, -- Made batch optional
    session VARCHAR(20) DEFAULT NULL, -- Added session field for alumni
    profile_picture VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Added timestamp for record creation
);
