<?php

/*

    Fichier : /Helpers/functions.php
 */
require __DIR__ . '/../vendor/autoload.php';



/**
 * Retourne le contenu HTML du bloc d'en tête d'une page.
 * Deux CSS sont automatiquement intégré :
 *   - pico.css
 *   - custom.css
 *
 * @param string title le titre de la page.
 * @return string
 */
function head(string $title = ''): string
{
    return  <<<HTML_HEAD
<!DOCTYPE html>
<html lang="fr">

<head>
<!-- Google tag (gtag.js) -->

<script async src="https://www.googletagmanager.com/gtag/js?id=G-FX70LE2MCM"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FX70LE2MCM');
</script>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/svg" href="../assets/img/mmicompanion_512.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../assets/css/style.css?v=2.03" rel="stylesheet"">
  <link href="../assets/css/responsive.css" rel="stylesheet"">
  <link href="../assets/css/uicons-bold-rounded.css" rel="stylesheet"">
  <link rel="manifest" href="../manifest.webmanifest" />
  <script async src="https://unpkg.com/pwacompat" crossorigin="anonymous"></script>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  
  <script src="./../assets/js/jquery-3.7.1.min.js"></script>

<link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_14_Pro_Max_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_14_Pro_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/iPhone_11__iPhone_XR_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" href="../splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/12.9__iPad_Pro_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/11__iPad_Pro__10.5__iPad_Pro_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/10.9__iPad_Air_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/10.5__iPad_Air_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/10.2__iPad_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="../splash_screens/8.3__iPad_Mini_landscape.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_14_Pro_Max_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_14_Pro_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/iPhone_11__iPhone_XR_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" href="../splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/12.9__iPad_Pro_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/11__iPad_Pro__10.5__iPad_Pro_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/10.9__iPad_Air_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/10.5__iPad_Air_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/10.2__iPad_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="../splash_screens/8.3__iPad_Mini_portrait.png">
  <title>$title</title>
</head>
HTML_HEAD;
}
function findTrigramme($profName)
{
    $profs = [
        "Mehrez Hanen" => "HMEH",
        "Barré Marielle" => "MBA",
        "Bachir Smail" => "SBA",
        "Badulescu Cristina" => "CBA",
        "Calou Bastien" => "BCA",
        "Calvez Yann" => "YCAL",
        "Chaulet Bernadette" => "BCH",
        "Couegnas Carole" => "CCO",
        "Burn Jean Baptiste" => "JBB",
        "Daghmi Fathallah" => "FDA",
        "Domont Éric" => "EDO",
        "Henry Simon" => "SHE",
        "Glénison Émilie" => "EGL",
        "Galonnier Didier" => "DGA",
        "Delayre Stéphanie" => "SDE",
        "Gineste Olivier" => "OGI",
        "Gourceau Carine" => "CGOU",
        "Hénin Jean Luc" => "JLH",
        "Henry Yann" => "YHE",
        "Le folgoc Cyrille" => "CFO",
        "Louet François" => "FLT",
        "Bui Quoc Marion" => "MBUI",
        "Vallade Christophe" => "CVAL",
        "Scutella Soline" => "SSCU",
        "Combot Mathilde" => "MCOMB",
        "Sulaiman Hamid" => "HSUL",
        "Chapeau Julie" => "JCHA",
        "Poyrault Matthieu" => "MPOY",
        "Brunie Julia" => "JBRU",
        "Cauvin-Doumic Frédérique" => "FCAU",
        "Hautot Adrian" => "AHAU"
    ];


    $search_term = $profName;

    $found_professors = [];

    foreach ($profs as $name => $code) {
        if (stripos($name, $search_term) !== false) {
            $found_professors[$name] = $code;
        }
    }

    if (!empty($found_professors)) {
        // Afficher les codes associés aux professeurs trouvés
        foreach ($found_professors as $name => $code) {
            echo "Nom : $name, Code : $code<br>";
        }
        return $code;
    } else {
        echo "undefined";
        return null;
    }
}



function generateBurgerMenuContent($role, $title)
{

    $menuHtml = '
    <header>
    <div class="content_header">
        <div class="content_title-header">
            <div class="burger-header" id="burger-header">
                <i class="fi fi-br-bars-sort"></i>
                <img class="img_bonnet_noel-header" src="./../assets/img/bonnet_noel.svg" alt="">
            </div>
            <div style="width:20px"></div>
            <h1>'.$title.'</h1>
        </div>
        <img class="img_neige-header" src="./../assets/img/neige.svg" alt="">
        <img class="img_guirlande-header" src="./../assets/img/guirlande.svg" alt="">
    </div>

    <div class="burger_content-header" id="burger_content-header">
        <div style="height:60px"></div>
        <div class="burger_content_title-header">
            <div class="burger_content_titleleft-header">
                <img src="./../assets/img/mmicompanion_noel.webp" alt="Logo de MMI Comapanion">
                <h1>MMI Companion</h1>
            </div>
            <div class="burger_content_titleright-header burger-header" id="close_burger-header">
                <i class="fi-br-cross-small"></i>
            </div>
        </div>
        <div class="burger_content_content-header">
            <div class="burger_content_trait_header"></div>
            <a href="./calendar.php">
                <div class="burger_content_link-header">
                    <i class="fi fi-br-calendar-lines"></i>
                    <p>Emploi du temps</p>
                    <div id="select_background_calendar-header" class=""></div>
                </div>
            </a>';

    // Ajouter le lien "Agenda" en fonction du rôle
    if ($role == "prof") {
        $menuHtml .= '
        <a href="./agenda_prof.php">
            <div class="burger_content_link-header">
                <i class="fi fi-br-book-bookmark"></i>
                <p>Agenda</p>
                <div id="select_background_agenda-header" class=""></div>
            </div>
        </a>';
    } else {
        $menuHtml .= '
        <a href="./agenda.php">
            <div class="burger_content_link-header">
                <i class="fi fi-br-book-bookmark"></i>
                <p>Agenda</p>
                <div id="select_background_agenda-header" class=""></div>
            </div>
        </a>';
    }
    if ($role == "prof" || $role == "autre") {
        $menuHtml .= '
        <div class="burger_content_trait_header"></div>
                <div class="burger_content_link-header burger_disabled">
                    <i class="fi fi-br-book-alt"></i>
                    <p>Scolarité (bêta)</p>
                    <div id="select_background_vie_sco-header" class=""></div>
                </div>';
    } else {
        $menuHtml .= '
        <div class="burger_content_trait_header"></div>
            <a href="./absences.php">
                <div class="burger_content_link-header">
                    <i class="fi fi-br-book-alt"></i>
                    <p>Scolarité (bêta)</p>
                    <div id="select_background_vie_sco-header" class=""></div>
                </div>
            </a>';
    }
    if ($role == "autre") {
        $menuHtml .= '
                <div class="burger_content_link-header burger_disabled">
                    <i class="fi fi-br-info"></i>
                    <p>Informations</p>
                    <div id="select_background_informations-header" class=""></div>
                </div>';
    } else {
        $menuHtml .= '
            <a href="./informations.php">
                <div class="burger_content_link-header">
                    <i class="fi fi-br-info"></i>
                    <p>Informations</p>
                    <div id="select_background_informations-header" class=""></div>
                </div>
            </a>';
    }
    $menuHtml .= '
            <a href="./outils_supplementaires.php">
                <div class="burger_content_link-header">
                    <i class="fi fi-br-link-alt"></i>
                    <p>Outils supplémentaires</p>
                    <div id="select_background_outils-supplementaires-header" class=""></div>
                </div>
            </a>
            <div class="burger_content_trait_header"></div>
            <a href="./profil.php">
                <div class="burger_content_link-header">
                    <i class="fi fi-br-user"></i>
                    <p>Mon profil</p>
                    <div id="select_background_profil-header" class=""></div>
                </div>
            </a>
            <div class="burger_content_trait_header"></div>
            <a href="./logout.php">
                <div class="burger_content_link-header logout-header">
                    <i class="fi fi-br-delete-user"></i>
                    <p>Se déconnecter</p>
                </div>
            </a>
        </div>
    </div>
    </header>';

    echo $menuHtml; // Affiche le menu HTML
}




/**
 * Retourne vrai si la méthode d'appel est GET.
 */

function isGetMethod(): bool
{
    return ($_SERVER['REQUEST_METHOD'] === 'GET');
}

/**
 * Retourne vrai si la méthode d'appel est POST.
 */
function isPostMethod(): bool
{
    return ($_SERVER['REQUEST_METHOD'] === 'POST');
}

function calendar($group = '')
{
    $calendar_link = '';
    if ($group == "BUT1-TP1") {
        $calendar_link = './../backup_cal/BUT1-TP1.ics';
    }
    if ($group == "BUT1-TP2") {
        $calendar_link = './../backup_cal/BUT1-TP2.ics';
    }
    if ($group == "BUT1-TP3") {
        $calendar_link = './../backup_cal/BUT1-TP3.ics';
    }
    if ($group == "BUT1-TP4") {
        $calendar_link = './../backup_cal/BUT1-TP4.ics';
    }
    if ($group == "BUT2-TP1") {
        $calendar_link = './../backup_cal/BUT2-TP1.ics';
    }
    if ($group == "BUT2-TP2") {
        $calendar_link = './../backup_cal/BUT2-TP2.ics';
    }
    if ($group == "BUT2-TP3") {
        $calendar_link = './../backup_cal/BUT2-TP3.ics';
    }
    if ($group == "BUT2-TP4") {
        $calendar_link = './../backup_cal/BUT2-TP4.ics';
    }
    if ($group == "BUT3-TP1") {
        $calendar_link = './../backup_cal/BUT3-TP1.ics';
    }
    if ($group == "BUT3-TP2") {
        $calendar_link = './../backup_cal/BUT3-TP2.ics';
    }
    if ($group == "BUT3-TP3") {
        $calendar_link = './../backup_cal/BUT3-TP3.ics';
    }
    if ($group == "BUT3-TP4") {
        $calendar_link = './../backup_cal/BUT3-TP4.ics';
    }
    return $calendar_link;
}
// on initialise la bibliothèque Firebase JWT pour PHP avec Composer et on y ajoute la clé secrète qui est dans le fichier .env
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

// La c'est la fonction qui décode le JWT, oui le cookie est encodé pour plus de sécurité,
// Il faut que le tableau ci-dessous soit identique à celui du fichier login.php, sinon on perd des données
// Si on ajoute un champs dans le cookie il faut l'ajouter dans le tableau ci-dessous puis dans le fichier login.php
// La clé secrète est dans le fichier .env, ne pas toucher
function decodeJWT($jwt, $secret_key)
{

    try {
        // Décoder le JWT avec la clé secrète

        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

        // Accéder aux valeurs du payload du JWT
        $pname = $decoded->pname;
        $name = $decoded->name;
        $edu_group = $decoded->edu_group;
        $id_user = $decoded->id_user;
        $edu_mail = $decoded->edu_mail;
        $role = $decoded->role;
        $session_id = $decoded->session_id;

        // Retourner les valeurs sous forme d'un tableau associatif
        return array(
            'edu_group' => $edu_group,
            'pname' => $pname,
            'name' => $name,
            'id_user' => $id_user,
            'edu_mail' => $edu_mail,
            'role' => $role,
            'session_id' => $session_id,
        );
    } catch (Exception $e) {
        // Gérer les erreurs de décodage du JWT ici
        echo "Erreur de décodage du JWT : " . $e->getMessage();
    }
}
/**
 * Vérifie si l'utilisateur est connecté.
 * Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion.
 * Si l'utilisateur est connecté, la fonction retourne les informations de l'utilisateur.
 */
function onConnect($dbh)
{
    if (!isset($_COOKIE['jwt'])) {
        header('Location: ./login.php');
        exit;
    }

    $jwt = $_COOKIE['jwt'];
    $secret_key = $_ENV['SECRET_KEY'];
    $user = decodeJWT($jwt, $secret_key);

    // SI le cookie n'a pas le clé session_id, on le supprime et on le redirige vers la connection
    if (!isset($user['session_id'])) {
        unset($_COOKIE['jwt']);
        header('Location: ./login.php');
        exit;
    }
    // Extrait le session_id du JWT
    $session_id = $user['session_id'];

    // Mise à jour de la date de dernière connexion de l'utilisateur
    $sql_update_last_connection = "UPDATE users SET last_connection = NOW() WHERE id_user = :id_user";
    $stmt = $dbh->prepare($sql_update_last_connection);
    $stmt->execute(['id_user' => $user['id_user']]);

    // Vérification de l'identifiant de session
    $sql_session_id_verify = "SELECT session_id FROM sessions WHERE user_id = :user_id AND session_id = :session_id";
    $stmt = $dbh->prepare($sql_session_id_verify);
    $stmt->execute(['user_id' => $user['id_user'], 'session_id' => $session_id]);

    if ($stmt->rowCount() == 0) {
        unset($_COOKIE['jwt']);
        exit;
    }
    $cgu_check = "SELECT CGU FROM users WHERE id_user = :id_user";
    $stmt = $dbh->prepare($cgu_check);
    $stmt->execute(['id_user' => $user['id_user']]);
    $cgu_check = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cgu_check['CGU'] == 0) {
        ?><section class="CGU-index">
            <div class="title_CGU-index">
              <div class="title_content_CGU-index">
                <h1>Conditions générales d'utilisation</h1>
              </div>
              <div class="CGU_content">
                <p><strong>Conditions G&eacute;n&eacute;rales d'Utilisation</strong></p>
                <br>
                <p><strong>En vigueur au 01/01/2024</strong></p>
                <br>
                <p>Les pr&eacute;sentes Conditions G&eacute;n&eacute;rales d'Utilisation (ci-apr&egrave;s d&eacute;nomm&eacute;es "CGU") encadrent l'utilisation de l'application web MMI Companion, d&eacute;velopp&eacute;e par Arnaud Graciet et Rapha&euml;l Tiphonet. Elles d&eacute;finissent les conditions d'acc&egrave;s et d'utilisation des services propos&eacute;s par l'application par l'utilisateur. Ces CGU sont accessibles sur le site &agrave; la rubrique "CGU".</p>
                <p>L'inscription ou l'utilisation de l'application implique l'acceptation sans r&eacute;serve des CGU par l'utilisateur. Lors de l'inscription via le Formulaire d&rsquo;inscription, chaque utilisateur accepte express&eacute;ment ces CGU en cochant la case pr&eacute;c&eacute;dant le texte suivant : &laquo; Je reconnais avoir lu et compris les CGU et je les accepte &raquo;. En cas de non-acceptation, l'utilisateur doit renoncer &agrave; l'acc&egrave;s aux services de l'application. https://www.mmi-companion.fr se r&eacute;serve le droit de modifier unilat&eacute;ralement le contenu des CGU &agrave; tout moment.</p>
                <br>
                <p><strong>ARTICLE 1 : MENTIONS L&Eacute;GALES</strong></p>
                <p>L'&eacute;dition et la direction de la publication de l'application MMI Companion sont assur&eacute;es par&nbsp;:</p>
                <br>
                <p><strong>TIPHONET Rapha&euml;l</strong></p>
                <p>5 ALL DES VIGNES</p>
                <p>16730 TROIS-PALIS</p>
                <p>07 71 18 59 89</p>
                <p><a href="mailto:rtiphonet@gmail.com">rtiphonet@gmail.com</a></p>
                <br>
                <p>et par</p>
                <br>
                <p><strong>GRACIET Arnaud</strong></p>
                <p>32 R DU PORT THUREAU</p>
                <p>16000 ANGOULEME</p>
                <p>06 52 55 15 18</p>
                <p><a href="mailto:arnaud.graciet@gmail.com">arnaud.graciet@gmail.com</a></p>
                <br>
                <p>MMI Companion est h&eacute;berg&eacute; sur le sol fran&ccedil;ais et par la soci&eacute;t&eacute; fran&ccedil;aise&nbsp;:</p>
                <br>
                <p><strong>O2SWITCH</strong></p>
                <p>CHE DES PARDIAUX</p>
                <p>63000 CLERMONT-FERRAND</p>
                <p>04 44 44 60 40</p>
                <br>
                <p><strong>ARTICLE 2 : ACC&Egrave;S &Agrave; L'APPLICATION</strong></p>
                <p>L'application MMI Companion offre un acc&egrave;s gratuit &agrave; ses services, regroupant divers outils universitaires tels que l'emploi du temps, l'agenda, le relev&eacute; de notes, ainsi que les absences, dans le but de simplifier la vie des &eacute;tudiants. Cet acc&egrave;s est gratuit pour tout utilisateur disposant d'une connexion Internet, et tous les frais associ&eacute;s &agrave; cette connexion sont &agrave; la charge de l'utilisateur.</p>
                <p>Pour b&eacute;n&eacute;ficier des services de l'application, les utilisateurs non-membres doivent s'inscrire en remplissant le formulaire avec des informations sinc&egrave;res et exactes. L'acc&egrave;s aux fonctionnalit&eacute;s requiert une identification via un identifiant et un mot de passe, qui sont communiqu&eacute;s apr&egrave;s l'inscription. Tout utilisateur a la possibilit&eacute; de demander sa d&eacute;sinscription &agrave; tout moment.</p>
                <p>Il est important de noter que l'acc&egrave;s &agrave; l'application est exclusivement r&eacute;serv&eacute; aux &eacute;tudiants, aux professeurs et au personnel appartenant ou intervenant au sein de la formation M&eacute;tiers du multim&eacute;dia et de l'internet &agrave; l'IUT d'Angoul&ecirc;me.</p>
                <br>
                <p><strong>ARTICLE 3 : COLLECTE DES DONN&Eacute;ES</strong></p>
                <p>L'application assure la collecte et le traitement des informations personnelles dans le plus strict respect de la vie priv&eacute;e, en conformit&eacute; avec la loi n&deg;78-17 du 6 janvier 1978. Les donn&eacute;es collect&eacute;es englobent les &eacute;l&eacute;ments suivants :</p>
                <br>
                <ul>
                  <li><strong> - Le nom</strong></li>
                  <li><strong> - Le pr&eacute;nom</strong></li>
                  <li><strong> - L&rsquo;adresse e-mail &eacute;tudiante</strong></li>
                  <li><strong> - L&rsquo;emploi du temps universitaire via l&rsquo;application ADE</strong></li>
                  <li><strong> - Les moyennes des 5 comp&eacute;tences, ainsi que le rang dans la promotion</strong></li>
                  <li><strong> - Les absences des &eacute;tudiants</strong></li>
                </ul>
                <br>
                <p>Ces donn&eacute;es sont conserv&eacute;es pendant une p&eacute;riode de 3 ans avant leur suppression. En ce qui concerne les absences et le relev&eacute; des notes, l'application MMI Companion n'y acc&egrave;de pas et n'enregistre pas ces donn&eacute;es. Seul l'utilisateur peut y acc&eacute;der. L&rsquo;utilisation des donn&eacute;es n'est pas &agrave; des fins commerciales.</p>
                <p>L'utilisateur dispose du droit d'acc&egrave;s, de rectification, de suppression et d'opposition sur ses donn&eacute;es personnelles, droits qu'il peut exercer par mail &agrave; l'adresse rgpd@mmi-companion.fr, via un formulaire de contact, ou directement depuis son espace personnel. La prise en charge de cette demande sera effectu&eacute;e dans un d&eacute;lai maximal de 48 heures ouvr&eacute;es.</p>
                <p>En acceptant ces conditions, vous autorisez la collecte des donn&eacute;es d&rsquo;absences et de notes par le secr&eacute;tariat et les professeurs de la formation via l&rsquo;interm&eacute;diaire du site : https://mmi-angouleme-dashboard.alwaysdata.net/ sous l&rsquo;autorit&eacute; de M. LOUET Fran&ccedil;ois, site h&eacute;berg&eacute; chez&nbsp;:</p>
                  <br>
                  <p><strong>ALWAYSDATA</strong></p>
                <p>91 RUE DU FAUBOURG SAINT HONORE</p>
                <p>75008 PARIS 8</p>
                <p>0 1 84 16 23 40</p>
                <br>
                <p><strong>ARTICLE 4 : PROPRI&Eacute;T&Eacute; INTELLECTUELLE</strong></p>
                <p>Les contenus de l'application MMI Companion (marques, logos, textes, images, son) sont prot&eacute;g&eacute;s par le Code de la propri&eacute;t&eacute; intellectuelle. Toute reproduction, publication ou copie n&eacute;cessite l'autorisation pr&eacute;alable de l'application. L'utilisation &agrave; des fins commerciales et publicitaires est strictement interdite.</p>
                <br>
                <p><strong>ARTICLE 5 : RESPONSABILIT&Eacute;</strong></p>
                <p>Malgr&eacute; les efforts d&eacute;ploy&eacute;s par l'application pour fournir des informations fiables, il est important de noter que leur fiabilit&eacute; n'est pas garantie. Des d&eacute;fauts, erreurs ou omissions peuvent survenir dans les informations fournies, lesquelles sont pr&eacute;sent&eacute;es &agrave; titre indicatif et g&eacute;n&eacute;ral. Ces donn&eacute;es n'ont aucune valeur contractuelle, et l'application d&eacute;cline toute responsabilit&eacute; en cas de force majeure, d'interruption ou de modification du service.</p>
                <p>Concernant le relev&eacute; de notes et les absences, il est crucial de souligner que seule l'information officielle d&eacute;livr&eacute;e par l'universit&eacute; en fin de semestre est consid&eacute;r&eacute;e comme valide. Les donn&eacute;es relatives aux notes et aux absences pr&eacute;sentes dans l'application sont fournies &agrave; titre informatif uniquement et ne doivent en aucun cas &ecirc;tre consid&eacute;r&eacute;es comme exhaustives ou d&eacute;finitives.</p>
                <p>Nous d&eacute;clinons toute responsabilit&eacute; en cas d'erreurs qui pourraient survenir lors de la saisie des informations.</p>
                <br>
                <p><strong>ARTICLE 6 : LIENS HYPERTEXTES</strong></p>
                <p>L'application peut contenir des liens hypertextes vers des pages externes. L'utilisateur est inform&eacute; qu'en cliquant sur ces liens, il quittera l'application MMI Companion. Cette derni&egrave;re n'a aucun contr&ocirc;le sur ces pages et ne saurait &ecirc;tre responsable de leur contenu.</p>
                <br>
                <p><strong>ARTICLE 7 : COOKIES</strong></p>
                <p>L'utilisateur est inform&eacute; de l'utilisation de cookies lors de sa navigation sur l'application MMI Companion. Ces cookies am&eacute;liorent l'exp&eacute;rience utilisateur. L'utilisateur peut les d&eacute;sactiver depuis les param&egrave;tres de son navigateur.</p>
                <br>
                <p><strong>ARTICLE 8 : PUBLICATION PAR L&rsquo;UTILISATEUR</strong></p>
                <p>Les membres peuvent publier des contenus sur l'application MMI Companion, tels que des informations diverses, des t&acirc;ches ou des &eacute;valuations dans l&rsquo;agenda. En publiant un contenu, le membre s'engage &agrave; respecter les r&egrave;gles de la Netiquette et les r&egrave;gles de droit en vigueur. L'application peut exercer une mod&eacute;ration sur les publications et se r&eacute;serve le droit de refuser leur mise en ligne sans justification aupr&egrave;s du membre.</p>
                <p>Le membre reste titulaire de l'int&eacute;gralit&eacute; de ses droits de propri&eacute;t&eacute; intellectuelle. En publiant un contenu sur l'application, il c&egrave;de &agrave; la soci&eacute;t&eacute; &eacute;ditrice le droit non exclusif et gratuit de repr&eacute;senter, reproduire, adapter, modifier, diffuser et distribuer sa publication, directement ou par un tiers autoris&eacute;, dans le monde entier, sur tout support (num&eacute;rique ou physique), pour la dur&eacute;e de la propri&eacute;t&eacute; intellectuelle. Le membre c&egrave;de notamment le droit d'utiliser sa publication sur Internet et sur les r&eacute;seaux de t&eacute;l&eacute;phonie mobile.</p>
                <p>La soci&eacute;t&eacute; &eacute;ditrice s'engage &agrave; faire figurer le nom du membre &agrave; proximit&eacute; de chaque utilisation de sa publication. Tout contenu mis en ligne par l'utilisateur est de sa seule responsabilit&eacute;. L'utilisateur s'engage &agrave; ne pas mettre en ligne de contenus pouvant porter atteinte aux int&eacute;r&ecirc;ts de tierces personnes. Tout recours en justice engag&eacute; par un tiers l&eacute;s&eacute; contre l'application sera pris en charge par l'utilisateur. Le contenu de l'utilisateur peut &ecirc;tre &agrave; tout moment et pour n'importe quelle raison supprim&eacute; ou modifi&eacute; par l'application, sans pr&eacute;avis.</p>
                <br>
                <p><strong>ARTICLE 9 : DROIT APPLICABLE ET JURIDICTION COMP&Eacute;TENTE</strong></p>
                <p>La l&eacute;gislation fran&ccedil;aise s'applique au pr&eacute;sent contrat. En cas d'absence de r&eacute;solution amiable d'un litige n&eacute; entre les parties, les tribunaux fran&ccedil;ais seront seuls comp&eacute;tents pour en conna&icirc;tre. Pour toute question relative &agrave; l'application des pr&eacute;sentes CGU, vous pouvez joindre l'&eacute;diteur aux coordonn&eacute;es indiqu&eacute;es &agrave; l'ARTICLE 1.</p>
                <p>Ces Conditions G&eacute;n&eacute;rales d'Utilisation ont &eacute;t&eacute; &eacute;tablies en accord avec la r&eacute;glementation fran&ccedil;aise et europ&eacute;enne en vigueur, notamment le RGPD, pour assurer la protection des droits et de la vie priv&eacute;e des utilisateurs de l'application MMI Companion.</p>
                <p>En acceptant ces CGU, vous reconnaissez avoir pris connaissance et accept&eacute; les dispositions qui y sont contenues.</p>
              </div>
              <p>En cliquant sur "C'est parti !", <strong>vous avez lu et vous acceptez les <a href="https://mmi-companion.fr/cgu.html">CGU</a> de MMI Companion.</strong></p>
            </div>
            <div class="trait_content_CGU-index"></div>
            <button id="button_CGU-validate" class="button_CGU-index">C'est parti !</button>
          </section>
        <?php
        }

    return $user;
}

function checkEvent($id_event, $id_user)
{
    $dbh = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . '', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $sql = "INSERT INTO event_check (id_event, id_user) VALUES (:id_event, :id_user)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['id_event' => $id_event, 'id_user' => $id_user]);
}
function unCheckEvent($id_event, $id_user)
{
    $dbh = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . '', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $sql = "DELETE FROM event_check WHERE id_event = :id_event AND id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['id_event' => $id_event, 'id_user' => $id_user]);
}
// Fonction pour vérifier si un enregistrement existe pour l'événement et l'utilisateur
function getEventCheckedStatus($dbh, $idAgenda, $idUser)
{
    // Assurez-vous de sécuriser vos paramètres
    $idAgenda = intval($idAgenda);
    $idUser = intval($idUser);

    // Préparez la requête SQL pour vérifier l'existence de l'enregistrement
    $sql = "SELECT 1 FROM event_check WHERE id_event = :idAgenda AND id_user = :idUser";

    // Préparez la requête SQL en utilisant la variable de connexion
    $stmt = $dbh->prepare($sql);

    // Liez les valeurs des paramètres
    $stmt->bindParam(":idAgenda", $idAgenda, PDO::PARAM_INT);
    $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);

    // Exécutez la requête SQL
    $stmt->execute();

    // Vérifiez s'il y a une ligne de résultat
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function sendNotification($message, $body, $groups)
{
    $dbh = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . '', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    // Assuming you already have a valid $dbh connection to your database

    $groupsArray = explode(',', $groups); // Split the groups into an array

    $auth = array(
        'VAPID' => array(
            'subject' => 'mailto:rtiphonet@gmail.com',
            'publicKey' => $_ENV['VAPID_PUBLIC_KEY'],
            'privateKey' => $_ENV['VAPID_PRIVATE_KEY'],
        ),
    );

    $webPush = new WebPush($auth);

    foreach ($groupsArray as $group) {
        $query = "SELECT s.* FROM subscriptions s
                  INNER JOIN users u ON s.id_user = u.id_user
                  WHERE u.edu_group = :group";
        $stmt = $dbh->prepare($query);
        $stmt->execute(['group' => trim($group)]); // Trim to remove any leading/trailing whitespace
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($subscriptions as $subscriptionData) {
            $subscription = Subscription::create([
                'endpoint' => $subscriptionData['endpoint'],
                'keys' => [
                    'p256dh' => $subscriptionData['p256dh'],
                    'auth' => $subscriptionData['auth'],
                ],
                // You can add additional properties if needed, e.g., 'contentEncoding', 'expirationTime', etc.
            ]);

            $payload = json_encode([
                'body' => $body,
                'title' => $message
            ]);

            $webPush->queueNotification($subscription, $payload);
        }
    }

    $webPush->flush();

    //foreach ($webPush->flush() as $report) {
       // $endpoint = $report->getRequest()->getUri()->__toString();

        //if ($report->isSuccess()) {
            //echo "[v] Le message à bien été envoyé à {$endpoint}.\n";
        //} else {
            // echo "[x] Le message n'a pas réussi à être envoyé à {$endpoint}: {$report->getReason()}\n";
            // Handle the failure, remove the subscription from your server, etc.
        //}
    //}
}
function viewChef($dbh, $edu_group)
{

    $sql_chef = "SELECT pname, name FROM users WHERE edu_group = :edu_group AND role LIKE '%chef%'";
    $stmt_chef = $dbh->prepare($sql_chef);
    $stmt_chef->execute([
        'edu_group' => $edu_group,
    ]);
    $chef = $stmt_chef->fetch(PDO::FETCH_ASSOC);
    return $chef['pname'] . " " . $chef['name'];
}


function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}

const APP_URL = 'https://app.mmi-companion.fr/pages';
const SENDER_EMAIL_ADDRESS = 'no-reply@mmi-companion.fr';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
function send_activation_email(string $email, string $activation_code, string $name)
{
    // create the activation link
    $activation_link = APP_URL . "/verify_mail.php?email=$email&activation_code=$activation_code";

    // set email subjectj
    $subject = 'Active ton compte dès maintenant !';

    // load HTML content from a file
    $message = file_get_contents('./../verify.html');

    // replace placeholders in the HTML content with actual values
    $message = str_replace('{activation_link}', $activation_link, $message);
    $message = str_replace('{FirstName}', $name, $message);

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'From: MMI Companion <' . SENDER_EMAIL_ADDRESS . '>' . "\r\n" .
        'Reply-To: ' . SENDER_EMAIL_ADDRESS . "\r\n" .
        'Content-Type: text/html; charset="utf-8"' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // send the email
    $_SESSION['mail_message'] = "";
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $_ENV['SERVEUR_MAIL'];                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = $_ENV['MAIL_PORT'];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        
        //Set AuthType to use XOAUTH2
        $mail->AuthType = 'XOAUTH2';
        
        //Start Option 1: Use league/oauth2-client as OAuth2 token provider
        //Fill in authentication details here
        //Either the gmail account owner, or the user that gave consent
        $email = $_ENV['MAIL_USERNAME'];
        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        
        //Obtained by configuring and running get_oauth_token.php
        //after setting up an app in Google Developer Console.
        $refreshToken = $_ENV['REFRESH_TOKEN'];
        
        //Create a new OAuth2 provider instance
        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );
        
        //Pass the OAuth provider instance to PHPMailer
        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );

        //Recipients
        $mail->setFrom(SENDER_EMAIL_ADDRESS, 'MMI Companion');
        $mail->addAddress($email, $name);     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
    } catch (Exception $e) {
        $_SESSION['mail_message'] = "Une erreur vient de survenir lors de l'envoi du mail, réessaye plus tard.";
        error_log("Error sending activation email to $email");
    }
    // mail($email, $subject, $message, $headers, '-f' . SENDER_EMAIL_ADDRESS);

    // if () {
    //     $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
        
    //     $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
        
    // } else {
    //     $_SESSION['mail_message'] = "Une erreur vient de survenir lors de l'envoi du mail, réessaye plus tard.";
    //     error_log("Error sending activation email to $email");
    // }
}

function send_reset_password(string $email, string $activation_code)
{
    // create the activation link
    $activation_link = APP_URL . "/verify_password.php?email=$email&activation_code=$activation_code";

    // set email subject & body
    $subject = 'Réinitialise ton mot de passe dès maintenant !';
    $message = <<<HTML
<!DOCTYPE html>
<html lang="en" style="margin: 0; padding: 0; box-sizing: border-box;">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Email</title>
    <style>
.button:hover {
  background-color: #458ea4;
}
</style>
</head>
<body style="margin: 0; padding: 0; box-sizing: border-box; background-color: #f2f2f2;">
    <div class="container" style="box-sizing: border-box; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #B9E0FF; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div class="logo" style="margin: 0; padding: 0; box-sizing: border-box; text-align: center; margin-bottom: 20px;">
            <img src="https://dev.mmi-companion.fr/mmicompanion/assets/img/mmicompanion.svg" alt="logo mmi" style="margin: 0; padding: 0; box-sizing: border-box; max-width: 80%;">
            <h1 style="margin: 0; padding: 0; box-sizing: border-box; color: #56B8D6; font-family: Montserrat, sans-serif; font-size: 1.6rem; font-weight: 800;">MMI Companion</h1>
        </div>

        <table style="margin: 0; padding: 0; box-sizing: border-box; width: 100%; border-collapse: collapse; margin-bottom: 20px;" width="100%">
            <tbody style="margin: 0; padding: 0; box-sizing: border-box;">
                <tr style="margin: 0; padding: 0; box-sizing: border-box;">
                    <td style="margin: 0; box-sizing: border-box; padding: 10px; text-align: left; border-bottom: 1px solid #ccc;" align="left">
                        <img src="https://dev.mmi-companion.fr/mmicompanion/assets/img/verif_mail.svg" style="margin: 0; padding: 0; box-sizing: border-box; width: 100%;" alt="email">
                    </td>
                </tr>
                <tr style="margin: 0; padding: 0; box-sizing: border-box;">
                    <td style="margin: 0; box-sizing: border-box; padding: 10px; text-align: left; border-bottom: 1px solid #ccc;" align="left">
                        <p style="margin: 0; padding: 0; box-sizing: border-box; color: #004A5A; font-family: Montserrat, sans-serif; font-size: 1.2rem; font-weight: 500; text-align: center;">
                            Bonjour, <br style="margin: 0; padding: 0; box-sizing: border-box;"><br style="margin: 0; padding: 0; box-sizing: border-box;">
                            Merci de confirmer votre email en cliquant sur le lien ci-dessous.
                        </p>
                    </td>
                </tr>
                <tr style="margin: 0; padding: 0; box-sizing: border-box;">
                    <td style="margin: 0; box-sizing: border-box; padding: 10px; text-align: left; border-bottom: 1px solid #ccc;" align="left">
                        <a href="$activation_link" class="button" style="margin: 0; box-sizing: border-box; background-color: #56B8D6; color: #004A5A; border: none; border-radius: 25px; padding: 10px 60px; font-size: 13px; font-weight: 800; cursor: pointer; font-family: Montserrat, sans-serif; text-decoration: none; display: block; width: 100%; text-align: center;">Confirmer votre email</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

HTML;

    // email header
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'From: MMI Companion <' . SENDER_EMAIL_ADDRESS . '>' . "\r\n" .
        'Reply-To:' . SENDER_EMAIL_ADDRESS . "\r\n" .
        'Content-Type: text/html; charset="utf-8"' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // send the email
    $_SESSION['mail_message'] = "";
    if (mail($email, $subject, nl2br($message), $headers)) {
        $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
    } else {
        $_SESSION['mail_message'] = "Une erreur vient de survenir lors de l'envoi du mail, réessaye plus tard.";
        error_log("Error sending activation email to $email");
    }
}

function compareDates($a, $b)
{
    $dateA = strtotime($a['date_finish']);
    $dateB = strtotime($b['date_finish']);
    $year = date('o'); // Obtenez l'année actuelle au format ISO-8601
    $week = date('W'); // Obtenez le numéro de semaine actuel

    // Formatez la date au format "YYYY-Www"
    $dateFormat = $year . '-W' . $week;

    // Vérifiez si $a est la semaine courante
    if ($a['date_finish'] === $dateFormat) {
        return -1; // $a est la semaine courante, placez-la en première position
    }

    // Vérifiez si $b est la semaine courante
    if ($b['date_finish'] === $dateFormat) {
        return 1; // $b est la semaine courante, placez-la en première position
    }

    // Comparez les dates de fin pour les autres cas
    if ($dateA == $dateB) {
        return 0;
    }

    return ($dateA < $dateB) ? -1 : 1;
}
