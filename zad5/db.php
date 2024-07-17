<?php
function connectdb($database = NULL)
{
    // Modify these settings according to your XAMPP configuration
    $host = 'localhost';
    $username = 'root';
    $password = ''; // Default password is often empty in XAMPP
    $database = $database ? $database : 'login_system';

    try {
        $db = mysqli_connect($host, $username, $password, $database);
        if (!$db) {
            echo ("Connection failed: " . mysqli_connect_error());
        }
        return $db;
    } catch (Error $e) {
        echo ("Connection failed: " . $e);
    } catch (Exception $e) {
        echo ("Connection failed: " . $e);
    }
}

// Example usage:
$conn = connectdb();
if ($conn) {
    echo "";
    // Perform your database operations here
    //mysqli_close($conn); // Close the connection when done
} else {
    echo "";
}
?>
