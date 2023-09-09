<!-- 
        Page d'accueil quand on arrive sur l'application via le QR-code ou bien en cliquant sur le lien
        On peut s'inscrire(register.php) ou se connecter(login.php)
        On peut aussi accéder à la page d'accueil en cliquant sur le logo en haut à gauche
-->

<?php
session_start();
require "../bootstrap.php";

echo head('MMI Companion | Accueil');
?>

<body class="body-login">
    <main class="main-desktop">
        <img src="./../assets/img/PC_install.webp" alt="">
        <h1><span style="font-weight:700">Nous travaillons sur la version PC...</span><br>Mais pour le moment, je t'invite à scanner le QR Code pour accès à l'application sur ton smartphone</h1>
        <p id="btn_access">Je veux y accéder quand-même</p>
    </main>
    <main class="main-accueil">
        <div class="illustration-login">
            <img src="./../assets/img/accueil.svg" alt="Illustration diverse">
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

<script>
    let btn_access = document.querySelector("#btn_access");
    let main_accueil = document.querySelector(".main-accueil");
    let main_desktop = document.querySelector(".main-desktop");

    btn_access.addEventListener("click", () => {
        main_accueil.style.display = "flex";
        main_desktop.style.display = "none";
    })
</script>

</html>

