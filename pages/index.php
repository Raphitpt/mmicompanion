<!-- 
        Page d'accueil quand on arrive sur l'application via le QR-code ou bien en cliquant sur le lien
        On peut s'inscrire(register.php) ou se connecter(login.php)
        On peut aussi accéder à la page d'accueil en cliquant sur le logo en haut à gauche
-->

<?php
session_start();
require "../bootstrap.php";

// si le cookie n'existe pas, on redirige vers la page d'accueil
if (!isset($_COOKIE['jwt'])) {
  header('Location: ./accueil.php');
  exit;
}

// La on récupère le cookie que l'on à crée à la connection, voir login.php et fonction.php
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_data = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_data);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// On récupère le lien de l'emploi du temps de l'utilisateur via la base de données
$cal_link = calendar($user_data['edu_group']);

// On récupère les données du formulaire du tutoriel pour ajouter l'année et le tp de l'utilisateur à la base de données
if (isset($_POST['annee']) && isset($_POST['tp'])) {
  $annee = $_POST['annee'];
  $tp = $_POST['tp'];
  $update_user = "UPDATE users SET edu_group = :edu_group WHERE id_user = :id_user";
  $stmt = $dbh->prepare($update_user);
  $stmt->execute([
    'edu_group' => $annee . "-" . $tp,
    'id_user' => $user['id_user']
  ]);
  header('Location: ./index.php');
  exit();
}

$color_subjects = "SELECT * FROM sch_ressource";
$stmt = $dbh->prepare($color_subjects);
$stmt->execute();
$color_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo head('MMI Companion | Emploi du temps');
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

