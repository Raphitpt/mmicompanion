<?php 
session_start();
require "../bootstrap.php";
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_user']) && isset($_GET['id_event'])) {
    $id_user = $_GET['id_user'];
    $id_event = $_GET['id_event'];
    $sql = "DELETE FROM calendar_event WHERE id_event = :id_event AND id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_event' => $id_event,
        'id_user' => $id_user
    ]);
    header('Location: ./calendar.php');
    exit();
}
else{
    header('Location: ./calendar.php');
    exit();
}