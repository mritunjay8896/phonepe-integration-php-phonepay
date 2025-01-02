<?php
// Database Connection Script
$host = 'localhost';
$db = 'YOUR_DB_NAME';
$user = 'root';
$pass = ''; // Change when in Production otherwise Leave IT blank

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}




?>