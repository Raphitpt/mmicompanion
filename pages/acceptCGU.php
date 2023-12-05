<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['CGU']) && !empty($_POST['CGU'])){
    $CGU = $_POST['CGU'];
    $user_sql = "SELECT * FROM users WHERE id_user = :id_user";
    $stmt = $dbh->prepare($user_sql);
    $stmt->execute([
        'id_user' => $user['id_user']
    ]);
    $user_sql = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_sql['CGU'] == 0){
        $sql = "UPDATE users SET CGU = 1 WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'id_user' => $user['id_user']
        ]);
        header('Location: ./calendar.php');
        exit;
    }
    else{
        header('Location: ./calendar.php');
        exit;
    }
}
else{
    header('Location: ./calendar.php');
    exit;
}