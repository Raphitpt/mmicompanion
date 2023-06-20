<?php
session_start();
require "../bootstrap.php";

echo head("Accueil");
?>
<body class="acc">
    <main>
        <div class="acc_illu">
            <img src="../assets/img/accueil.svg" alt="Illustration diverse">
        </div>
        <div class="acc_text">
            <h1>BIENVENUE</h1>
            <p>Ne perds plus le fil de tes études avec MMI Companion !</br> <span>Enregistre, organise, réussis !<span></p>
        </div>
        <div class="acc_btn">
            <a role="button" href="./register.php" class="register_btn">Créer un compte</a>
            <a role="button" href="./login.php" class="login_btn">Se connecter</a>
        </div>
    </main>
</body>