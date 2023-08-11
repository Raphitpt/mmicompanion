<?php 
session_start();
require '../bootstrap.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['mail_user']) && isset($_POST['verif_code'])){
        $mail_user = strip_tags($_POST['mail_user']);
        $verif_code = strip_tags($_POST['verif_code']);
        send_activation_email($mail_user, $verif_code);
    }
}