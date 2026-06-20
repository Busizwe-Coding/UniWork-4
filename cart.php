<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$userID = (int)$_SESSION["user"]["userID"];

/* =========================
   UPDATE QUANTITY ACTIONS
========================= */
if (isset($_GET['action']) && isset($_GET['id'])) {

    $orderID = (int)$_GET['id'];

    // Increase quantity
    if ($_GET['action'] == "plus") {
        $conn->query("
            UPDATE tblAorder 
            SET quantity = quantity + 1 
            WHERE orderID=$orderID AND userID=$userID
        ");
    }

    // Decrease quantity
    if ($_GET['action'] == "minus") {
        $conn->query("
            UPDATE tblAorder 
            SET quantity = GREATEST(quantity - 1, 1)
            WHERE orderID=$orderID AND userID=$userID
        ");
    }

    // Remove item
    if ($_GET['action'] == "delete") {
        $conn->query("
            DELETE FROM tblAorder 
            WHERE orderID=$orderID AND userID=$userID
        ");
    }

    header("Location: cart.php");
    exit();
}

/* =========================
   GET CART ITEMS
========================= */
$sql = "
SELECT 
    o.orderID,
    o.quantity,
    c.name,
    c.price,
    c.size
FROM tblAorder o
JOIN tblClothes c ON o.clothesID = c.clothesID
WHERE o.userID=$userID
";

$result = $conn->query($sql);

/* =========================
   TOTAL CALCULATION
========================= */
$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="shell dashboard">
<div class="container">

<h1 class="title">Shopping Cart</h1>

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

<div class="panel">

<?php if (count($items) == 0): ?>
    <p>Your cart is empty.</p>
    <a href="mainPage.php" class="action verify">Go Shopping</a>

<?php else: ?>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td class="name"><?= $item['name'] ?></td>
            <td><?= $item['price'] ?></td>

            <td>
                <?= $item['quantity'] ?>
            </td>

            <td>
                <?= $item['subtotal'] ?>
            </td>

            <td>
                <a class="action verify" href="cart.php?action=plus&id=<?= $item['orderID'] ?>">+</a>
                <a class="action verify" href="cart.php?action=minus&id=<?= $item['orderID'] ?>">-</a>
                <a class="action delete" href="cart.php?action=delete&id=<?= $item['orderID'] ?>">Remove</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h2>Total: R<?= $total ?></h2>

<br>

<a href="checkout.php" class="action verify">Checkout</a>

<?php endif; ?>

</div>

</div>
</div>

</body>
</html>