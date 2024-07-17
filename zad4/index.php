<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grecenzenci</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Grecenzenci</h1>
        <br>
        <nav>
            <ul>
                <li><a href="index.php?page=home" class="button">Home</a></li>
                <li><a href="index.php?page=about" class="button">O nas</a></li>
                <li><a href="index.php?page=contact" class="button">Kontakt</a></li>
            </ul>
        </nav>
    </header>

    <main>
    <?php
    $allowed_directory = '';

    function sanitizePageParameter($page) {
        return preg_replace('/[^a-zA-Z0-9-_]/', '', $page);
    }
    if(isset($_GET['page'])) {
        $requested_page = sanitizePageParameter($_GET['page']);
        $file_path = $allowed_directory . $requested_page . '.php';
        
        if(file_exists($file_path) && is_file($file_path) && strpos(realpath($file_path), realpath($allowed_directory)) === 0) {
            require($file_path);
        } else {
            echo "Nieprawidłowa strona.";
        }
    } else {
        require($allowed_directory . 'home.php');
    }
// if(isset($_GET['page'])) {
//     $page = $_GET['page'];
//     if(file_exists($page)) {
//         include($page);
//     } elseif (in_array($page, ['home', 'about', 'contact']) && file_exists($page . ".php")) {
//         include($page . ".php");
//     } 
//     }
//  else {
//     include("home.php");
// }
?>
    </main>

    <footer>
        &copy; 2024 Grecenzenci
    </footer>
</body>
</html>