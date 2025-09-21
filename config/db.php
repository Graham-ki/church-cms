<?php
// Database credentials
$servername = "localhost";  // or "127.0.0.1"
$username   = "root";       // default username for local MySQL
$password   = "";           // often empty in local setups (e.g. XAMPP, WAMP, MAMP)
$database   = "church-cms"; // your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo '<script>alert("Database connected successfully");</script>';
?>
    