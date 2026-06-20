<?php
$host = "localhost";
$user_db = "root";
$password = "";
$dbname = "ClothingStore";

$conn = new mysqli($host, $user_db, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =========================
   CLOTHES DATA
========================= */
$items = [
    ['name' => 'Shirt', 'price' => 150.00, 'size' => 'M', 'description' => 'A good quality shirt', 'image' => 'shirt.png'],
    ['name' => 'Joggers', 'price' => 200.00, 'size' => 'L', 'description' => 'Comfortable joggers', 'image' => 'joggers.jpg'],
    ['name' => 'Jacket', 'price' => 350.00, 'size' => 'M', 'description' => 'A stylish jacket', 'image' => 'jacket.jpg'],
    ['name' => 'Skirt', 'price' => 120.00, 'size' => 'S', 'description' => 'A fashionable skirt', 'image' => 'skirt.jpg'],
    ['name' => 'Jeans', 'price' => 250.00, 'size' => 'L', 'description' => 'Durable denim jeans', 'image' => 'jeans.png']
];

/* =========================
   INSERT INTO DATABASE
========================= */
foreach ($items as $item) {

    $name = $conn->real_escape_string($item['name']);
    $price = $item['price'];
    $size = $conn->real_escape_string($item['size']);
    $description = $conn->real_escape_string($item['description']);
    $sellerID = 1;
    $image = $conn->real_escape_string($item['image']);

    $sql = "INSERT INTO tblClothes (name, price, size, description, sellerID, image)
            VALUES ('$name', '$price', '$size', '$description', '$sellerID', '$image')";

    if (!$conn->query($sql)) {
        echo "Error inserting {$name}: " . $conn->error . "<br>";
    }
}

$conn->close();

echo "Items added successfully!";
?>