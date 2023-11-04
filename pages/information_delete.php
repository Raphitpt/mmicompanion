<?php 
session_start();
require "../bootstrap.php";
$user = onConnect($dbh);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_user']) && isset($_GET['id_infos'])) {
    $id_user = $_GET['id_user'];
    $id_infos = $_GET['id_infos'];
    $sql = "DELETE FROM informations WHERE id_infos = :id_infos AND id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_infos' => $id_infos,
        'id_user' => $id_user
    ]);
    header('Location: ./informations.php');
    exit();
}
else{
    header('Location: ./informations.php');
    exit();
}