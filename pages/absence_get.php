<?php
session_start();
require '../bootstrap.php';

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);

$USER_name = $_ENV['ABSENCE_USERNAME'];
$USER_password = $_ENV['ABSENCE_PASSWORD'];

if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $semestre = $_POST['semestre'];
    $edu_mail = $user['edu_mail'];

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

    if (curl_error($ch)) {
        $error_msg = curl_error($ch);
        echo $error_msg;
    }
    // dd($error_msg);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, true);
    dd($result);
}