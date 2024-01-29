<?php
session_start();
require('../bootstrap.php');

$user = onConnect($dbh);
if (str_contains($user['role'], 'admin') == false && ($user['edu_mail'] != 'raphael.tiphonet@etu.univ-poitiers.fr' || $user['edu_mail'] != 'arnaud.graciet@etu.univ-poitiers.fr')) {
    header('Location: ./../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['mail']) && isset($_GET['pname']) && isset($_GET['name']) && isset($_GET['trigramme'])) {
    $mail = $_GET['mail'];
    $pname = $_GET['pname'];
    $name = $_GET['name'];
    $trigramme = $_GET['trigramme'];
    generate_password_prof($mail, $name, $pname, $trigramme);
    header('Location: ./admin/administration.php');
}
