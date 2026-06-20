<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$sellerID = (int)$_SESSION["user"]["userID"];

$result = $conn->query("
    SELECT * FROM tblSellerRequest 
    WHERE sellerID=$sellerID
    ORDER BY requestDate DESC
");
?>

<h1>Seller Dashboard</h1>
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

<table border="1">
<tr>
    <th>Item</th>
    <th>Brand</th>
    <th>Status</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['brand'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>