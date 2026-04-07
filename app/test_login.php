<?php
include 'database.php';

$username = 'admin';
$password = 'password';

$stmt = $pdo->prepare("SELECT id, username, password_hash FROM Admins WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    echo "User found: " . $user['username'] . "\n";
    echo "Hash: " . $user['password_hash'] . "\n";
    if (password_verify($password, $user['password_hash'])) {
        echo "Password correct!\n";
    } else {
        echo "Password incorrect!\n";
    }
} else {
    echo "User not found!\n";
}
?>