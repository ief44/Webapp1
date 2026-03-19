<?php
include_once("database.php");

$sql = "SELECT * FROM Gerechten";

$statement = $pdo->prepare($sql);

$statement->execute();

$gerechten = $statement->fetchAll();

echo "<pre>";
print_r($gerechten);
echo "</pre>";

?>
