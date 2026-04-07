<?php
include_once("database.php");

$zoek = $_GET['zoek'] ?? '';
$params = $zoek ? ["%$zoek%", "%$zoek%", "%$zoek%"] : [];
$sql = "SELECT * FROM Gerechten" . ($zoek ? " WHERE naam LIKE ? OR beschrijving LIKE ? OR `type` LIKE ?" : "");

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$gerechten = $stmt->fetchAll();

echo "<pre>";
print_r($gerechten);
echo "</pre>";

?>
