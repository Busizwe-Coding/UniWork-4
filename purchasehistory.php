<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["user"]["userID"];

$result = $conn->query("
    SELECT * FROM tblOrders 
    WHERE userID = $userID
    ORDER BY orderDate DESC
");

// Track running sum of all entries
$grandTotal = 0; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="shell dashboard">
<div class="container">

<div class="topbar">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title">Purchase History</h1>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="index.php" class="action verify">Home</a>
        <a href="cart.php" class="action verify">Cart</a>
        <a href="checkout.php" class="action verify">Checkout</a>
    </div>

</div>

<div class="panel">

<?php if ($result->num_rows == 0): ?>
    <p>No purchases yet.</p>
<?php else: ?>

<table>
    <tr>
        <th>Order ID</th>
        <th>Total</th>
        <th>Date</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <?php $grandTotal += $row['total']; // Calculate aggregate sum ?>
    <tr>
        <td><?= $row['mainOrderID'] ?></td>
        <td>R<?= $row['total'] ?></td>
        <td><?= $row['orderDate'] ?></td>
    </tr>
    <?php endwhile; ?>

</table>

<hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

<div style="text-align: right; font-size: 20px; font-weight: bold; padding-right: 10px;">
    Total Spent: R<?= $grandTotal ?>
</div>

<?php endif; ?>

</div>

</div>
</div>

</body>
</html>