<?php 
session_start();
require '../bootstrap.php';

if((isset($_POST['mail_user'])) && !empty($_POST['verif_code'])){
$mail_user = $_POST['mail_user'];
$verif_code = $_POST['verif_code'];
}


send_activation_email($mail_user, $verif_code);
exit();
?>
