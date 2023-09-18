<?php 
session_start();
require "../bootstrap.php";
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_user']) && isset($_GET['id_task'])) {
    $id_user = $_GET['id_user'];
    $id_task = $_GET['id_task'];
    $sql = "DELETE FROM agenda WHERE id_task = :id_task AND id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_task' => $id_task,
        'id_user' => $id_user
    ]);
    header('Location: ../agenda.php');
    exit();
}
else{
    header('Location: ../agenda.php');
    exit();
}