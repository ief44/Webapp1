<?php include_once("database.php");

$zoek = $_GET['zoek'] ?? '';
$params = $zoek ? ["%$zoek%", "%$zoek%", "%$zoek%"] : [];
$sql = "SELECT * FROM Gerechten" . ($zoek ? " WHERE naam LIKE ? OR beschrijving LIKE ? OR type LIKE ?" : "");

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$gerechten = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menukaart – Eethuis IEF</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="header-inner">
    <a class="logo" href="index.php">Eethuis <span>IEF</span></a>
    <form class="search-form" action="menu.php" method="get">
      <input type="text" name="zoek" placeholder="Zoeken…" value="<?= htmlspecialchars($zoek) ?>">
      <button type="submit">Zoeken</button>
    </form>
    <a class="btn-login" href="login.php">🔐 Inloggen</a>
  </div>
</header>

<nav>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="menu.php" class="active">Menukaart</a></li>
    <li><a href="order.php">Bestellen</a></li>
    <li><a href="contact.php">Contact</a></li>
  </ul>
</nav>

<div class="section">
  <h2 class="section-title">Menukaart</h2>
  <p><?= count($gerechten) ?> gerechten</p>

  <?php if ($gerechten): ?>
    <div class="menu-grid">
      <?php foreach ($gerechten as $g): ?>
        <div class="menu-card">
          <div class="card-body">
            <h3><?= htmlspecialchars($g['naam']) ?></h3>
            <?php if ($g['beschrijving']): ?>
              <p><?= htmlspecialchars($g['beschrijving']) ?></p>
            <?php endif; ?>
          </div>
          <div class="card-footer">
            <span class="price">€ <?= $g['prijs'] ? number_format($g['prijs'], 2, ',', '.') : '-' ?></span>
            <?php if ($g['type']): ?>
              <span style="font-size: 12px; color: #666;"><?= htmlspecialchars($g['type']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Geen gerechten gevonden.</p>
  <?php endif; ?>
</div>

<footer>
  <p>&copy; 2024 Eethuis IEF</p>
</footer>

</body>
</html>
    </div>

  </div>

  <!-- DRANKEN -->
  <h3 class="menu-section-heading">🥤 Dranken</h3>
  <div class="menu-grid">

    <div class="menu-card">
      <div class="card-thumb drink-thumb">🍷</div>
      <div class="card-body">
        <span class="tag">Drank</span>
        <h3>Huiswijn Wit</h3>
        <p>Frisse Sauvignon Blanc, per glas</p>
      </div>
      <div class="card-footer">
        <span class="price">€ 5,50</span>
        <a href="order.php" class="btn-add">Bestellen →</a>
      </div>
    </div>

    <div class="menu-card">
      <div class="card-thumb drink-thumb">🍺</div>
      <div class="card-body">
        <span class="tag">Drank</span>
        <h3>Ijskoffie</h3>
        <p>Flesje 33cl, lekker gekoeld</p>
      </div>
      <div class="card-footer">
        <span class="price">€ 3,50</span>
        <a href="order.php" class="btn-add">Bestellen →</a>
      </div>
    </div>

    <div class="menu-card">
      <div class="card-thumb drink-thumb">🍹</div>
      <div class="card-body">
        <span class="tag">Drank</span>
        <h3>Zeebries Cocktail</h3>
        <p>Gin-tonic met citroen en zeekruid</p>
      </div>
      <div class="card-footer">
        <span class="price">€ 8,50</span>
        <a href="order.php" class="btn-add">Bestellen →</a>
      </div>
    </div>
<!-- Einde Dranken-->
    <div class="menu-card">
      <div class="card-thumb drink-thumb">💧</div>
      <div class="card-body">
        <span class="tag">Drank</span>
        <h3>Spa Blauw / Rood</h3>
        <p>Still of bruisend water, 50cl</p>
      </div>
      <div class="card-footer">
        <span class="price">€ 2,50</span>
        <a href="order.php" class="btn-add">Bestellen →</a>
      </div>
    </div>

    <div class="menu-card">
      <div class="card-thumb drink-thumb">🍊</div>
      <div class="card-body">
        <span class="tag">Drank</span>
        <h3>Vers Geperst Sinaasappelsap</h3>
        <p>100% vers, groot glas</p>
      </div>
      <div class="card-footer">
        <span class="price">€ 4,00</span>
        <a href="order.php" class="btn-add">Bestellen →</a>
      </div>
    </div>

  </div>
</div>
<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-logo">Eethuis <span>IEF</span></div>
      <p>Het fijnste visrestaurant van Zeeland. Dagverse vis, bereid met passie en traditie.</p>
    </div>
    <div>
      <h4>Locatie</h4>
      <ul>
        <li>Havenstraat 42</li>
        <li>1234 AB Zeeland</li>
        <li>Nederland</li>
      </ul>
    </div>
    <div>
      <h4>Contact &amp; Tijden</h4>
      <ul>
        <li>📞 +31 (0)113 456 789</li>
        <li>✉️ info@eethuisief.nl</li>
        <li>Ma–Vr: 11:00–22:00</li>
        <li>Za–Zo: 10:00–23:00</li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">© 2025 Eethuis IEF · Havenstraat 42, 1234 AB Zeeland · KvK 12345678</div>
</footer>
<!--Einde footer-->
</body>
</html>