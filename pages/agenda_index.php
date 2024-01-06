<?php
session_start();
require '../bootstrap.php';
$user = onConnect($dbh);

// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = userSQL($dbh, $user);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $edu_group = $_POST['edu_group'];

    $agendaMerged = getAgendaProf($dbh, $user, $edu_group);

    $response = array(
        'viewChef' => viewChef($dbh, $edu_group),
        'agendaHtml' => $agendaMerged
    );
    echo json_encode($response);
}

