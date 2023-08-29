<?php
session_start();
require "../bootstrap.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    var_dump($_POST['email']);
    if (isset($_POST['email'])) {
        $pass_code = generate_activation_code();
        $email = $_POST['email'];
        $sql = "UPDATE users SET verification_code_pass = :pass_code WHERE edu_mail = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'pass_code' => $pass_code,
            'email' => $email
        ]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['mail_message'] = "Un mail vient d'être envoyé à $email pour réinitialiser ton mot de passe.";
            send_reset_password($email, $pass_code);
            var_dump($pass_code);
        } else {
            $_SESSION['mail_message'] = "L'email $email n'est pas enregistré dans notre base de données.";
        }
        var_dump($_SESSION);
    }
}
?>
