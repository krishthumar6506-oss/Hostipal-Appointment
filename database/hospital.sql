CREATE DATABASE IF NOT EXISTS `hospital_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hospital_db`;

-- Drop existing tables if re-initializing
DROP TABLE IF EXISTS `appointment`;
DROP TABLE IF EXISTS `schedule`;
DROP TABLE IF EXISTS `doctor`;
DROP TABLE IF EXISTS `patient`;
DROP TABLE IF EXISTS `admin`;

-- 1. Admin Table
CREATE TABLE `admin` (
  `admin_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Patient Table
CREATE TABLE `patient` (
  `patient_id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `age` INT NOT NULL,
  `address` TEXT NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Doctor Table
CREATE TABLE `doctor` (
  `doctor_id` INT AUTO_INCREMENT PRIMARY KEY,
  `doctor_name` VARCHAR(100) NOT NULL,
  `specialization` VARCHAR(100) NOT NULL,
  `qualification` VARCHAR(150) NOT NULL,
  `experience` INT NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Schedule Table
CREATE TABLE `schedule` (
  `schedule_id` INT AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` INT NOT NULL,
  `available_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `status` ENUM('Available', 'Booked', 'Inactive') DEFAULT 'Available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor`(`doctor_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Appointment Table
CREATE TABLE `appointment` (
  `appointment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `schedule_id` INT DEFAULT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `reason` TEXT DEFAULT NULL,
  `status` ENUM('Pending', 'Approved', 'Cancelled', 'Completed') DEFAULT 'Pending',
  `booking_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`patient_id`) REFERENCES `patient`(`patient_id`) ON DELETE CASCADE,
  FOREIGN KEY (`doctor_id`) REFERENCES `doctor`(`doctor_id`) ON DELETE CASCADE,
  FOREIGN KEY (`schedule_id`) REFERENCES `schedule`(`schedule_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
