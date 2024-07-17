<?php
session_start();
require 'db.php';

$username = $password = $email = "";
$registration_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $stmt->close();
        
            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sss", $username, $password, $email);
                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['username'] = $username;
                    header('Location: index.php');
                    exit;
                } else {
                    $registration_error = 'Wystąpił błąd podczas rejestracji.';
                }
            }
        } else {
            $registration_error = 'Użytkownik o takim loginie lub emailu już istnieje.';
        }
        $stmt->close();
    } else {
        $registration_error = 'Wystąpił błąd podczas sprawdzania istniejącego użytkownika.';
    }
    $conn->close();
}

?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja - Podatny Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5">Rejestracja</h2>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Zarejestruj się</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>