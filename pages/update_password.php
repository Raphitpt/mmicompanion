<?php
session_start();
require "../bootstrap.php";

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français

$error_password = "";
$success_password = "";

if (isset($_POST['password'])) {
    $old_password = strip_tags($_POST['old_password']);
    $password = strip_tags($_POST['password']);
    $confirm_password = strip_tags($_POST['confirm_password']);

    $sql = "SELECT password FROM users WHERE id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_user' => $users['id_user']
    ]);
    $password_hash = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($old_password, $password_hash['password'])) {
        $error_password = "L'ancien mot de passe est incorrect !";
    } else if ($password != $confirm_password) {
        $error_password = "Les mots de passe ne correspondent pas !";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE id_user = :id_user";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'password' => $password,
            'id_user' => $users['id_user']
        ]);
        $success_password = "Mot de passe modifié avec succès !";

    }
    
    $_SESSION['success_password'] = $success_password;
    $_SESSION['error_password'] = $error_password;

    Header('Location: ./profil.php');
    exit;
    
}