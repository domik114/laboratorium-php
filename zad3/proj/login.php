<?php
session_start();
require 'db.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $username = htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8');
    // $password = htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8');
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    $conn = connectdb();

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            mysqli_stmt_close($stmt);
            header('Location: index.php');
            exit;
        } else {
            $login_error = 'Niepoprawna nazwa użytkownika lub hasło.';
        }
    }
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
//     $result = $conn->query($sql);

//     if ($result && $result->num_rows > 0) {
//         $user = $result->fetch_assoc();
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['username'] = $user['username'];
//         header('Location: index.php');
//         exit;
//     } else {
//         $login_error = 'Niepoprawna nazwa użytkownika lub hasło.';
//     }
// }

$conn->close();

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie - Podatny Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mt-5">Logowanie</h2>
            <?php if ($login_error != ''): ?>
                <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?php echo $username; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Zaloguj się</button>
            </form>
        </div>
    </div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>