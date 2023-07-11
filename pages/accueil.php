<?php
session_start();
require "../bootstrap.php";

echo head("MMI Companion - Accueil");
?>
<body class="body-accueil">
    <main class="main-accueil">
        <div class="illustration-accueil">
            <img src="../assets/img/accueil.svg" alt="Illustration diverse">
        </div>
        <div class="title-accueil">
            <h1>BIENVENUE</h1>
            <p>Ne perds plus le fil de tes études avec MMI Companion !</br> <span style="font-weight: 600;">Enregistre, organise, réussis !<span></p>
        </div>
        <div style="height:30px"></div>
        <div class="button-accueil">
            <a role="button" href="./register.php" class="button_register">Créer un compte</a>
            <div style="height:10px"></div>
            <a role="button" href="./login.php" class="button_login">Se connecter</a>
        </div>
    </main>
</body>
</html>