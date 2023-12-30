<?php
session_start();
require '../bootstrap.php';

$nextCours = nextCours('BUT2-TP3');

// si submit
// if (isset($_POST['submit'])) {
//     sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "cmqgfxf7Df_aJvJEVc2XB3:APA91bHoEOb8ucJfBURLDtMX9RI4Zwajab0Cf_NpUFxHQMD-bnhNA5BeV7q9Ko8FDctzED69YwkX49ofUinel-VRuPut5v8MyM-GXp8IZ9IT2_ixWcfeS5HdSqiU38yH3G32O2UxB1FY");
//     var_dump(sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "cmqgfxf7Df_aJvJEVc2XB3:APA91bHoEOb8ucJfBURLDtMX9RI4Zwajab0Cf_NpUFxHQMD-bnhNA5BeV7q9Ko8FDctzED69YwkX49ofUinel-VRuPut5v8MyM-GXp8IZ9IT2_ixWcfeS5HdSqiU38yH3G32O2UxB1FY"));
// }
// $client = new \Fcm\FcmClient('AIzaSyCjTSvi2mReuoaSK9PlbFl-0Hvre04yj8M', "995711151734");

// // Remove the second parameter for more basic device information
// $info = new \Fcm\Device\Info("cmqgfxf7Df_aJvJEVc2XB3:APA91bHoEOb8ucJfBURLDtMX9RI4Zwajab0Cf_NpUFxHQMD-bnhNA5BeV7q9Ko8FDctzED69YwkX49ofUinel-VRuPut5v8MyM-GXp8IZ9IT2_ixWcfeS5HdSqiU38yH3G32O2UxB1FY", true);

use Google\Auth\CredentialsLoader;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Client;

$projectId = 'mmi-companion';
$apiKey = 'AIzaSyCjTSvi2mReuoaSK9PlbFl-0Hvre04yj8M';
$token = 'ezgroWzxHelh4M9OFrheJq:APA91bHLZZgbKVUZMwz_tJBBOmGbb7wmNo8CFoI88Y7mmlI7Y9bonlneZNul6lqZxm3ljvK9E3mxBP4L84v7OUonDYS8El76V9b8erT6VSUna9BwI-KVnMBSmgo0I7VVKdQP3aWSFW5L';

$client = new Google_Client();
$client->setApplicationName('Your App Name');
$client->setAuthConfig([
    "type"=> "service_account",
  "project_id"=> "mmi-companion",
  "private_key_id"=> "96ff21b60ece8fbae7ae4b69eae2cacf9d74e129",
  "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCngbWiNOYVcIEk\nZVzHGvEs1AQJuCv8/n08ilp2eaU3FYAs+WkVuhkEIH4ciCRs6kceCxEqcbRE840P\nChXZEuw/bbWLnKgVHRUxwBHUlpAHXS4ci9ocK7MOsI0ggtVe0nM4LSNvakCQrmyk\n3UI7h95aX6iJtSiHPql5CyaqDm2wl+d+8TSXZ6MvEsN/rmy2ScR3SSwtgVTnTXj7\nVx0Jv8mnWr7S2dBa/JKn5lIBWfutC3BIMltKReRt/bTs50+lCKeGYE19szmp992w\n9LkDSvagZh5/ta94oyyOyOvTmmBZpL+rYBEWCz+vaC2eA7sWu7KOEwR16mNA+6Cr\n8mBZcRWfAgMBAAECggEATm+OfcYHd0KXNlPAffs/s54TxflXn8ALJK4kbhXIJ2MK\nAgVID8R0sloEs5eMdvO9GXRVNWrI3wCsrL4sPNl/hrP4rsdMbZaQthsaPlHCX7Bi\nRzu1hjtwPRCvBOo+v4gMK+H3FdTLJvSGKyzwNrAIjoj8gn8x2yKcePGfyUG4W1gC\nnRppje5Y2C/srVT6Sq1RJYqtJPzJWhGFrWHqUb1Oosegqaxwa3fnHkZbKoOtvmgL\n80Yj7ZqgR/cmFr372HFM270TmhrmZWuV/pAcwG6Tr3GPeXgLnh6CkDMaqu634Mrt\nAA4LB13xbxLL1qBaUbVjIocL/s5I2fzSS0leb5D7AQKBgQDrwpl0BzCN6X3YBJ9r\nLKPaUrPGoELw+0GDGa59UlO+/FXQJHhYRHgE8nbHCD0nFHeTqk9HNxkCMaYsjxCu\nThC1HSfNIqkbK1FlsrAZb+1+eAn2g0VEirBl+bakdmdfmHP8b+Bx9/0DI968CH/I\niBx4y06gdFfm//2SuOBikPms1QKBgQC14xMXhImDMtKLIvP7IkbqyVLFhWtiZYyh\nROYXJM5GMiytq1XF1h1bmuVR+R9MK3t7wT1hDqY6Tkx4gD2EDj/6T4LZ2w1Ren7/\nyI+shJax1ZA4LX6F+RNWV8KjefNyV/2pP0bv0rPNYzFk9YOKB/NoAntkwhZKzNL5\nU72OQlTiowKBgQDgr/7FmNCKfzvjM0ynvMyQFv+kzjneJudhxYkJgPu1ahFypD9Z\niC+GL3pJ5604YIYx4j9aFMWt1JmVn+d654V+xsGW/HCEJz2VGb5BD+4c+NQt7x+F\n2lORbHLHvNx6O/ZWYF1c4MZuRrJRLBx0hlv0N3bA7wCTc+c7/RxEc/yv3QKBgFlB\n6t+SoezpZtrytypZgtW7IExgEfeCgAWwCv32iOd3vecn+nqQfW2z0K9ugoZdnEZ5\n6QYVK0vXmr1TqVyBpbHPjRYd4iZG58XcBW2Sz2TLue9nm/xg47VucczJjsdhGNES\nhVwxWR0EfXve48M77Z3wAd/LQydH5oyGJLKIKKHjAoGBAOW2IC54EIbMVBftEqSb\nZP4qzl5+2Qa2gP019lr5kARqYiXF4ArM8wQ4Cvtnn4QwkGa6GKUONgf7StZwhhVN\n9YzC6dg1jK+iqSdy4pGJpl63OdbLLupUMNcgVUwAlattvQ0x76CfjPO+A33jLPke\nBtJRb0baChPT14WqZoTpSPXd\n-----END PRIVATE KEY-----\n",
  "client_email"=> "firebase-adminsdk-2gvth@mmi-companion.iam.gserviceaccount.com",
  "client_id"=> "116836359113926610745",
  "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
  "token_uri"=> "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-2gvth%40mmi-companion.iam.gserviceaccount.com",
  "universe_domain"> "googleapis.com"
]);
$client->addScope('https://www.googleapis.com/auth/firebase.messaging');

$httpClient = $client->authorize();
$uri = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

$message = [
    'message' => [
        'token' => $token,
        'notification' => [
            'title' => 'Hello',
            'body' => 'World',
        ],
    ],
];

$response = $httpClient->post($uri, [
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'body' => json_encode($message),
]);

echo $response->getBody();

echo head('Accueil');
?>
<main>
<div>
    <h1>Accueil</h1>
    <p>Bienvenue sur l'application de gestion des ressources de l'IUT de Lens.</p>
    <p>Vous pouvez consulter les ressources disponibles dans le menu de gauche.</p>
    <p>Vous pouvez également consulter les prochains cours dans le tableau ci-dessous.</p>
    <table>
        <thead>
            <tr>
                <th>Intitulé</th>
                <th>Enseignant</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Salle</th>
                <th>Temps restant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $nextCours['summary'] ?></td>
                <td><?= $nextCours['description'] ?></td>
                <td id="tmstpCours"><?= $nextCours['dtstart_tz'] ?></td>
                <td><?= $nextCours['debut'] ?> - <?= $nextCours['fin'] ?></td>
                <td><?= $nextCours['location'] ?></td>
                <td id="tempsBefore">0</td>
            </tr>
        </tbody>
    </table>
    <form>
        <button type="submit">send notification</button>
    </form>
</div>
</main>
</body>
<script>
const tmstpCours = document.getElementById('tmstpCours').innerHTML;
const tempsBefore = document.getElementById('tempsBefore');
function tempsRestant(x){
    y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6Z');
    let now = new Date();
    let dateCours = new Date(y);
    let diff = dateCours - now;
    let diffSec = diff / 1000;
    let diffMin = diffSec / 60;
    let diffHeure = diffMin / 60;
    let diffJour = diffHeure / 24;
    tempsBefore.innerHTML = "";
    tempsBefore.innerHTML = Math.floor(diffJour) + ' jours ' + Math.floor(diffHeure % 24) + ' heures ' + Math.floor(diffMin % 60) + ' minutes ' + Math.floor(diffSec % 60) + ' secondes';
}
setInterval(function () {
    tempsRestant(tmstpCours);
}, 1000);
</script>