<?php
// config.php - Database configuration and connection

$host = 'localhost'; // Change if needed
$dbname = 'todo_app'; // Database name
$username = 'root'; // MySQL username
$password = ''; // MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>