<?php
include_once("database.php");

$zoek = $_GET['zoek'] ?? '';
$sql = "SELECT * FROM Gerechten";
$params = [];

if ($zoek) {
    $sql .= " WHERE naam LIKE ? OR beschrijving LIKE ? OR `type` LIKE ?";
    $params = ["%$zoek%", "%$zoek%", "%$zoek%"]; 
}

$statement = $pdo->prepare($sql);
$statement->execute($params);

$gerechten = $statement->fetchAll();

echo "<pre>";
print_r($gerechten);
echo "</pre>";

?>
