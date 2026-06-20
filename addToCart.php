<?php
session_start();

//"Add to Cart" functionality
if (isset($_POST['itemID']) && isset($_SESSION['user']['userID'])) {
    $itemID = (int)$_POST['itemID'];
    $userID = (int)$_SESSION['user']['userID'];

    $host = "localhost";
    $user_db = "root";
    $password = "";
    $dbname = "ClothingStore";

    $conn = new mysqli($host, $user_db, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the item already exists in the cart for this user
    $check_sql = "SELECT orderID FROM tblAorder WHERE userID = $userID AND clothesID = $itemID";
    $result = $conn->query($check_sql);

    if ($result && $result->num_rows > 0) {
        // Item exists: Increment quantity
        $row = $result->fetch_assoc();
        $orderID = $row['orderID'];
        $sql = "UPDATE tblAorder SET quantity = quantity + 1 WHERE orderID = $orderID";
    } else {
        // Item does not exist: Create new entry with quantity 1
        $sql = "INSERT INTO tblAorder (userID, clothesID, quantity) VALUES ($userID, $itemID, 1)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Item added to cart!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>