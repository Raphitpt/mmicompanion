<?php
session_start();

require "../bootstrap.php";
echo head("MMI Companion - Mot de passe oublié");


?>

<body class="body-login">
    <a href="./login.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>  
    <main class="main-login">
        <div class="illustration-login">
            <img src="../assets/img/forgot_password.svg" alt="Illustration diverse">
        </div>
        <h1 class="title-login">Mot de passe oublié</h1>
        <div style="height:30px"></div>
        <form method="POST" class="form-login" action="./send_password.php">
            <input type="text" name="email" placeholder="ton email" id="email" class="input-login" required>
            <div style="height:20px"></div>
            <input type="submit" name="submit" value="Envoyer" class="button_register">
            <div style="height:15px"></div>
            <div class="error_message-login"></div>
        </form>
    </main>
</body>

</html>

