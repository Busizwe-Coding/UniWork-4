<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$userID = (int)$_SESSION["user"]["userID"];

/* =========================
   GET CART ITEMS
========================= */
$sql = "
SELECT 
    o.quantity,
    c.price
FROM tblAorder o
JOIN tblClothes c ON o.clothesID = c.clothesID
WHERE o.userID=$userID
";

$result = $conn->query($sql);

$total = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

/* =========================
   CONFIRM ORDER
========================= */
if (isset($_POST['confirm'])) {

    $stmt = $conn->prepare("
        INSERT INTO tblOrders (userID, total)
        VALUES (?, ?)
    ");
    $stmt->bind_param("id", $userID, $total);
    $stmt->execute();

    $conn->query("DELETE FROM tblAorder WHERE userID=$userID");

    // Redirect straight back to mainPage.php instantly upon a successful purchase
    header("Location: mainPage.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="shell dashboard">
<div class="container">

<div class="topbar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title">Checkout</h1>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="mainPage.php" class="action verify">Shop</a>
        <a href="cart.php" class="action verify">Cart</a>
        <a href="checkout.php" class="action verify">Checkout</a>
        <a href="sellerrequest.php" class="action verify">Sell</a>
        <a href="sellerdashboard.php" class="action verify">Seller</a>

        <form action="logout.php" method="post" style="margin:0;">
            <button class="action delete" type="submit">Logout</button>
        </form>
    </div>

</div>

<div class="panel">

<?php if (count($items) == 0): ?>

    <p>Your cart is empty.</p>
    <a href="mainPage.php" class="action verify">Go Shopping</a>

<?php else: ?>

    <h2>Order Summary</h2>

    <table>
        <tr>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= $item['price'] ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= $item['price'] * $item['quantity'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <h2>Total: R<?= $total ?></h2>

    <form method="POST">
        <button class="action verify" name="confirm">
            Confirm Purchase
        </button>
    </form>

<?php endif; ?>

</div>

</div>
</div>

</body>
</html>