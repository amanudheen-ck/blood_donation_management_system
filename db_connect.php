<?php
// Database configuration
$servername = "localhost"; // Your server (keep localhost for XAMPP)
$username = "root";        // Default XAMPP username
$password = "";            // Default XAMPP password is empty
$dbname = "blood_donation"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
