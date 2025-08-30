
CREATE DATABASE IF NOT EXISTS traveller_db;
USE traveller_db;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trips Table
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(100),
    description TEXT,
    destination VARCHAR(100),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Car Bookings Table
CREATE TABLE car_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    pickup_location VARCHAR(255),
    drop_location VARCHAR(255),
    pickup_date DATE,
    pickup_time TIME,
    car_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Travel Packages Table
CREATE TABLE travel_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    destination VARCHAR(100),
    duration VARCHAR(50),
    price DECIMAL(10,2),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Package Bookings Table
CREATE TABLE package_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    package_id INT,
    booking_date DATE,
    status VARCHAR(50) DEFAULT 'Booked',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (package_id) REFERENCES travel_packages(id)
);
