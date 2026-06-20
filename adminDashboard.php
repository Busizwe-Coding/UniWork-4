<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);
?>
<?php
session_start();

// Admin protection
if (!isset($_SESSION["admin"])) {
    header("Location: adminlogin.php");
    exit();
}

include("DBConn.php");

// Verify user
if (isset($_GET['verify'])) {
    $id = (int)$_GET['verify'];
    $conn->query("UPDATE tblUser SET status='verified' WHERE userID=$id");
    header("Location: adminDashboard.php");
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM tblUser WHERE userID=$id");
    header("Location: adminDashboard.php");
    exit();
}

// Save edited name
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_edit'])) {
    $id = (int)$_POST['userID'];
    $newName = $conn->real_escape_string($_POST['fullName']);
    $conn->query("UPDATE tblUser SET fullName='$newName' WHERE userID=$id");
    header("Location: adminDashboard.php");
    exit();
}

// Get users
$result = $conn->query("SELECT * FROM tblUser");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pastimes | Admin Dashboard</title>
<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="shell">
    <div class="container">

        <div class="topbar">

            <div>
                <div class="eyebrow">Pastimes Admin</div>
                <h1 class="title">User Management</h1>
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
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="name">
                            <?php if (isset($_GET['edit']) && (int)$_GET['edit'] === (int)$row['userID']): ?>
                                <form method="POST" action="adminDashboard.php" style="display:flex; gap:5px; margin:0;">
                                    <input type="hidden" name="userID" value="<?= (int)$row['userID'] ?>">
                                    <input class="input" type="text" name="fullName" value="<?= htmlspecialchars($row['fullName']); ?>" required style="padding: 5px; font-size:14px;">
                                    <button class="action verify" type="submit" name="save_edit" style="border:none; cursor:pointer;">Save</button>
                                    <a class="action delete" href="adminDashboard.php" style="text-decoration:none;">Cancel</a>
                                </form>
                            <?php else: ?>
                                <?php echo htmlspecialchars($row['fullName']); ?>
                            <?php endif; ?>
                        </td>
                        <td class="status"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <div class="actions" style="display:flex; gap:5px;">
                                <a class="action verify" href="?edit=<?php echo (int)$row['userID']; ?>">Edit</a>
                                <a class="action verify" href="?verify=<?php echo (int)$row['userID']; ?>">Verify</a>
                                <a class="action delete" href="?delete=<?php echo (int)$row['userID']; ?>">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; text-align: left;">
            <a class="action verify" href="register.php" style="text-decoration: none; display: inline-block; padding: 10px 20px;">+ Add New User</a>
        </div>

    </div>
</div>
</body>
</html>