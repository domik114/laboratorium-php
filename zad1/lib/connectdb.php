<?php
function connectdb($database = NULL)
{
    // Modify these settings according to your XAMPP configuration
    $host = 'localhost';
    $port = '3306';
    $username = 'root';
    $password = ''; // Default password is often empty in XAMPP
    $database = $database ? $database : 'coffee';

    try {
        $db = mysqli_connect($host, $username, $password, $database, $port);
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
    echo "Connected successfully!";
    // Perform your database operations here
    mysqli_close($conn); // Close the connection when done
} else {
    echo "Failed to connect to the database.";
}
?>
