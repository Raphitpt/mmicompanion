<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);

$USER_name = $_ENV['ABSENCE_USERNAME'];
$USER_password = $_ENV['ABSENCE_PASSWORD'];

$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_COOKIE['jwt'])) {
    header('Location: ./login.php');
    exit;
  }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['semestre']) && !empty($_POST['semestre']) && !empty($user_sql['edu_mail'])){
    $semestre = $_POST['semestre'];
    $edu_mail = $user_sql['edu_mail'];

    $credentials = base64_encode($USER_name . ':' . $USER_password);

    $url = "https://mmi-angouleme-dashboard.alwaysdata.net/api-v1/absences/". $semestre ."/". $edu_mail ."?detailled=true";
    $headers = [
        'Accept: application/json',
        "Authorization: Basic $credentials",
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // sauvegarde les logs
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_STDERR, $fp);
    $fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');

    if (curl_error($ch)) {
        $error_msg = curl_error($ch);
        echo $error_msg;
    }
    $result = curl_exec($ch);

    // if ($result == null) {
    //     $result = "Aucune absence n'a été trouvée pour ce semestre.";
    // }
    curl_close($ch);
    echo $result;
}
