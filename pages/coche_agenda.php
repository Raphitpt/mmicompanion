<!-- Script utilisé dans le fichier agenda.php pour mettre à jour la valeur d'une coche -->
<?php
require "../bootstrap.php";
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

$idAgenda = $_POST['idAgenda'];
$checkedValue = $_POST['checked'];
$idUser = $_POST['id_user'];

  if ($checkedValue == 0){
    unCheckEvent($idAgenda, $idUser);
  } else {
    checkEvent($idAgenda, $idUser);
  }
    
echo "Mise à jour effectuée avec succès!";
?>