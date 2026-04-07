<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$opties = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
$dsn = "mysql:host=db;dbname=mydatabase;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, "user", "password", $opties);
} catch (PDOException $e) {
    die("Databasefout: " . $e->getMessage());
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Admins (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS Gerechten (id INT AUTO_INCREMENT PRIMARY KEY, naam VARCHAR(255) NOT NULL, beschrijving TEXT, prijs DECIMAL(10,2), `type` VARCHAR(255), foto VARCHAR(250))");
    $pdo->prepare("INSERT IGNORE INTO Admins (username, password_hash) VALUES (?, ?)") ->execute(['admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
} catch (PDOException $e) {
    error_log("Database initialisatie fout: " . $e->getMessage());
}
?>
