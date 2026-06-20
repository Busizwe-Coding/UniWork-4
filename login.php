<?php
include("DBConn.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = md5($_POST["password"]); // match given hash

    $sql = "SELECT * FROM tblUser 
            WHERE username='$username' AND email='$email'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row["password"] == $password && $row["status"] == "verified") {
            session_start();

            $_SESSION["user"] = $row;

            header("Location: index.php");
            exit();
        } else {
            $message = "Incorrect password or not verified.";
        }
    } else {
        $message = "User does not exist.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pastimes | Login</title>
<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="shell">
    <div class="card">
        <div class="eyebrow">Pastimes</div>
        <h1 class="title">Welcome back</h1>
        <p class="subtle">Sign in to continue your curated experience.</p>

        <?php if ($message !== ""): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label class="label">Username</label>
                <input class="input" type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="field">
                <label class="label">Email</label>
                <input class="input" type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="field">
                <label class="label">Password</label>
                <input class="input" type="password" name="password" required>
            </div>
            <button class="button" type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>