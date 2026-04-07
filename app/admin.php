<?php
include_once("database.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$bericht = '';
$error = '';

// Gerecht toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['naam'] ?? ''))) {
    $naam = trim($_POST['naam']);
    $beschrijving = $_POST['beschrijving'] ?? '';
    $prijs = trim($_POST['prijs'] ?? '');
    $prijs = $prijs === '' ? null : (float) $prijs;
    $categorie = $_POST['categorie'] ?? '';

    try {
        $sql = "INSERT INTO Gerechten (naam, beschrijving, prijs, `type`) VALUES (?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$naam, $beschrijving, $prijs, $categorie]);
        $bericht = "✓ Gerecht toegevoegd!";
    } catch (PDOException $e) {
        $error = "Fout bij toevoegen: " . $e->getMessage();
    }
}

// Gerecht verwijderen
if (isset($_GET['verwijder']) && is_numeric($_GET['verwijder'])) {
    $id = (int) $_GET['verwijder'];
    try {
        $sql = "DELETE FROM Gerechten WHERE id = ?";
        $pdo->prepare($sql)->execute([$id]);
        $bericht = "✓ Gerecht verwijderd!";
    } catch (PDOException $e) {
        $error = "Fout bij verwijderen: " . $e->getMessage();
    }
}

// Zoeken
$zoek = $_GET['zoek'] ?? '';
$sql = "SELECT * FROM Gerechten";
$params = [];

if ($zoek) {
    $sql .= " WHERE naam LIKE ? OR beschrijving LIKE ? OR `type` LIKE ?";
    $params = ["%$zoek%", "%$zoek%", "%$zoek%"]; 
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$gerechten = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerechten Beheer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 20px; }
        .header { background: #333; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .logout { background: #d9534f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px; }
        .logout:hover { background: #c9302c; }
        .bericht { background: #d4edda; color: #155724; padding: 12px; border-radius: 3px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #842029; padding: 12px; border-radius: 3px; margin-bottom: 15px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h2 { font-size: 18px; margin-bottom: 15px; color: #333; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #ddd; border-radius: 3px; font-family: Arial; }
        button { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; width: 100%; }
        button:hover { background: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .del { background: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .del:hover { background: #c9302c; }
        .search-bar { margin-bottom: 15px; display: flex; gap: 10px; }
        .search-bar input { flex: 1; }
        .search-bar button { flex: 0; width: auto; }
        .search-bar a { padding: 8px 15px; display: flex; align-items: center; background: #333; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📋 Admin Panel</h1>
        <a href="?logout" class="logout">Uitloggen</a>
    </div>

    <?php if ($bericht): ?>
        <div class="bericht"><?= htmlspecialchars($bericht) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="grid">
        <!-- TOEVOEGEN -->
        <div class="card">
            <h2>Gerecht Toevoegen</h2>
            <form method="POST">
                <label>Naam *</label>
                <input type="text" name="naam" required>

                <label>Beschrijving</label>
                <textarea name="beschrijving" rows="3"></textarea>

                <label>Prijs (€)</label>
                <input type="number" name="prijs" step="0.01">

                <label>Categorie</label>
                <input type="text" name="categorie">

                <button type="submit">Toevoegen</button>
            </form>
        </div>

        <!-- OVERZICHT -->
        <div class="card">
            <h2>Alle Gerechten (<?= count($gerechten) ?>)</h2>
            
            <form method="GET" class="search-bar">
                <input type="text" name="zoek" placeholder="Zoeken..." value="<?= htmlspecialchars($zoek) ?>">
                <button type="submit">Zoeken</button>
                <a href="admin.php">Reset</a>
            </form>

            <?php if ($gerechten): ?>
                <table>
                    <tr>
                        <th>Naam</th>
                        <th>Categorie</th>
                        <th>Prijs</th>
                        <th></th>
                    </tr>
                    <?php foreach ($gerechten as $g): ?>
                        <tr>
                            <td><?= htmlspecialchars($g['naam']) ?></td>
                            <td><?= htmlspecialchars($g['type'] ?? '-') ?></td>
                            <td>€ <?= number_format($g['prijs'] ?? 0, 2, ',', '.') ?></td>
                            <td><a href="?verwijder=<?= $g['id'] ?>" class="del" onclick="return confirm('Verwijderen?')">Del</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Geen gerechten gevonden.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
