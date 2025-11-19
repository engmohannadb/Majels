<?php
// Database connection settings
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "DesignersWall";

// Create a MySQLi connection
$Connection = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$Connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: set character set
mysqli_set_charset($Connection, "utf8mb4");
?>