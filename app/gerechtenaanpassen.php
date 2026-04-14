<?php
include('database.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$gerechtenId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gerechten = $pdo->prepare("UPDATE Gerechten SET naam = :naam, beschrijving = :beschrijving, prijs = :prijs, type = :type WHERE id = :id");
    $gerechten->bindParam(':naam', $_POST['naam']);
    $gerechten->bindParam(':beschrijving', $_POST['beschrijving']);
    $gerechten->bindParam(':prijs', $_POST['prijs']);
    $gerechten->bindParam(':type',$_POST['type']);
    $gerechten->bindParam(':id', $_GET['id']);
    $gerechten->execute();
    $bericht = 'Gerecht succesvol aangepast!';
}
$gerechtenOphalen = $pdo->prepare("SELECT * FROM Gerechten WHERE id = :id");
$gerechtenOphalen->bindParam(':id', $gerechtenId);
$gerechtenOphalen->execute();
$gerecht = $gerechtenOphalen->fetch();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerecht Bewerken</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .header { background: #333; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { color: white; font-size: 1.2rem; }
        .terug { background: #d9534f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px; }
        .terug:hover { background: #c9302c; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h2 { font-size: 18px; margin-bottom: 15px; color: #333; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #ddd; border-radius: 3px; font-family: Arial; }
        button { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; width: 100%; }
        button:hover { background: #555; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📋 Gerecht Bewerken</h1>
        <a href="admin.php" class="terug">← Terug</a>
    </div>

    <div class="card">
        <?php if (!empty($bericht)): ?>
            <div style="background:#d4edda;color:#155724;padding:10px;border-radius:3px;margin-bottom:15px;"><?php echo $bericht; ?></div>
        <?php endif; ?>
        <h2>Gegevens aanpassen</h2>
        <form method="POST" action="gerechtenaanpassen.php?id=<?php echo $gerechtenId; ?>">

            <label>Naam *</label>
            <input type="text" name="naam" required value="<?php echo ($gerecht['naam']); ?>">

            <label>Beschrijving</label>
            <textarea name="beschrijving" rows="3"><?php echo ($gerecht['beschrijving']); ?></textarea>

            <label>Prijs (€)</label>
            <input type="number" name="prijs" step="0.01" min="0" required value="<?php echo ($gerecht['prijs']); ?>">

            <label>Categorie</label>
            <input type="text" name="type" required value="<?php echo ($gerecht['type']); ?>">

            <button type="submit">Opslaan</button>
        </form>
    </div>
</div>
</body>
</html>
