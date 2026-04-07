<?php include_once("database.php");

// Zoeken
$zoek = $_GET['zoek'] ?? '';
$sql = "SELECT * FROM Gerechten";
$params = [];

if ($zoek) {
    $sql .= " WHERE naam LIKE ? OR beschrijving LIKE ? OR type LIKE ?";
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
  <title>Bestellen – Eethuis IEF</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="Ief eethuis.png" type="image/x-icon">
</head>
<body>

<input type="checkbox" id="nav-toggle">

<header>
  <div class="header-inner">
    <a class="logo" href="index.php">Eethuis <span>IEF</span></a>

    <form class="search-form" action="order.php" method="get">
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
    <li><a href="index.php">Home</a></li>
    <li><a href="menu.php">Menukaart</a></li>
    <li><a href="order.php" class="active">Bestellen</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="admin.php">Admin</a></li>
  </ul>
</nav>

<!-- INLOG MODAL -->
<div class="modal-overlay" id="login-modal">
  <div class="modal">
    <div class="modal-header">
      <h2>Inloggen</h2>
      <a class="modal-close" href="#">✕</a>
    </div>
    <form action="#" method="post">
      <div class="form-group">
        <label for="email">E-mailadres</label>
        <input type="email" id="email" name="email" placeholder="jouw@email.nl" required>
      </div>
      <div class="form-group">
        <label for="wachtwoord">Wachtwoord</label>
        <input type="password" id="wachtwoord" name="wachtwoord" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-submit">Inloggen</button>
    </form>
  </div>
</div>

<!-- BESTELLEN LAYOUT -->
<div class="order-layout">

  <!-- LINKS: menuoverzicht -->
  <div>
    <h2 class="section-title" style="margin-bottom:0.4rem;">Stel uw <span>bestelling</span> samen</h2>
    <p class="section-subtitle">Vul rechts het gewenste aantal in en verstuur uw bestelling.</p>

    <div class="order-table-wrap">
      <h3>🐟 Visgerechten</h3>
      <table class="order-table">
        <thead>
          <tr><th>Gerecht</th><th>Omschrijving</th><th style="text-align:right">Prijs</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>Gebakken Scholfilet</strong></td><td>Citroenboter en peterselie</td><td>€ 16,50</td></tr>
          <tr><td><strong>Garnalencocktail</strong></td><td>Verse Noordzeegarnalen, roze dressing</td><td>€ 14,00</td></tr>
          <tr><td><strong>Gerookte Zalm</strong></td><td>Huisgerookt, kappertjes en rode ui</td><td>€ 18,50</td></tr>
          <tr><td><strong>Mosselen Marinière</strong></td><td>Witte wijn en room</td><td>€ 19,00</td></tr>
          <tr><td><strong>Tonijntartaar</strong></td><td>Avocado, soja-sesamdressing</td><td>€ 21,00</td></tr>
          <tr><td><strong>Gegrilde Zeebaars</strong></td><td>Mediterraan, olijfolie en citroen</td><td>€ 23,50</td></tr>
          <tr><td><strong>Vissoep IEF</strong></td><td>Rijke bouillabaisse, huisbrood</td><td>€ 12,00</td></tr>
          <tr><td><strong>Inktvis Gegrild</strong></td><td>Knoflook en peterselie</td><td>€ 17,50</td></tr>
          <tr><td><strong>Kabeljauw in Tempura</strong></td><td>Knapperig gefrituurde kabeljauw</td><td>€ 15,50</td></tr>
          <tr><td><strong>Kreeft Thermidor</strong></td><td>Halve kreeft, kaassaus au gratin</td><td>€ 38,00</td></tr>
        </tbody>
      </table>
    </div>
<!-- Dranken -->
    <div class="order-table-wrap">
      <h3>🥤 Dranken</h3>
      <table class="order-table">
        <thead>
          <tr><th>Drank</th><th>Omschrijving</th><th style="text-align:right">Prijs</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>Huiswijn Wit</strong></td><td>Sauvignon Blanc, per glas</td><td>€ 5,50</td></tr>
          <tr><td><strong>Ijskoffie</strong></td><td>Flesje 33cl, gekoeld</td><td>€ 3,50</td></tr>
          <tr><td><strong>Zeebries cocktail</strong></td><td>Gin-tonic, citroen en zeekruid</td><td>€ 8,50</td></tr>
          <tr><td><strong>Spa Blauw / Rood</strong></td><td>Still of bruisend, 50cl</td><td>€ 2,50</td></tr>
          <tr><td><strong>Vers Sinaasappelsap</strong></td><td>100% vers geperst, groot glas</td><td>€ 4,00</td></tr>
        </tbody>
      </table>
    </div>
  </div>
<!--Einde Dranken-->
  <!-- RECHTS: bestelformulier -->
  <aside class="order-form-box">
    <h3>🧾 Uw bestelling</h3>
    <form action="contact.php" method="get">

      <p style="font-size:0.82rem;color:var(--muted);margin-bottom:1rem;">Vul het gewenste aantal in per item (0 = niet bestellen)</p>

      <div class="order-row">
        <label>🐟 Gebakken Scholfilet</label>
        <span class="row-price">€16,50</span>
        <input type="number" name="scholfilet" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🦐 Garnalencocktail</label>
        <span class="row-price">€14,00</span>
        <input type="number" name="garnalen" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🐠 Gerookte Zalm</label>
        <span class="row-price">€18,50</span>
        <input type="number" name="zalm" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🦪 Mosselen Marinière</label>
        <span class="row-price">€19,00</span>
        <input type="number" name="mosselen" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍣 Tonijntartaar</label>
        <span class="row-price">€21,00</span>
        <input type="number" name="tonijn" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🐡 Gegrilde Zeebaars</label>
        <span class="row-price">€23,50</span>
        <input type="number" name="zeebaars" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍲 Vissoep IEF</label>
        <span class="row-price">€12,00</span>
        <input type="number" name="vissoep" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🦑 Inktvis Gegrild</label>
        <span class="row-price">€17,50</span>
        <input type="number" name="inktvis" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍤 Kabeljauw Tempura</label>
        <span class="row-price">€15,50</span>
        <input type="number" name="kabeljauw" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🦞 Kreeft Thermidor</label>
        <span class="row-price">€38,00</span>
        <input type="number" name="kreeft" value="0" min="0" max="20">
      </div>

      <hr class="order-divider">

      <div class="order-row">
        <label>🍷 Huiswijn Wit</label>
        <span class="row-price">€5,50</span>
        <input type="number" name="wijn" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍺 Ijs koffee </label>
        <span class="row-price">€3,50</span>
        <input type="number" name="bier" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍹 Zeebries Cocktail</label>
        <span class="row-price">€8,50</span>
        <input type="number" name="cocktail" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>💧 Spa Blauw / Rood</label>
        <span class="row-price">€2,50</span>
        <input type="number" name="spa" value="0" min="0" max="20">
      </div>
      <div class="order-row">
        <label>🍊 Sinaasappelsap</label>
        <span class="row-price">€4,00</span>
        <input type="number" name="sap" value="0" min="0" max="20">
      </div>

      <hr class="order-divider">
<!-- Tafelnummer en opmerking -->
      <div class="form-group" style="margin-top:0.5rem;">
        <label for="tafel">Tafelnummer</label>
        <input type="number" id="tafel" name="tafel" placeholder="bijv. 5" min="1" required>
      </div>
      <div class="form-group">
        <label for="opmerking">Opmerking</label>
        <input type="text" id="opmerking" name="opmerking" placeholder="Allergieën, wensen…">
      </div>
<!--einde tafelnummer en opmerking-->
      <button type="submit" class="btn-submit" style="margin-top:0.5rem;">Bestelling plaatsen →</button>
    </form>
  </aside>

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
        <li>📞 +31 (0)713 486 752</li>
        <li>✉️ info@eethuisief.nl</li>
        <li>Ma–Vr: 11:00–22:00</li>
        <li>Za–Zo: 10:00–23:00</li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">© 2026 Eethuis IEF · Havenstraat 42, 3204 AB Zeeland · KvK 58493092</div>
</footer>
<!--Einde footer-->
</body>
</html>
