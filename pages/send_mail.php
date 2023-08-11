<?php 
session_start();
require '../bootstrap.php';

$mail_user = strip_tags($_POST['mail_user']);
$verif_code = strip_tags($_POST['verif_code']);

send_activation_email($mail_user, $verif_code);
exit();
?>
