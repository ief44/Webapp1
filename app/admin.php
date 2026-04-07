<?php
include_once("database.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$melding = '';
$type = '';

// Gerecht toevoegen
if ($_POST) {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $prijs = $_POST['prijs'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    if ($naam) {
        $pdo->prepare("INSERT INTO gerechten (naam, beschrijving, prijs, categorie) VALUES (?, ?, ?, ?)")
            ->execute([$naam, $beschrijving, $prijs, $categorie]);
        $melding = "✓ Gerecht '$naam' toegevoegd!";
        $type = 'succes';
    } else {
        $melding = "✕ Naam is verplicht!";
        $type = 'fout';
    }
}

// Gerecht verwijderen
if (isset($_GET['del'])) {
    $pdo->prepare("DELETE FROM gerechten WHERE id = ?")->execute([$_GET['del']]);
    $melding = "Gerecht verwijderd!";
    $type = 'succes';
}

// Uitloggen
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Alle gerechten ophalen
$gerechten = $pdo->query("SELECT * FROM gerechten ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Beheer Gerechten</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: #333;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .logout {
            background: #8B5E3C;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout:hover {
            background: #6d4630;
        }

        .melding {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .melding.succes {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .melding.fout {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 700px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #555;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f5f5f5;
            font-weight: 600;
        }

        tr:hover {
            background: #fafafa;
        }

        .del-btn {
            background: #C0392B;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .del-btn:hover {
            background: #a02416;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>📋 Beheer Gerechten</h1>
            <a href="?logout" class="logout">Uitloggen</a>
        </div>

        <?php if ($melding): ?>
            <div class="melding <?= $type ?>"> <?= $melding ?> </div>
        <?php endif; ?>

        <div class="grid">
            <!-- FORMULIER -->
            <div class="card">
                <h2>➕ Gerecht Toevoegen</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Naam *</label>
                        <input type="text" name="naam" placeholder="bijv. Pasta Carbonara" required>
                    </div>
                    <div class="form-group">
                        <label>Beschrijving</label>
                        <textarea name="beschrijving" placeholder="Korte omschrijving..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Prijs (€)</label>
                        <input type="number" name="prijs" placeholder="12.50" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Categorie</label>
                        <input type="text" name="categorie" placeholder="bijv. Vis, Vlees...">
                    </div>
                    <button type="submit">Toevoegen</button>
                </form>
            </div>

            <!-- OVERZICHT -->
            <div class="card">
                <h2>📝 Alle Gerechten (<?= count($gerechten) ?>)</h2>
                <?php if ($gerechten): ?>
                    <table>
                        <tr>
                            <th>Naam</th>
                            <th>Prijs</th>
                            <th></th>
                        </tr>
                        <?php foreach ($gerechten as $g): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($g['naam']) ?></strong>
                                    <?php if ($g['categorie']): ?><br><small style="color:#666"><?= htmlspecialchars($g['categorie']) ?></small><?php endif; ?>
                                </td>
                                <td><?= $g['prijs'] ? '€ ' . number_format($g['prijs'], 2, ',', '.') : '—' ?></td>
                                <td><a href="?del=<?= $g['id'] ?>" class="del-btn" onclick="return confirm('Zeker?')">Verwijder</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p style="color:#999; padding:20px; text-align:center;">Nog geen gerechten</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>