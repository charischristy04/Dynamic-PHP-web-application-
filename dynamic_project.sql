-- Create database
CREATE DATABASE IF NOT EXISTS dynamic_project;
USE dynamic_project;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    course VARCHAR(50) NOT NULL,
    enrollment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO students (name, email, phone, course, enrollment_date) VALUES
('Krishna Prasanth', 'krishnaprasanth@gmail.com', '9876543210', 'Computer Science', '2022-07-15'),
('Sameer Sha', 'sameershas@gmail.com', '9876543211', 'Computer Science', '2022-07-15'),
('Sanjith Brijmohan', 'sanjithbrijmohan@gmail.com', '9876543212', 'Computer Science', '2022-07-15');
