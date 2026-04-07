<?php
include 'database.php';

// ─── GEBRUIKERS TABEL ────────────────────────────────────────────────────────
$sql_users = "CREATE TABLE IF NOT EXISTS gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// ─── GERECHTEN TABEL ─────────────────────────────────────────────────────────
$sql_gerechten = "CREATE TABLE IF NOT EXISTS gerechten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL,
    beschrijving TEXT,
    prijs DECIMAL(10,2),
    categorie VARCHAR(100),
    aangemaakt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($sql_users);
    echo "✓ Tabel 'gebruikers' aangemaakt of bestaat al.<br>";
    
    $pdo->exec($sql_gerechten);
    echo "✓ Tabel 'gerechten' aangemaakt of bestaat al.<br><br>";
    
    // Test user toevoegen
    $check = $pdo->prepare("SELECT id FROM gebruikers WHERE username = ?");
    $check->execute(['admin']);
    
    if (!$check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO gebruikers (username, password) VALUES (?, ?)");
        $stmt->execute([
            'admin',
            password_hash('password', PASSWORD_BCRYPT)
        ]);
        echo "✓ Testgebruiker 'admin' (wachtwoord: 'password') aangemaakt.<br>";
    } else {
        echo "✓ Testgebruiker 'admin' bestaat al.<br>";
    }
    
    echo "<br><strong>Setup compleet! Je kunt nu inloggen met:</strong><br>";
    echo "Gebruikersnaam: <code>admin</code><br>";
    echo "Wachtwoord: <code>password</code><br>";
    
} catch (PDOException $e) {
    echo "✗ Fout: " . $e->getMessage();
}
?>