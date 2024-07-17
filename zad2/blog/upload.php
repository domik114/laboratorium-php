<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fileToUpload'])) {
    $uploadedNametemp = $_FILES['fileToUpload']['name'];
    // $uploadedName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $uploadedNametemp);

    $encrypted = encrypt($uploadedNametemp, $key);

    $command = "echo " . $encrypted;
    $output = shell_exec($command);

    $decrypted = decrypt($encrypted, $key);

    $message = "<pre>$decrypted</pre>";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Prześlij plik - Symulacja</title>
</head>
<body>
    <h1>Prześlij plik</h1>
    <p>Ta funkcja pozwala na przesyłanie plików do archiwizacji</p>
    <?php if ($message) echo $message; ?>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        Wybierz plik do przesłania:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Prześlij plik" name="submit">
    </form>
</body>
</html>
