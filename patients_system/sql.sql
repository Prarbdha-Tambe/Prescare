CREATE DATABASE patients_db;

USE patients_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    bloodgroup VARCHAR(10),
    height DECIMAL(5,2),
    weight DECIMAL(5,2)
);
