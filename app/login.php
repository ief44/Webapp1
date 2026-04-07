<?php
include_once("database.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM Admins WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Gebruikersnaam of wachtwoord is onjuist.';
        }
    } else {
        $error = 'Vul beide velden in.';
    }
}

if (isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen – Beheer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9;">
        <h2>Inloggen</h2>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Gebruikersnaam:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <button type="submit">Inloggen</button>
        </form>
        <p>Testgebruiker: admin / password</p>
    </div>
</body>
</html>