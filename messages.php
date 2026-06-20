<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);
?>
<?php
session_start();
include("dbconn.php");

if (!isset($_SESSION["user"]) && !isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$isAdmin = isset($_SESSION["admin"]);

$adminID = 1;

if ($isAdmin) {
    $senderID = $_SESSION["admin"]["adminID"];
} else {
    $senderID = $_SESSION["user"]["userID"];
}

/* SEND MESSAGE */
if (isset($_POST['send'])) {

    $message = trim($_POST['message']);

    if ($message != "") {

        // USER → ADMIN
        if (!$isAdmin) {
            $receiverID = $adminID;
        } 
        // ADMIN → USER
        else {
            $receiverID = (int)$_POST['receiverID']; // admin chooses user
        }

        $stmt = $conn->prepare("
            INSERT INTO tblMessages (senderID, receiverID, message)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $senderID, $receiverID, $message);
        $stmt->execute();
    }
}

/* GET MESSAGES */
if ($isAdmin) {

    $result = $conn->query("
        SELECT 
            m.*,
            u.fullName AS senderName
        FROM tblMessages m
        LEFT JOIN tblUser u ON m.senderID = u.userID
        ORDER BY m.sentDate DESC
    ");

} else {

    $result = $conn->query("
        SELECT 
            m.*,
            u.fullName AS senderName
        FROM tblMessages m
        LEFT JOIN tblUser u ON m.senderID = u.userID
        WHERE (m.senderID=$senderID AND m.receiverID=1)
           OR (m.senderID=1 AND m.receiverID=$senderID)
        ORDER BY m.sentDate DESC
    ");
}

$result = $conn->query("
    SELECT m.*, u.fullName
    FROM tblMessages m
    LEFT JOIN tblUser u ON m.senderID = u.userID
    ORDER BY m.sentDate DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="shell">
<div class="container">

<div class="shell dashboard">
<div class="container">

<div class="topbar" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">

    <div>
        <div class="eyebrow">Pastimes</div>
        <h1 class="title" style="font-size:28px;">
            Messages
        </h1>
    </div>

    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

        <a href="index.php" class="action verify">Home</a>

    </div>

    <form action="logout.php" method="post" style="margin:0;">
        <button class="signout" type="submit">Logout</button>
    </form>

</div>

<div class="panel">

<!-- MESSAGE FORM -->
<form method="POST">
    <textarea name="message" class="input" placeholder="Type message..." required></textarea><br><br>
    <button class="action verify" type="submit" name="send">Send</button>
</form>

<hr>

<!-- MESSAGE LIST -->
<?php while($row = $result->fetch_assoc()): ?>

<div style="padding:10px; margin-bottom:10px; background:#fff; border-radius:10px;">

    <b>
        <?= htmlspecialchars($row['fullName'] ?? 'Admin') ?>
        →
        Admin
    </b>

    <br>

    <?= htmlspecialchars($row['message']) ?>

    <br>

    <small><?= $row['sentDate'] ?></small>

</div>

<?php endwhile; ?>

</div>

</div>
</div>

</body>
</html>