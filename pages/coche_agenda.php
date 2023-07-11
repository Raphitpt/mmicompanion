<?php
require "../bootstrap.php";

$idAgenda = $_POST['idAgenda'];
$checkedValue = $_POST['checked'];

$sql = "UPDATE agenda SET checked = :checked WHERE id_task = :id_task";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':checked', $checkedValue, PDO::PARAM_INT);
$stmt->bindParam(':id_task', $idAgenda, PDO::PARAM_INT);
$stmt->execute();

echo "Mise à jour effectuée avec succès!";
?>