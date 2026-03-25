<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Eethuis IEF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();

// Database connection
require_once 'database.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT password_hash FROM Admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $login_error = "Ongeldige gebruikersnaam of wachtwoord";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle add dish
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dish']) && isset($_SESSION['admin_logged_in'])) {
    $naam = $_POST['naam'];
    $beschrijving = $_POST['beschrijving'];
    $prijs = $_POST['prijs'];
    $categorie = $_POST['categorie'];

    $stmt = $pdo->prepare("INSERT INTO Gerechten (naam, beschrijving, prijs, categorie) VALUES (?, ?, ?, ?)");
    $stmt->execute([$naam, $beschrijving, $prijs, $categorie]);
    header('Location: admin.php');
    exit;
}

// Handle delete dish
if (isset($_GET['delete']) && isset($_SESSION['admin_logged_in'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Gerechten WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin.php');
    exit;
}

// Fetch dishes
$gerechten = [];
if (isset($_SESSION['admin_logged_in'])) {
    $stmt = $pdo->query("SELECT * FROM Gerechten ORDER BY categorie, naam");
    $gerechten = $stmt->fetchAll();
}
?>

<div class="section">
    <h2 class="section-title">Admin <span>Paneel</span></h2>

    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <!-- Login Form -->
        <div class="login-form">
            <h3>Inloggen</h3>
            <?php if (isset($login_error)): ?>
                <p style="color: red;"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn-submit">Inloggen</button>
            </form>
        </div>
    <?php else: ?>
        <!-- Admin Panel -->
        <p>Welkom in het admin paneel. <a href="?logout">Uitloggen</a></p>

        <!-- Add Dish Form -->
        <h3>Gerecht Toevoegen</h3>
        <form method="post" class="add-dish-form">
            <div class="form-group">
                <label for="naam">Naam</label>
                <input type="text" id="naam" name="naam" required>
            </div>
            <div class="form-group">
                <label for="beschrijving">Beschrijving</label>
                <textarea id="beschrijving" name="beschrijving" required></textarea>
            </div>
            <div class="form-group">
                <label for="prijs">Prijs (€)</label>
                <input type="number" id="prijs" name="prijs" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="categorie">Categorie</label>
                <select id="categorie" name="categorie">
                    <option value="Visgerecht">Visgerecht</option>
                    <option value="Drankje">Drankje</option>
                    <option value="Voorgerecht">Voorgerecht</option>
                    <option value="Nagerecht">Nagerecht</option>
                </select>
            </div>
            <button type="submit" name="add_dish" class="btn-submit">Toevoegen</button>
        </form>

        <!-- List Dishes -->
        <h3>Bestaande Gerechten</h3>
        <div class="dishes-list">
            <?php foreach ($gerechten as $gerecht): ?>
                <div class="dish-item">
                    <div>
                        <strong><?php echo htmlspecialchars($gerecht['naam']); ?></strong> (<?php echo htmlspecialchars($gerecht['categorie']); ?>) - €<?php echo number_format($gerecht['prijs'], 2, ',', '.'); ?>
                        <br><small><?php echo htmlspecialchars($gerecht['beschrijving']); ?></small>
                    </div>
                    <a href="?delete=<?php echo $gerecht['id']; ?>" onclick="return confirm('Weet je zeker dat je dit gerecht wilt verwijderen?')" class="btn-delete">Verwijderen</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.login-form, .add-dish-form {
    max-width: 400px;
    margin: 0 auto;
    background: var(--white);
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 1rem;
}

.btn-submit {
    background: var(--blue);
    color: var(--white);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
}

.btn-submit:hover {
    background: var(--navy);
}

.dishes-list {
    margin-top: 2rem;
}

.dish-item {
    background: var(--white);
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: var(--radius);
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-delete {
    background: #dc3545;
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius);
    text-decoration: none;
}

.btn-delete:hover {
    background: #c82333;
}
</style>

</body>
</html>