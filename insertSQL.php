<?php
//run this by typing: http://localhost/Pastimes/insertSQL.php in the url
//this is to update myAdminphp

// this is loadClothingStore.php but renaming it is a hassel

$servername = "localhost";
$username = "root";
$password = "";  // default XAMPP password
$dbname = "ClothingStore";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$file = "C:/xampp/htdocs/Pastimes/userData.txt";

// Read the entire contents of the file
$sql = file_get_contents($file);

if ($sql === false) {
    die("Error reading file.");
} else {
    // Execute the SQL statements in the file
    if ($conn->multi_query($sql)) {
        echo "SQL queries executed successfully!";
    } else {
        echo "Error executing queries: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>