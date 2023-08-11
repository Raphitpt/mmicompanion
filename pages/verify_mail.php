<?php 
session_start();

require "../bootstrap.php";

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edu_mail']) && isset($_GET['verification_code_mail'])){
    $activation_code = $_GET['verification_code_mail'];
    $edu_mail = $_GET['edu_mail'];
    $sql_code = "SELECT verification_code_mail FROM users WHERE edu_mail = :edu_mail";
    $stmt_code = $dbh->prepare($sql_code);
    $stmt_code->execute([
        'edu_mail' => $edu_mail,
    ]);
    $user = $stmt_code->fetch(PDO::FETCH_ASSOC);
    if($sql_code['verification_code_mail'] == $activation_code){
        $sql = "UPDATE users SET verification_code_mail = NULL AND active = 1 WHERE edu_mail = :edu_mail";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'edu_mail' => $edu_mail
        ]);
        header('Location: ../login.php');
        exit();
    }
    else{
        header('Location: ../login.php');
        exit();
    }
}