<?php
session_start();
require "../bootstrap.php";

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
}

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// Obligatoire pour afficher la page
echo head("MMI Companion - Outils supplémentaires");

?>

<body class="body-all">
    <!-- Menu de navigation -->
    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header" id="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Outils supplémentaires</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent() ?>
    </header>

    <main>
        
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_outils-supplementaires-header');
        select_background_profil.classList.add('select_link-header');
    </script>
</body>
</html>