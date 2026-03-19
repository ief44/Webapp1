<?php
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
 
try{
    $pdo = new PDO($dsn, $user, $password, $opties);
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die("Unable to connect to the database.");
}
 
$sql = "SELECT * FROM Gerechten";
 
$statement = $pdo->prepare($sql);
 
$statement->execute();
 
$gerechten = $statement->fetchAll();
 
echo "<pre>";
print_r($gerechten);
echo "</pre>";





