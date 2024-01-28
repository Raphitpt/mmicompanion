<?php
session_start();
require "../bootstrap.php";
$user = onConnect($dbh);

$user_sql = userSQL($dbh, $user);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_user']) && isset($_GET['id_task'])) {
    $id_user = $_GET['id_user'];
    $id_task = $_GET['id_task'];
    $sql = "DELETE FROM agenda WHERE id_task = :id_task AND id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_task' => $id_task,
        'id_user' => $id_user
    ]);

    if (str_contains($user_sql['role'], 'prof')) {
        header('Location: ../agenda_prof.php?but=' . $_GET['but'] . '&tp=' . $_GET['tp']);
    } else {
        header('Location: ../agenda.php');
    }
    exit();
} else {
    if (str_contains($user_sql['role'], 'prof')) {
        header('Location: ../agenda_prof.php?but=' . $_GET['but'] . '&tp=' . $_GET['tp']);
    } else {
        header('Location: ../agenda.php');
    }
    exit();
}
