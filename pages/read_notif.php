<?php
session_start();
require '../bootstrap.php';
$user = onConnect($dbh);

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

if ($data !== null && isset($data['notifications_ids'])) {
    foreach ($data['notifications_ids'] as $notif_id) {
        $sql_check = "SELECT COUNT(*) FROM read_notif WHERE id_user = :user_id AND id_notif = :notif_id";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindValue(':user_id', $user['id_user'], PDO::PARAM_INT);
        $query_check->bindValue(':notif_id', $notif_id, PDO::PARAM_INT);
        $query_check->execute();

        $result = $query_check->fetchColumn();

        if ($result == 0) {
            $sql_insert = "INSERT INTO read_notif (id_user, id_notif) VALUES (:user_id, :notif_id)";
            $query_insert = $dbh->prepare($sql_insert);
            $query_insert->bindValue(':user_id', $user['id_user'], PDO::PARAM_INT);
            $query_insert->bindValue(':notif_id', $notif_id, PDO::PARAM_INT);

            $sql_update = "UPDATE users SET notif_message = 0 WHERE id_user = :id_user";
            $query_update = $dbh->prepare($sql_update);
            $query_update->bindValue(':id_user', $user['id_user'], PDO::PARAM_INT);
            $query_update->execute();


            if ($query_insert->execute()) {
                echo $notif_id;
            } else {
                $errorInfo = $query_insert->errorInfo();
                echo "Erreur lors de l'insertion : " . $errorInfo[2];
            }
        } else {
            echo "Erreur : La notification a déjà été lue.";
        }
    }
} else {
    echo "Erreur : Aucune donnée valide reçue.";
}
