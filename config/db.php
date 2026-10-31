<?php
// RoadGuard AI - Database Connection

$host = "localhost";
$dbname = "roadguard_db";
$username = "root";
$password = ""; // Default XAMPP setup often has an empty password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("RoadGuard AI database connection failed: " . $e->getMessage());
    http_response_code(500);
    exit("Database connection failed. Check the database configuration.");
}
?>