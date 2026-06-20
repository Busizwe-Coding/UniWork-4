<?php
session_start();

// Admin protection
if (!isset($_SESSION["admin"])) {
    header("Location: adminLogin.php");
    exit();
}

include("dbconn.php");

/* =========================
   DELETE ITEM
========================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM tblClothes WHERE clothesID=$id");
    header("Location: manageclothes.php");
    exit();
}

/* =========================
   UPDATE ITEM
========================= */
if (isset($_POST['update'])) {

    $id = (int)$_POST['clothesID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        UPDATE tblClothes 
        SET name=?, price=?, size=?, description=?
        WHERE clothesID=?
    ");

    $stmt->bind_param("sdssi", $name, $price, $size, $description, $id);
    $stmt->execute();
    
    header("Location: manageclothes.php");
    exit();
}

/* =========================
   GET SINGLE ITEM FOR EDIT
========================= */
$editItem = null;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM tblClothes WHERE clothesID=$id");
    $editItem = $result->fetch_assoc();
}

/* =========================
   GET ALL CLOTHES
========================= */
$items = $conn->query("SELECT * FROM tblClothes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes | Manage Clothes</title>
    <link href="assets/fonts.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="shell">
    <div class="container">

        <div class="topbar">
            <div>
                <div class="eyebrow">Pastimes Admin</div>
                <h1 class="title">Manage Clothes</h1>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="action verify" href="adminDashboard.php">Users</a>
                <a class="action verify" href="managerequests.php">Seller Requests</a>
                <a class="action verify" href="manageclothes.php">Clothes</a>
                <a class="action verify" href="messages.php">Messages</a> 
                <a class="action verify" href="logout.php?redirect=mainPage.php">View Site</a>
                <a class="action delete" href="logout.php">Logout</a>
            </div>
        </div>

        <?php if ($editItem): ?>
        <div class="panel" style="margin-bottom: 25px;">
            <h2>Edit Clothing Item</h2>
            <form method="POST">
                <input type="hidden" name="clothesID" value="<?= $editItem['clothesID'] ?>">
                
                <div class="field">
                    <label class="label">Item Name</label>
                    <input class="input" type="text" name="name" value="<?= htmlspecialchars($editItem['name']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Price (R)</label>
                    <input class="input" type="text" name="price" value="<?= htmlspecialchars($editItem['price']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Size</label>
                    <input class="input" type="text" name="size" value="<?= htmlspecialchars($editItem['size']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Description</label>
                    <textarea class="input" name="description" rows="4" style="height:auto; font-family:inherit; padding:10px;" required><?= htmlspecialchars($editItem['description']) ?></textarea>
                </div>

                <div style="display:flex; gap:10px; margin-top:15px;">
                    <button type="submit" name="update" class="action verify" style="border:none; cursor:pointer; padding:10px 20px;">Update</button>
                    <a href="manageclothes.php" class="action delete" style="text-decoration:none; padding:10px 20px;">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td class="name"><?= htmlspecialchars($row['name']) ?></td>
                        <td>R<?= htmlspecialchars($row['price']) ?></td>
                        <td><?= htmlspecialchars($row['size']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <div class="actions" style="display:flex; gap:5px;">
                                <a class="action verify" href="?edit=<?= $row['clothesID'] ?>">Edit</a>
                                <a class="action delete" href="?delete=<?= $row['clothesID'] ?>" onclick="return confirm('Delete item?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>