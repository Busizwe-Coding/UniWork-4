CREATE DATABASE ClothingStore;
USE ClothingStore;

-- User Table
CREATE TABLE tblUser (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    username VARCHAR(50),
    password VARCHAR(255),
    status ENUM('pending','verified') DEFAULT 'pending'
);

-- Admin Table
CREATE TABLE tblAdmin (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

-- Clothes Table
CREATE TABLE tblClothes (
    clothesID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2),
    size VARCHAR(10),
    description TEXT,
    sellerID INT,
    FOREIGN KEY (sellerID) REFERENCES tblUser(userID)
);

-- Order Table
CREATE TABLE tblAorder (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    clothesID INT,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES tblUser(userID),
    FOREIGN KEY (clothesID) REFERENCES tblClothes(clothesID)
);