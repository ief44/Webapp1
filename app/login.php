<?php
include_once("database.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM Admins WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
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
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #0b2d6f 0%, #1a56bb 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .login-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 380px;
      padding: 40px;
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .login-header h1 {
      font-size: 28px;
      color: #0b2d6f;
      margin-bottom: 8px;
    }
    
    .login-header p {
      color: #666;
      font-size: 14px;
    }
    
    .form-group {
      margin-bottom: 18px;
    }
    
    label {
      display: block;
      font-weight: 600;
      color: #182847;
      margin-bottom: 6px;
      font-size: 14px;
    }
    
    input {
      width: 100%;
      padding: 10px 12px;
      border: 1.5px solid #daeaff;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    input:focus {
      outline: none;
      border-color: #1a56bb;
      box-shadow: 0 0 0 3px rgba(26,86,187,0.1);
    }
    
    .btn-login {
      width: 100%;
      padding: 12px;
      background: #1a56bb;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 8px;
    }
    
    .btn-login:hover {
      background: #0b2d6f;
    }
    
    .error {
      background: #fee;
      border: 1px solid #fcc;
      color: #c33;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }
    
    .login-footer {
      text-align: center;
      margin-top: 16px;
      font-size: 13px;
      color: #999;
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-header">
    <h1>Beheer-Panel</h1>
    <p>Log in met je gegevens</p>
  </div>

  <?php if ($error): ?>
    <div class="error">✕ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label for="username">Gebruikersnaam</label>
      <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    
    <div class="form-group">
      <label for="password">Wachtwoord</label>
      <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn-login">Inloggen</button>
  </form>

  <div class="login-footer">
    Testgebruiker: <strong>admin</strong> / <strong>password</strong>
  </div>
</div>

</body>
</html>