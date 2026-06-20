<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$sellerID = (int)$_SESSION["user"]["userID"];

if (isset($_POST['submit'])) {

    $brand = $_POST['brand'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // IMAGE UPLOAD
    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];

    $folder = "uploads/" . basename($imageName);
    move_uploaded_file($tmpName, $folder);

    $stmt = $conn->prepare("
        INSERT INTO tblSellerRequest (sellerID, brand, name, description, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issss", $sellerID, $brand, $name, $description, $imageName);
    $stmt->execute();

    echo "Request submitted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sell Clothes</title>
</head>
<body>

<h1>Sell Clothes Request</h1>

<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">

<div class="topbar">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title" style="font-size:28px;">
            Welcome, <?= htmlspecialchars($_SESSION["user"]["fullName"]); ?>
        </h1>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">

        <a href="index.php" class="action verify">Home</a>
        <a href="cart.php" class="action verify">Cart</a>
        <a href="checkout.php" class="action verify">Checkout</a>
        <a href="sellerrequest.php" class="action verify">Sell</a>
        <a href="sellerdashboard.php" class="action verify">Seller</a>

        <form action="logout.php" method="post" style="margin:0;">
            <button class="action delete" type="submit">Logout</button>
        </form>

    </div>

</div>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="brand" placeholder="Brand" required><br><br>

    <input type="text" name="name" placeholder="Clothing Name" required><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <input type="file" name="image" required><br><br>

    <button type="submit" name="submit">Send Request</button>

</form>

</body>
</html>