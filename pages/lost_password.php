<?php
session_start();

require "../bootstrap.php";
echo head("Mot de passe oublié");
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="title-login">Mot de passe oublié</h1>
                <div style="height:30px"></div>
                <form method="POST" class="form-login">
                    <input type="text" name="email" placeholder="email" id="email"
                        class="input-login" required>
                    <div style="height:20px"></div>
                    <input type="submit" value="Envoyer le lien de récuperation" class="button_register">
                    <div style="height:15px"></div>
                    <div class="error_message-login"></div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
if (isset($_POST['email'])){
    $pass_code = generate_activation_code();
    $email = $_POST['email'];
    $sql = "UPDATE users SET verification_code_pass = :pass_code WHERE edu_mail = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'pass_code' => $pass_code,
        'email' => $email
    ]);
    if ($stmt->rowCount() > 0){
        $_SESSION['mail_message'] = "Un mail vient d'être envoyé à $email pour réinitialiser ton mot de passe.";
        send_reset_password($email, $pass_code);
    }
    else{
        $_SESSION['mail_message'] = "L'email $email n'est pas enregistré dans notre base de données.";
    }
   
}
?>