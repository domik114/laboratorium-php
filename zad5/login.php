<?php
session_start();
include 'db.php';

$allowedAttempts = 3; 
$lockoutTime = 10; 
$delayTime = 2; 

if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $allowedAttempts) {
    if (isset($_SESSION['lockout_time']) && time() - $_SESSION['lockout_time'] < $lockoutTime) {
        echo "Twoje konto jest zablokowane. Spróbuj ponownie później.";
        exit;
    } else {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['lockout_time']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $timestamp_limit = time() - $delayTime;
    $sql_check_attempt = "SELECT COUNT(*) AS attempts FROM failed_login_attempts WHERE username = '$username' AND ip_address = '$ip_address' AND timestamp > '$timestamp_limit'";
    $result_check_attempt = $conn->query($sql_check_attempt);
    $row_check_attempt = $result_check_attempt->fetch_assoc();
    $attempts = $row_check_attempt['attempts'];

    if ($attempts > 0) {
        echo "Przekroczono dozwoloną liczbę prób logowania. Spróbuj ponownie za jakiś czas.";
        exit;
    }

    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("location: welcome.php");
            exit;
        } else {
            logFailedLoginAttempt($conn, $username);
        }
    } else {
        logFailedLoginAttempt($conn, $username);
    }

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }

    if ($_SESSION['login_attempts'] >= $allowedAttempts) {
        $_SESSION['lockout_time'] = time();
    }
    echo "Nieprawidłowa nazwa użytkownika lub hasło." . $_SESSION['login_attempts'];

    $conn->close();
}

function logFailedLoginAttempt($conn, $username) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $timestamp = time();
    $sql = "INSERT INTO failed_login_attempts (username, ip_address, timestamp) VALUES ('$username', '$ip_address', '$timestamp')";
    $conn->query($sql);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form method="post">
        <label for="username">Nazwa użytkownika:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Hasło:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Zaloguj">
    </form>
</body>

</html>
