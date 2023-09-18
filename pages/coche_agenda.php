<!-- Script utilisé dans le fichier agenda.php pour mettre à jour la valeur d'une coche -->
<?php
require "../bootstrap.php";
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

$idAgenda = $_POST['idAgenda'];
$checkedValue = $_POST['checked'];

$sql = "UPDATE agenda SET checked = :checked WHERE id_task = :id_task";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':checked', $checkedValue, PDO::PARAM_INT);
$stmt->bindParam(':id_task', $idAgenda, PDO::PARAM_INT);
$stmt->execute();

echo "Mise à jour effectuée avec succès!";
?>