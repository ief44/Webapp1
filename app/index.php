<?php include_once("database.php");

$zoek = $_GET['zoek'] ?? '';
$params = $zoek ? ["%$zoek%", "%$zoek%", "%$zoek%"] : [];
$sql = "SELECT * FROM Gerechten" . ($zoek ? " WHERE naam LIKE ? OR beschrijving LIKE ? OR type LIKE ?" : "") . " LIMIT 4";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$gerechten = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eethuis IEF - Home</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="Ief eethuis.png" type="image/x-icon">
</head>
<body>

<input type="checkbox" id="nav-toggle">

<header>
  <div class="header-inner">
    <a class="logo" href="index.php">Eethuis <span>IEF</span></a>

    <form class="search-form" action="index.php" method="get">
      <input type="text" name="zoek" placeholder="Zoek gerecht…" value="<?= htmlspecialchars($zoek) ?>">
      <button type="submit">Zoeken</button>
    </form>

    <a class="btn-login" href="login.php">🔐 Inloggen</a>

    <label class="hamburger-label" for="nav-toggle">
      <span></span><span></span><span></span>
    </label>
  </div>
</header>

<nav>
  <ul class="nav-links">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="menu.php">Menukaart</a></li>
    <li><a href="order.php">Bestellen</a></li>
    <li><a href="contact.php">Contact</a></li>
  </ul>
</nav>
<!-- HERO -->
<section class="hero">
  <h1>Welkom bij<br>Eethuis <em>IEF</em></h1>
  <p>Verse vis, dagelijks binnengehaald door lokale vissers. Bereid met vakmanschap en passie voor de Zeeuwse tafel.</p>
  <a href="menu.php" class="btn-primary">Bekijk de menukaart →</a>
</section>

<!-- FEATURES -->
<div class="section">
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">🐟</div>
      <h3>Dagverse vis</h3>
      <p>Elke dag vers aangevoerd van lokale Zeeuwse vissers.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">👨‍🍳</div>
      <h3>Vakmanschap</h3>
      <p>Onze koks bereiden elk gerecht met toewijding en liefde.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🌊</div>
      <h3>Authentiek</h3>
      <p>Klassieke visrecepten met een eigentijdse Zeeuwse touch.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🍽️</div>
      <h3>Sfeervolle omgeving</h3>
      <p>Gezellig tafelen in ons maritiem ingerichte restaurant.</p>
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-box dark"><div class="stat-number">10</div><div class="stat-label">Visgerechten</div></div>
    <div class="stat-box blue"><div class="stat-number">5</div><div class="stat-label">Dranken</div></div>
    <div class="stat-box dark"><div class="stat-number">15+</div><div class="stat-label">Jaar ervaring</div></div>
    <div class="stat-box blue"><div class="stat-number">★★★★★</div><div class="stat-label">Beoordeling</div></div>
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

</body>
</html>