<?php
session_start();
require "../bootstrap.php";
$user = onConnect($dbh);

header('Content-Type: application/json');


$idAgenda = $_POST['idAgenda'];
$checkedValue = $_POST['checked'];
$idUser = $_POST['id_user'];

$user_sql = userSQL($dbh, $user);




$agendaMerged = getAgenda($dbh, $user, $user_sql['edu_group'], $user_sql);


function countEvent($agenda){
    $nbEval = 0;
    $nbDevoir = 0;
    
    $dateSemaine = "Semaine 01 (du 01/01 au 05/01)";

    foreach ($agenda as $semaine => $jours) {
        if ($semaine == $dateSemaine) {
            foreach ($jours as $jour => $taches) {
                foreach ($taches as $tache) {
                    if ($tache['type'] == 'devoir' || $tache['type'] == 'autre') {
                        if ($tache['id_event'] !== NULL && $tache['id_event'] !== '') {
                            $nbDevoir++;
                        }
                    } elseif ($tache['type'] == 'eval') {
                        $nbEval++;
                    }
                }
            }
        }
    }

    return ['nbEval' => $nbEval, 'nbDevoir' => $nbDevoir];
}

// Utilisation de la fonction
if ($checkedValue == 0) {
    unCheckEvent($idAgenda, $idUser);

    $countResult = countEvent($agendaMerged);
    $nbEval = $countResult['nbEval'];
    $nbDevoir = $countResult['nbDevoir'];
} else {
    checkEvent($idAgenda, $idUser);

    $countResult = countEvent($agendaMerged);
    $nbEval = $countResult['nbEval'];
    $nbDevoir = $countResult['nbDevoir'];
}





// dd($nbDevoir);



// Compter le nombre de tâches à faire
// $taches_count = count($agenda);

// foreach ($agenda as $key => $value) {
//     if ($value['id_event'] != '' OR $value['id_event'] != NULL) {
//         $taches_count--;
//     }
// }

echo json_encode(['message' => "Mise à jour effectuée avec succès!", 'nbDevoir' => $nbDevoir, 'nbEval' => $nbEval]);






// $year_here = date('o'); // Obtenez l'année actuelle au format ISO-8601
// $week_here = date('W'); // Obtenez le numéro de semaine actuel
// // Formatez la date au format "YYYY-Www"
// $current_week_year = $year_here . '-W' . $week_here;
// $today = new DateTime();
// $currentDate = date('Y-m-d');

// $edu_group_all = substr($user_sql['edu_group'], 0, 4);

// // Trouvez le lundi de la semaine actuelle
// $startDate = clone $today;
// $startDate->modify('Monday this week');

// // Créer un tableau avec les dates de la semaine du lundi au vendredi
// $week_dates = [];
// for ($i = 0; $i < 7; $i++) {
//     $week_dates[] = $startDate->format('Y-m-d');
//     $startDate->modify('+1 day');
// }

// $sqlagendacheck = "SELECT agenda.*, event_check.*, sch_subject.name_subject
// FROM agenda
// LEFT JOIN event_check ON agenda.id_user = event_check.id_user AND agenda.id_task = event_check.id_event
// JOIN sch_subject ON agenda.id_subject = sch_subject.id_subject
// WHERE (agenda.edu_group = :group OR agenda.edu_group = :edu_group_all)
// AND (agenda.type = 'devoir' OR agenda.type = 'autre')
// AND (event_check.id_user = :id_user OR event_check.id_user IS NULL)
// AND (
//     (agenda.date_finish LIKE :date1 OR agenda.date_finish LIKE :date2 OR agenda.date_finish LIKE :date3 OR agenda.date_finish LIKE :date4 OR agenda.date_finish LIKE :date5 OR agenda.date_finish LIKE :date6 OR agenda.date_finish LIKE :date7 OR agenda.date_finish = :current_week_year)
// )
// AND agenda.date_finish >= :today";

// $stmt = $dbh->prepare($sqlagendacheck);
// $stmt->execute([
//     'group' => $user_sql['edu_group'],
//     'edu_group_all' => $edu_group_all,
//     'id_user' => $user_sql['id_user'],
//     'date1' => $week_dates[0] . '%',
//     'date2' => $week_dates[1] . '%',
//     'date3' => $week_dates[2] . '%',
//     'date4' => $week_dates[3] . '%',
//     'date5' => $week_dates[4] . '%',
//     'date6' => $week_dates[5] . '%',
//     'date7' => $week_dates[6] . '%',
//     'current_week_year' => $current_week_year,
//     'today' => $today->format('Y-m-d'),
// ]);

// $agenda = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // dd($agenda);


// // Compter le nombre de tâches à faire
// $taches_count = count($agenda);

// foreach ($agenda as $key => $value) {
//     if ($value['id_event'] != '' OR $value['id_event'] != NULL) {
//         $taches_count--;
//     }
// }

?>