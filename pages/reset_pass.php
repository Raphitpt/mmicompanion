<?php
session_start();
require '../bootstrap.php';

if(isset($_POST['submit'])){
    if(isset($_POST['password']) && isset($_POST['password_confirm']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $edu_mail = $_POST['mail_user'];
        if($password == $password_confirm){
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password = :password, verification_code_pass = null WHERE edu_mail = :edu_mail";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'password' => $password,
                'edu_mail' => $edu_mail
            ]);

            header('Location: ./login.php');
            exit();
        }
        else{
            header('Location: ./verify_password.php');
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas !";
            exit();
        }
    }
    else {
        header('Location: ./verify_password.php');
        $_SESSION['erreur'] = "Veuillez remplir tous les champs du formulaire.";
        exit();
    }
}
