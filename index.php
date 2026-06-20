<?php
session_start();
include("dbconn.php");

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userID = $user["userID"];

// Get cart item count
$cartCountResult = $conn->query("
    SELECT SUM(quantity) AS totalItems
    FROM tblAorder
    WHERE userID = $userID
");

// FIX: Changed $cartResult to $cartCountResult to match the line above
$cartData = $cartCountResult->fetch_assoc();
$cartCount = $cartData['totalItems'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes | Home</title>
    <link href="assets/fonts.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<body>
<div class="shell dashboard">
<div class="container">

<div class="topbar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title" style="font-size:32px; margin-top:5px;">
            Welcome back, <?php echo htmlspecialchars($user["fullName"]); ?>
        </h1>
    </div>

    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

        <a href="mainPage.php" class="action verify" style="text-decoration:none;">Shop</a>

        <a href="cart.php" class="action verify" style="text-decoration:none;">
            Cart
            <?php if ($cartCount > 0): ?>
                <span style="
                    background:red;
                    color:white;
                    border-radius:50%;
                    padding:3px 7px;
                    font-size:12px;
                    margin-left:5px;
                ">
                    <?= $cartCount ?>
                </span>
            <?php endif; ?>
        </a>

        <a href="checkout.php" class="action verify" style="text-decoration:none;">Checkout</a>

        <a href="messages.php" class="action verify" style="text-decoration:none;">Messages</a>

        <a href="purchasehistory.php" class="action verify" style="text-decoration:none;">Purchases</a>     

        <a href="sellerrequest.php" class="action verify" style="text-decoration:none;">Sell</a>

        <a href="sellerdashboard.php" class="action verify" style="text-decoration:none;">Seller</a>

    </div>

    <div style="display:flex; gap:10px; align-items:center;">

        <a href="adminLogin.php" class="signout" style="text-decoration:none; text-align:center; display:inline-block; line-height:normal;">Admin</a>

        <form action="logout.php" method="post" style="margin:0;">
            <button class="signout" type="submit">Logout</button>
        </form>

    </div>

</div>

<div class ="eyebrow">Shopp high quality second hand at a reasonable price.</div>

<div class="panel" style="margin-top:30px;">
    <h2 style="margin-top:0;">Your Account Details</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Status</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($user["userID"]) ?></td>
            <td class="name"><?= htmlspecialchars($user["fullName"]) ?></td>
            <td><?= htmlspecialchars($user["email"]) ?></td>
            <td><?= htmlspecialchars($user["username"]) ?></td>
            <td class="status"><?= htmlspecialchars($user["status"]) ?></td>
        </tr>
    </table>
</div>

</div>
</div>
</body>
</html>