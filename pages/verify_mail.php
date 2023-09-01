<?php 
session_start();

require "../bootstrap.php";

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) && isset($_GET['activation_code'])){
    $activation_code = $_GET['activation_code'];
    $edu_mail = $_GET['email'];
    $sql_code = "SELECT verification_code_mail FROM users WHERE edu_mail = :edu_mail";
    $stmt_code = $dbh->prepare($sql_code);
    $stmt_code->execute([
        'edu_mail' => $edu_mail,
    ]);
    $user = $stmt_code->fetch(PDO::FETCH_ASSOC);
    if($user['verification_code_mail'] == $activation_code && $activation_code != null && $edu_mail != null && $activation_code != "" && $activation_code != 0){
        $sql = "UPDATE users SET verification_code_mail = NULL, active = 1 WHERE edu_mail = :edu_mail";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'edu_mail' => $edu_mail
        ]);
        header('Location: ./mail.php');
        exit();
    }
    else{
        header('Location: ./mail.php');
        exit();
    }
}