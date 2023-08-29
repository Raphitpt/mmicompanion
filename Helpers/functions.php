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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/svg" href="../assets/img/mmicompanion_512.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../assets/css/style.css" rel="stylesheet"">
  <link href="../assets/css/uicons-bold-rounded.css" rel="stylesheet"">
  <link rel="manifest" href="../manifest.webmanifest" />
  <script async src="https://unpkg.com/pwacompat" crossorigin="anonymous"></script>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
  <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    

</head>


HTML_HEAD;
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
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21314&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT1-TP2") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21315&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT1-TP3") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21470&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT1-TP4") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24826&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT2-TP1") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24827&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT2-TP2") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24834&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT2-TP3") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24835&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT2-TP4") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24836&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT3-TP1") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2465&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT3-TP2") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2454&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT3-TP3") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2452&projectId=14&calType=ical&nbWeeks=15';
    }
    if ($group == "BUT3-TP4") {
        $calendar_link = 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2451&projectId=14&calType=ical&nbWeeks=15';
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

        // Retourner les valeurs sous forme d'un tableau associatif
        return array(
            'edu_group' => $edu_group,
            'pname' => $pname,
            'name' => $name,
            'id_user' => $id_user,
            'edu_mail' => $edu_mail,
            'role' => $role
        );
    } catch (Exception $e) {
        // Gérer les erreurs de décodage du JWT ici
        echo "Erreur de décodage du JWT : " . $e->getMessage();
    }
}

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function sendNotification($message, $body, $group) {
    $dbh = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'].'', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    // Assuming you already have a valid $dbh connection to your database
    if ($group != null) {
        $query = "SELECT s.* FROM subscriptions s
                  INNER JOIN users u ON s.edu_mail = u.edu_mail
                  WHERE u.edu_group = :group";
        $stmt = $dbh->prepare($query);
        $stmt->execute(['group' => $group]);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $query = "SELECT * FROM subscriptions";
        $stmt = $dbh->query($query);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    $auth = array(
        'VAPID' => array(
            'subject' => 'mailto:rtiphonet@gmail.com',
            'publicKey' => $_ENV['VAPID_PUBLIC_KEY'],
            'privateKey' => $_ENV['VAPID_PRIVATE_KEY'],
        ),
    );

    $webPush = new WebPush($auth);

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

    $webPush->flush();

    foreach ($webPush->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();

        if ($report->isSuccess()) {
            echo "[v] Le message à bien été envoyer à {$endpoint}.\n";
        } else {
            echo "[x] Le message n'a pas réussi à être envoyer {$endpoint}: {$report->getReason()}\n";
            // Handle the failure, remove the subscription from your server, etc.
        }
    }
}
function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}

const APP_URL = 'http://localhost/mmicompanion/pages';
const SENDER_EMAIL_ADDRESS = 'no-reply@mmi-companion.fr';
function send_activation_email(string $email, string $activation_code)
{
    // create the activation link
    $activation_link = APP_URL . "/verify_mail.php?email=$email&activation_code=$activation_code";

    // set email subject & body
    $subject = 'Active ton compte dès maintenant !';
    $message = <<<MESSAGE
            Hi,
            Please click the following link to activate your account:
            $activation_link
            MESSAGE;
    // email header
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'From: MMI Companion <'.SENDER_EMAIL_ADDRESS.'>' . "\r\n" .
            'Reply-To:'.SENDER_EMAIL_ADDRESS. "\r\n" .
            'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
            'Content-Disposition: inline'. "\r\n" .
            'Content-Transfer-Encoding: 7bit'." \r\n" .
            'X-Mailer:PHP/'.phpversion();

    // send the email
    $_SESSION['mail_message'] = "";
    if (mail($email, $subject, nl2br($message), $headers)) {
        $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
    } else {
        $_SESSION['mail_message'] = "Une erreur vient de survenir lors de l'envoi du mail, réessaye plus tard.";
        error_log("Error sending activation email to $email");
    }
}

function send_reset_password(string $email, string $activation_code)
{
    // create the activation link
    $activation_link = APP_URL . "/reset_pass.php?email=$email&activation_code=$activation_code";

    // set email subject & body
    $subject = 'Réinitialise ton mot de passe dès maintenant !';
    $message = <<<MESSAGE
            Salut,
            Clique sur le lien pour changer ton mot de passe :
            $activation_link
            MESSAGE;
    // email header
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'From: MMI Companion <'.SENDER_EMAIL_ADDRESS.'>' . "\r\n" .
            'Reply-To:'.SENDER_EMAIL_ADDRESS. "\r\n" .
            'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
            'Content-Disposition: inline'. "\r\n" .
            'Content-Transfer-Encoding: 7bit'." \r\n" .
            'X-Mailer:PHP/'.phpversion();

    // send the email
    $_SESSION['mail_message'] = "";
    if (mail($email, $subject, nl2br($message), $headers)) {
        $_SESSION['mail_message'] = "Le mail vient de t'être envoyé, penses à regarder dans tes spams si besoin.";
    } else {
        $_SESSION['mail_message'] = "Une erreur vient de survenir lors de l'envoi du mail, réessaye plus tard.";
        error_log("Error sending activation email to $email");
    }
}