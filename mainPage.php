<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$userID = $user["userID"];

$conn = new mysqli("localhost", "root", "", "ClothingStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =========================
   CART COUNT (REAL)
========================= */
$cartResult = $conn->query("
    SELECT SUM(quantity) AS totalItems
    FROM tblAorder
    WHERE userID = $userID
");

$cartData = $cartResult->fetch_assoc();
$cartCount = $cartData['totalItems'] ?? 0;

/* =========================
   CLOTHES DATA
========================= */
$sql = "SELECT * FROM tblClothes";
$result = $conn->query($sql);

$items = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MainPage | View Clothes</title>

<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">

<style>
/* NAVBAR */
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:25px;
}

.nav-links{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.nav-links a{
    text-decoration:none;
}

.cart-badge{
    background:red;
    color:white;
    border-radius:50%;
    padding:3px 7px;
    font-size:12px;
    margin-left:5px;
}

/* CENTERED POPUP */
.popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.85);
    color: #fff;
    padding: 15px 25px;
    border-radius: 8px;
    font-size: 16px;
    z-index: 9999;
    display: none; /* Hidden by default */
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.popup.active {
    display: block; /* Shown when active */
}

/* CLOTHES UI */
.clothing-item{
    display:flex;
    align-items:center;
    margin-bottom:20px;
    padding:15px;
    border:1px solid #ddd;
    border-radius:8px;
    background:#f7f7f7;
}

.item-image{
    width:100px;
    height:100px;
    object-fit:cover;
    margin-right:20px;
}

.item-name{
    font-size:18px;
    font-weight:bold;
}

.item-price{
    font-size:16px;
    color:#6a5c51;
}

.item-description{
    font-size:14px;
    margin-top:8px;
}

.add-to-cart-btn{
    margin-top:10px;
    background:#6a5c51;
    color:#fff;
    padding:10px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.add-to-cart-btn:hover{
    opacity:0.85;
}
</style>
</head>

<body>

<div class="shell dashboard">
<div class="container">

<div class="navbar">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title" style="font-size:28px;">
            Welcome back, <?= htmlspecialchars($user["fullName"]); ?>
        </h1>
    </div>

    <div class="nav-links">

        <a href="index.php" class="action verify">Home</a>

        <a href="cart.php" class="action verify" id="cartLink">
            Cart<?php if ($cartCount > 0): ?>
                <span class="cart-badge" id="cartBadge"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>

        <a href="checkout.php" class="action verify">Checkout</a>

        <a href="sellerrequest.php" class="action verify">Sell</a>

        <a href="sellerdashboard.php" class="action verify">Seller</a>

    </div>

    <div>
        <form action="logout.php" method="post" style="margin:0;">
            <button class="signout" type="submit">Logout</button>
        </form>
    </div>

</div>

<h2>Our Clothing Items</h2>

<div class="items-list">

<?php foreach ($items as $item): ?>
    <div class="clothing-item">

        <img class="item-image"
             src="images/<?= htmlspecialchars($item['image'] ?? strtolower($item['name']) . '.jpg'); ?>"
             alt="<?= htmlspecialchars($item['name']); ?>">

        <div>

            <div class="item-name">
                <?= htmlspecialchars($item["name"]); ?>
            </div>

            <div class="item-price">
                R<?= htmlspecialchars($item["price"]); ?>
            </div>

            <div class="item-description">
                <?= htmlspecialchars($item["description"]); ?>
            </div>

            <form onsubmit="addToCart(event, this)">
                <input type="hidden" name="itemID" value="<?= $item["clothesID"]; ?>">
                <input type="hidden" name="itemName" value="<?= htmlspecialchars($item["name"]); ?>">
                <input type="hidden" name="itemPrice" value="<?= $item["price"]; ?>">

                <button class="add-to-cart-btn" type="submit">
                    Add to Cart
                </button>
            </form>

        </div>

    </div>
<?php endforeach; ?>

</div>

<div class="popup" id="popupMessage"></div>

<script>
function addToCart(event, form) {
    event.preventDefault(); // stop page reload

    let itemID = form.itemID.value;
    let itemName = form.itemName.value;
    let itemPrice = form.itemPrice.value;

    // send to PHP
    fetch("addtocart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "itemID=" + itemID
    })
    .then(response => {
        // Increment badge count instantly upon successful server resolution
        let badge = document.getElementById("cartBadge");
        if (badge) {
            badge.innerText = parseInt(badge.innerText) + 1;
        } else {
            // If badge wasn't rendered originally (count was 0), create it now
            let cartLink = document.getElementById("cartLink");
            let newBadge = document.createElement("span");
            newBadge.className = "cart-badge";
            newBadge.id = "cartBadge";
            newBadge.innerText = "1";
            cartLink.appendChild(newBadge);
        }
    });

    // popup message
    let popup = document.getElementById("popupMessage");
    popup.innerHTML = itemName + " R" + itemPrice + " added successfully!";
    popup.classList.add("active");

    setTimeout(() => {
        popup.classList.remove("active");
    }, 3000);
}
</script>

</div>
</div>

</body>
</html>