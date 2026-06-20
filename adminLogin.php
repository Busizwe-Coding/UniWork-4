<?php
// Suppress warnings and errors on the production page
ini_set('display_errors', 0);
error_reporting(0);

session_start();
include("DBConn.php");

if (isset($_SESSION["admin"])) {
    header("Location: adminDashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ensure fields are set to prevent undefined index notices if form submits empty
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if (!empty($username) && !empty($password)) {
        $result = $conn->query("
            SELECT * FROM tblAdmin 
            WHERE username='$username' AND password='$password'
        ");

        if ($result && $result->num_rows > 0) {
            $_SESSION["admin"] = $result->fetch_assoc();
            header("Location: adminDashboard.php");
            exit();
        } else {
            $error = "Invalid login";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pastimes | Admin Login</title>

<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>

<body>

<div class="shell">

    <div class="card">

        <div class="eyebrow">Pastimes Admin</div>
        <h1 class="title">Admin Login</h1>
        <p class="subtle">Enter your admin credentials to continue</p>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label class="label">Username</label>
                <input class="input" type="text" name="username" required>
            </div>

            <div class="field">
                <label class="label">Password</label>
                <input class="input" type="password" name="password" required>
            </div>

            <button class="button" type="submit">
                Login
            </button>

        </form>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
        
    </div>

</div>

</body>
</html>