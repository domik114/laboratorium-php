
<link rel="stylesheet" href="style.css">

<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Sprawdzenie, czy hasło spełnia wymagania dotyczące siły hasła
    if (!isStrongPassword($password)) {
        echo "Hasło nie spełnia wymagań dotyczących siły. Hasło powinno mieć co najmniej 8 znaków i zawierać przynajmniej jedną wielką literę, jedną małą literę, jedną cyfrę i jeden znak specjalny.";
        exit;
    }

    // Hashowanie hasła
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hash')";

    if ($conn->query($sql) === TRUE) {
        echo "Rejestracja zakończona sukcesem. <a href='login.php'>Zaloguj się</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

function isStrongPassword($password) {
    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match("/[A-Z]/", $password)) {
        return false;
    }

    if (!preg_match("/[a-z]/", $password)) {
        return false;
    }

    if (!preg_match("/[0-9]/", $password)) {
        return false;
    }

    if (!preg_match("/[^A-Za-z0-9]/", $password)) {
        return false;
    }

    return true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form method="post">
        <label for="username">Nazwa użytkownika:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Hasło:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Zarejestruj">
    </form>
</body>

</html>