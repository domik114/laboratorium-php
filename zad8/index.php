

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSRF</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#intro">Wprowadzenie</a></li>
                <li><a href="#ssrf">Co to jest SSRF?</a></li>
                <li><a href="#form">Sprawdź URL</a></li>
                <li><a href="#protection">Jak chronić się przed SSRF?</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="intro" class="content-section">
            <h2>Wprowadzenie</h2>
            <p>Bezpieczeństwo aplikacji webowych jest kluczowym elementem w dzisiejszym świecie cyfrowym. W tej witrynie omówimy różne zagrożenia, z jakimi mogą się spotkać aplikacje webowe, oraz jak można się przed nimi bronić.</p>
        </section>
        <section id="ssrf" class="content-section">
            <h2>Co to jest SSRF?</h2>
            <p>SSRF (Server-Side Request Forgery) to rodzaj ataku, w którym atakujący zmusza serwer do wysłania żądania HTTP do dowolnego adresu URL, w tym do zasobów wewnętrznych, które normalnie nie są dostępne z zewnątrz. Może to prowadzić do ujawnienia poufnych informacji lub umożliwić atakującemu dalsze działania w sieci wewnętrznej.</p>
        </section>
        <section id="form" class="content-section">
            <h2>Sprawdź URL</h2>
            <form method="GET" action="">
                <label for="url">Podaj URL:</label>
                <input type="text" id="url" name="url" required>
                <button type="submit">Wyślij</button>
            </form>
        </section>
        <section class="content-section">
        <?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    if(!empty($url)) {
        $parsed_url = parse_url($url);
        $allowed_hosts = ['example.com', 'example2.com'];

        if(!in_array($parsed_url['scheme'], ['http', 'https'])) {
            echo '<h2>Error:</h2>';
            echo '<p>Invalid URL scheme</p>';
            exit;
        } 

        if(!in_array($parsed_url['host'], $allowed_hosts)) {
            echo '<h2>Error:</h2>';
            echo '<p>Host is not allowed.</p>';
            exit;
        } 

        $ip = gethostbyname($parsed_url['host']);

        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE |FILTER_FLAG_NO_RES_RANGE) === false) {
            echo '<h2>Error</h2>';
            echo '<p>Not allowed.</p>';
            exit;
        }

        $response = file_get_contents($url);

        echo '<h2>URL:</h2>';
        echo '<pre>' . htmlspecialchars($response) . '</pre>';
    } else {
        echo '<h2>Error.</h2>';
        echo '<p>URL jest pusty.</p>';
    }
} else {
    echo '<h2> Zawartość URL pojawi się tutaj.';
}
?>
        </section>
        <section id="protection" class="content-section">
            <h2>Jak chronić się przed SSRF?</h2>
            <ul>
                <li>Walidacja i sanitizacja wejściowych adresów URL.</li>
                <li>Ograniczenie zakresu adresów, do których można wysyłać żądania (biała lista).</li>
                <li>Używanie tokenów i innych mechanizmów uwierzytelniania.</li>
                <li>Monitorowanie i logowanie wszystkich wychodzących żądań HTTP.</li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Bezpieczeństwo Aplikacji Webowych</p>
    </footer>
</body>
</html>
