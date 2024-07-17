<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$avatar = null;

$target_dir = "uploads/avatars/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$key = "0123456789abcdef";

function encrypt($plaintext, $key) {
    $cipher = "aes-128-ecb"; 
    $options = 0; 
    $iv = ""; 
    $encrypted = openssl_encrypt($plaintext, $cipher, $key, $options, $iv);
    return $encrypted;
}

function decrypt($ciphertext, $key) {
    $cipher = "aes-128-ecb"; 
    $options = 0; 
    $iv = ""; 
    $decrypted = openssl_decrypt($ciphertext, $cipher, $key, $options, $iv);
    return $decrypted;
}

if (!empty($_FILES['avatar']['name'])) {
    $encryptedFile = encrypt($_FILES['avatar']['name'], $key);
    $target_file = $target_dir . basename($_FILES['avatar']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Sprawdź, czy plik jest obrazem
    $check = getimagesize($_FILES['avatar']['tmp_name']);
    if($check === false) {
        die("Plik nie jest obrazem.");
    }

    // Sprawdź, czy plik istnieje
    if (file_exists($target_file)) {
        die("Plik już istnieje.");
    }

    // Sprawdź rozmiar pliku
    if ($_FILES['avatar']['size'] > 500000) {
        die("Plik jest za duży.");
    }

    // Dopuszczalne formaty plików
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        die("Tylko formaty JPG, JPEG, PNG i GIF są dozwolone.");
    }

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
        $avatar = $target_file;
    } else {
        die("Wystąpił błąd podczas przesyłania pliku.");
    }
}

if ($stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?")) {
    $stmt->bind_param("sssi", $username, $email, $avatar, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?")) {
        $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: private_info.php');
?>
