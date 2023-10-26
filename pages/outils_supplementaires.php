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
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);


// Obligatoire pour afficher la page
echo head("MMI Companion | Outils supplémentaires");

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

        <?php generateBurgerMenuContent($user_sql['role']) ?>

        <img class="img_halloween-header" src="./../assets/img/araignee.webp" alt="">
    </header>

    <main class="main-outils">
        <div style="height:30px"></div>
        <div class="container-outils">
            <a href="https://zimbra.univ-poitiers.fr" target="_blank">
                <div class="item-outils red">
                    <div class="item_flextop-outils">
                        <h1>Messagerie
                        <br>(webmail)
                        </h1>
                        <img src="./../assets/img/messagerie.webp" alt="Une personne envoyant un email">
                    </div>
                    <div class="item_flexbottom-outils">
                        <p>Ta messagerie de l’université de Poitiers</p>
                    </div>
                </div>
            </a>
            <a href="https://cas.univ-poitiers.fr/cas/login?service=https://ent.univ-poitiers.fr/uPortal/Login" target="_blank">
                <div class="item-outils purple">
                    <div class="item_flextop-outils">
                        <h1>ENT</h1>
                        <img src="./../assets/img/ENT.webp" alt="Une personne qui travaille">
                    </div>
                    <div class="item_flexbottom-outils">
                        <p>Ton espace numérique de travail</p>
                    </div>
                </div>
            </a>
            <a href="https://auth.univ-poitiers.fr/cas/login?service=https%3A%2F%2Fupdago.univ-poitiers.fr%2Flogin%2Findex.php%3FauthCAS%3DCAS" target="_blank">
                <div class="item-outils orange">
                    <div class="item_flextop-outils updago_img">
                        <h1>UPdago</h1>
                        <img src="./../assets/img/UPdago.webp" alt="Logo de UPdago">
                    </div>
                    <div class="item_flexbottom-outils">
                        <p>Ta plateforme d’enseignement en ligne</p>
                    </div>
                </div>
            </a>
            
        </div>
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_outils-supplementaires-header');
        select_background_profil.classList.add('select_link-header');
    </script>
</body>
</html>
