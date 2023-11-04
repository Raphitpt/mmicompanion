<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['but'])){
        if (strpos($_POST['but'], 'BUT1') !== false) {
            $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
            FROM sch_ressource rs
            JOIN sch_subject ss ON rs.name_subject = ss.id_subject
            WHERE rs.code_ressource LIKE 'R1%' OR rs.code_ressource LIKE 'R2%' OR rs.code_ressource LIKE 'SAE1%' OR rs.code_ressource LIKE 'SAE2%'
            ORDER BY ss.name_subject ASC";
        } elseif (strpos($_POST['but'], 'BUT2') !== false) {
            $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
            FROM sch_ressource rs
            JOIN sch_subject ss ON rs.name_subject = ss.id_subject
            WHERE rs.code_ressource LIKE 'R3%' OR rs.code_ressource LIKE 'R4%' OR rs.code_ressource LIKE 'SAE3%' OR rs.code_ressource LIKE 'SAE4%'
            ORDER BY ss.name_subject ASC";
        } elseif (strpos($_POST['but'], 'BUT3') !== false) {
            $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
            FROM sch_ressource rs
            JOIN sch_subject ss ON rs.name_subject = ss.id_subject
            WHERE rs.code_ressource LIKE 'R5%' OR rs.code_ressource LIKE 'R6%' OR rs.code_ressource LIKE 'SAE5%' OR rs.code_ressource LIKE 'SAE6%'
            ORDER BY ss.name_subject ASC";
        } else {
            $sql_subject = "SELECT rs.*, ss.name_subject, ss.id_subject FROM sch_ressource rs
            JOIN sch_subject ss ON rs.name_subject = ss.id_subject ORDER BY ss.name_subject ASC";
        }
    }
    $stmt_subject = $dbh->prepare($sql_subject);
    $stmt_subject->execute();
    $subjects = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($subjects);
    exit();
}