<?php
session_start();
require '../bootstrap.php';

if (!isset($_COOKIE['jwt'])) {
    header('Location: ./login.php');
    exit;
}

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY'];
$user = decodeJWT($jwt, $secret_key);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $subject = '%' . $_POST['subject'] . '%';
    $sql_subject = "SELECT * FROM sch_subject WHERE name_subject LIKE :subject ORDER BY name_subject ASC";
    $stmt_subject = $dbh->prepare($sql_subject);
    $stmt_subject->execute([
        ':subject' => $subject
    ]);
    $subject_sql = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($subject_sql);
}
?>
