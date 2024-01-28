<?php
session_start();
require "../bootstrap.php";
$user = onConnect($dbh);

header('Content-Type: application/json');

$load = $_POST['load'];

$user_sql = userSQL($dbh, $user);


$dateSemaine = formatSemaineScolaire();


function countEvent($agenda, $dateSemaine)
{
    $nbEval = 0;
    $nbDevoir = 0;

    foreach ($agenda as $semaine => $jours) {
        if ($semaine == $dateSemaine) {
            foreach ($jours as $jour => $taches) {
                foreach ($taches as $tache) {
                    if ($tache['type'] == 'devoir' || $tache['type'] == 'autre') {
                        $nbDevoir++;
                    } elseif ($tache['type'] == 'eval') {
                        $nbEval++;
                    }
                }
            }
        }
    }

    foreach ($agenda as $semaine => $jours) {
        if ($semaine == $dateSemaine) {
            foreach ($jours as $jour => $taches) {
                foreach ($taches as $tache) {
                    if ($tache['type'] == 'devoir' || $tache['type'] == 'autre') {
                        if ($tache['id_event'] !== NULL && $tache['id_event'] !== '') {
                            $nbDevoir--;
                        }
                    }
                }
            }
        }
    }

    return ['nbEval' => $nbEval, 'nbDevoir' => $nbDevoir];
}


if ($load == 'false') {
    $idAgenda = $_POST['idAgenda'];
    $checkedValue = $_POST['checked'];
    $idUser = $_POST['id_user'];

    // Utilisation de la fonction
    if ($checkedValue == 0) {
        unCheckEvent($idAgenda, $idUser);
    } else {
        checkEvent($idAgenda, $idUser);
    }
}


$agendaMerged = getAgenda($dbh, $user, $user_sql['edu_group']);
// print_r($agendaMerged);

$countResult = countEvent($agendaMerged, $dateSemaine);
$nbEval = $countResult['nbEval'];
$nbDevoir = $countResult['nbDevoir'];



echo json_encode(['message' => "Mise à jour effectuée avec succès !", 'nbDevoir' => $nbDevoir, 'nbEval' => $nbEval]);
