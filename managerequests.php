<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: adminLogin.php");
    exit();
}
?>
<?php
include("dbconn.php");

/* =========================
   APPROVE REQUEST
========================= */
if (isset($_GET['approve'])) {

    $id = (int)$_GET['approve'];

    // Get request data
    $req = $conn->query("SELECT * FROM tblSellerRequest WHERE requestID=$id")->fetch_assoc();

    if ($req) {

        // Insert into clothes table
        $stmt = $conn->prepare("
            INSERT INTO tblClothes (name, price, size, description, sellerID)
            VALUES (?, ?, ?, ?, ?)
        ");

        $price = 0; // default price (admin can edit later)
        $size = "M";

        $stmt->bind_param(
            "sdssi",
            $req['name'],
            $price,
            $size,
            $req['description'],
            $req['sellerID']
        );

        $stmt->execute();

        // Mark approved
        $conn->query("
            UPDATE tblSellerRequest 
            SET status='Approved' 
            WHERE requestID=$id
        ");
    }
    header("Location: managerequests.php");
    exit();
}

/* =========================
   REJECT REQUEST
========================= */
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];

    $conn->query("
        UPDATE tblSellerRequest 
        SET status='Rejected' 
        WHERE requestID=$id
    ");
    header("Location: managerequests.php");
    exit();
}

/* =========================
   GET REQUESTS
========================= */
$result = $conn->query("SELECT * FROM tblSellerRequest ORDER BY requestDate DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pastimes | Seller Requests</title>
<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="shell">
    <div class="container">

        <div class="topbar">
            <div>
                <div class="eyebrow">Pastimes Admin</div>
                <h1 class="title">Seller Requests</h1>
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

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="name"><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td class="status"><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <div class="actions" style="display:flex; gap:5px;">
                                <?php if ($row['status'] == "Pending"): ?>
                                    <a class="action verify" href="?approve=<?= $row['requestID'] ?>">Approve</a>
                                    <a class="action delete" href="?reject=<?= $row['requestID'] ?>">Reject</a>
                                <?php else: ?>
                                    <span class="subtle">Done</span>
                                <?php endif; ?>
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