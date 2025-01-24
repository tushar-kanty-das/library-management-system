<?php
// Database connection details
$host = "localhost";   // Database host
$username = "root";     // Database username
$password = "";         // Database password
$databaseName = "tushar"; // Database name

// Create a new MySQLi connection
$database = new mysqli($host, $username, $password, $databaseName);

// Check if the connection was successful
if ($database->connect_error) {
    // Output a user-friendly message and log the error internally (do not expose sensitive information)
    error_log("Connection failed: " . $database->connect_error);  // Log the error to the server logs
    die("Connection failed. Please try again later.");  // Show a generic message to the user
} else {
    // Set the character set to UTF-8 to handle international characters
    $database->set_charset("utf8");
}

// You can now use $database to interact with the database

?>
