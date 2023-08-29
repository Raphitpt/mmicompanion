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
                <form method="POST" class="form-login" action="./send_password.php">
                    <input type="text" name="email" placeholder="email" id="email" class="input-login" required>
                    <div style="height:20px"></div>
                    <input type="submit" name="submit" value="Envoyer le lien de récuperation" class="button_register">
                    <div style="height:15px"></div>
                    <div class="error_message-login"></div>
                </form>
            </div>
        </div>
    </div>
</main>

