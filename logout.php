<?php
session_start();

// If coming from the "View Site" button
if (isset($_GET['redirect'])) {
    unset($_SESSION["admin"]); // Log out admin only
    header("Location: " . $_GET['redirect']);
    exit();
}

// Default logout behavior (Log out everything)
$_SESSION = array();
session_destroy();
header("Location: login.php");
exit();
?>