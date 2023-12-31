<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $user['id_user'];
    $sql = "SELECT notif_message, notif_infos FROM users WHERE id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id_user', $id_user);
    $stmt->execute();
    $notifs = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($notifs);
}
