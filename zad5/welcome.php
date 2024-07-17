<link rel="stylesheet" href="style.css">


<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

echo "Witaj, " . $_SESSION['username'] . "! Pomyślnie zalogowano.";
session_destroy();
?>
