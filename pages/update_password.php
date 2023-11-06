<?php
session_start();
require "../bootstrap.php";

use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
$user = onConnect($dbh);
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
        'id_user' => $user['id_user']
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
            'id_user' => $user['id_user']
        ]);
        $uuid = Uuid::uuid4();
        $session_id = $uuid->toString();
        $sql_session = "UPDATE sessions SET session_id = :session_id WHERE user_id = :user_id AND session_id = :session_id_old";
        $stmt_session = $dbh->prepare($sql_session);
        $stmt_session->execute([
            'session_id' => $session_id,
            'user_id' => $user['id_user'],
            'session_id_old' => $user['session_id']
        ]);
        // update existant JWT token with new session_id
        $payload = [
            'id_user' => $user['id_user'],
            'pname' => $user['pname'],
            'name' => $user['name'],
            'edu_group' => $user['edu_group'],
            'edu_mail' => $user['edu_mail'],
            'role' => $user['role'],
            'session_id' => $session_id,
        ];
        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        setcookie('jwt', $jwt, time() + (86400 * 260), "/", "", false, true);

        $sql_delete = "DELETE FROM sessions WHERE user_id = :user_id AND session_id != :session_id";
        $stmt_delete = $dbh->prepare($sql_delete);
        $stmt_delete->execute([
            'user_id' => $user['id_user'],
            'session_id' => $session_id
        ]);

        $success_password = "Mot de passe modifié avec succès !";

    }
    
    $_SESSION['success_password'] = $success_password;
    $_SESSION['error_password'] = $error_password;

    Header('Location: ./profil.php');
    exit;
    
}