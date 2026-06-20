<?php
include("DBConn.php");

// user reg

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $sql = "INSERT INTO tblUser (fullName,email,username,password,status)
            VALUES ('$name','$email','$username','$password','pending')";
    
    $conn->query($sql);

    echo "Registered. Await admin approval.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pastimes | Register</title>
<link href="assets/fonts.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="shell">
    <div class="card">
        <div class="eyebrow">Pastimes</div>
        <h1 class="title">Create account</h1>
        <p class="subtle">Join the curated marketplace in a few steps.</p>

        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field"><label class="label">Name</label><input class="input" type="text" name="name" required></div>
            <div class="field"><label class="label">Email</label><input class="input" type="email" name="email" required></div>
            <div class="field"><label class="label">Username</label><input class="input" type="text" name="username" required></div>
            <div class="field"><label class="label">Password</label><input class="input" type="password" name="password" required></div>
            <button class="button" type="submit">Register</button>
        </form>
    </div>
</div>
</body>
</html>