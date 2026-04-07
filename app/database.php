<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "db";
$db = "mydatabase";
$user = "user";
$password = "password";
$charset = "utf8mb4";

$opties = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $password, $opties);
} catch (PDOException $e) {
    die("Databasefout: " . $e->getMessage());
}

// Database initialisatie
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS Gerechten (
        id INT AUTO_INCREMENT PRIMARY KEY,
        naam VARCHAR(255) NOT NULL,
        beschrijving TEXT,
        prijs DECIMAL(10,2),
        `type` VARCHAR(255)
    )");

    // Voeg admin toe als deze niet bestaat
    $stmt = $pdo->prepare("INSERT IGNORE INTO Admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute(['admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
} catch (PDOException $e) {
    // Log de fout maar stop niet
    error_log("Database initialisatie fout: " . $e->getMessage());
}
?>