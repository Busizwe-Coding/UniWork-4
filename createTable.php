<?php
include("DBConn.php");

/* =========================
   DROP TABLES (SAFE RESET ORDER)
========================= */

$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$conn->query("DROP TABLE IF EXISTS tblMessages");
$conn->query("DROP TABLE IF EXISTS tblSellerRequest");
$conn->query("DROP TABLE IF EXISTS tblOrders");
$conn->query("DROP TABLE IF EXISTS tblAorder");
$conn->query("DROP TABLE IF EXISTS tblClothes");
$conn->query("DROP TABLE IF EXISTS tblAdmin");
$conn->query("DROP TABLE IF EXISTS tblUser");

$conn->query("SET FOREIGN_KEY_CHECKS = 1");

/* =========================
   CREATE TABLES
========================= */

// USERS
$conn->query("
CREATE TABLE tblUser (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    username VARCHAR(50),
    password VARCHAR(255),
    status ENUM('pending','verified') DEFAULT 'pending'
)");

// ADMIN
$conn->query("
CREATE TABLE tblAdmin (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
)");

// CLOTHES
$conn->query("
CREATE TABLE tblClothes (
    clothesID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2),
    size VARCHAR(10),
    description TEXT,
    sellerID INT,
    FOREIGN KEY (sellerID) REFERENCES tblUser(userID)
)");

// CART / ORDERS (TEMP CART)
$conn->query("
CREATE TABLE tblAorder (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    clothesID INT,
    quantity INT DEFAULT 1,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES tblUser(userID),
    FOREIGN KEY (clothesID) REFERENCES tblClothes(clothesID)
)");

// FINAL ORDERS (HISTORY)
$conn->query("
CREATE TABLE tblOrders (
    mainOrderID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    total DECIMAL(10,2),
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// SELLER REQUESTS
$conn->query("
CREATE TABLE tblSellerRequest (
    requestID INT AUTO_INCREMENT PRIMARY KEY,
    sellerID INT,
    brand VARCHAR(100),
    name VARCHAR(100),
    description TEXT,
    image VARCHAR(255),
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    requestDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// MESSAGES
$conn->query("
CREATE TABLE tblMessages (
    messageID INT AUTO_INCREMENT PRIMARY KEY,
    senderID INT,
    receiverID INT,
    message TEXT,
    sentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

echo "All tables created successfully.";
?>