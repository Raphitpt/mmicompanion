<?php
session_start();
require "../bootstrap.php";
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);

if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date']) && !empty($_POST['school_subject'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $school_subject = $_POST['school_subject'];
    $eval = $_POST['eval'];
    $devoir_rendre = $_POST['devoir_rendre'];
    $sql = "INSERT INTO agenda (title, date_finish, eval, devoir_rendre, id_user, id_subject) VALUES (:title, :date, :eval, :devoir_rendre, :id_user, :id_subject)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'id_user' => $users['id_user'],
        'eval' => $eval,
        'devoir_rendre' => $devoir_rendre,
        'id_subject' => $school_subject
    ]);
    echo json_encode(['status' => 'success']);
    exit();
}

$sql_subject = "SELECT * FROM sch_subject";
$stmt_subject = $dbh->prepare($sql_subject);
$stmt_subject->execute();
$subject = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);

$sql_agenda = "SELECT a.*, s.name_subject AS subject_name 
        FROM agenda a 
        JOIN sch_subject s ON a.id_subject = s.id_subject 
        WHERE a.id_user = :id_user 
        ORDER BY a.date_finish ASC";

$stmt_agenda = $dbh->prepare($sql_agenda);
$stmt_agenda->execute([
    'id_user' => $users['id_user']
]);
$agenda_user = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);

$sql_eval = "SELECT a.*, s.name_subject AS subject_name
        FROM agenda a
        JOIN sch_subject s ON a.id_subject = s.id_subject
        WHERE a.edu_group = :edu_group AND a.type = 'eval' OR a.type = 'devoir'
        ORDER BY a.date_finish ASC";
$stmt_eval = $dbh->prepare($sql_eval);
$stmt_eval->execute([
    'edu_group' => $users['edu_group']
]);
$eval = $stmt_eval->fetchAll(PDO::FETCH_ASSOC);
$agenda = array_merge($agenda_user, $eval);
$eval_cont = count($eval);
$agenda_cont = count($agenda);

$semaine = array(
    " Dimanche ",
    " Lundi ",
    " Mardi ",
    " Mercredi ",
    " Jeudi ",
    " vendredi ",
    " samedi "
);
$mois = array(
    1 => " janvier ",
    " février ",
    " mars ",
    " avril ",
    " mai ",
    " juin ",
    " juillet ",
    " août ",
    " septembre ",
    " octobre ",
    " novembre ",
    " décembre "
);
$agendaByDate = [];

if ($agenda_cont == 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Aucune tache à faire',
        'eval_count' => $eval_cont,
        'agenda_count' => $agenda_cont
    ]);
} else {
    foreach ($agenda as $agendas) {
        $date = strtotime($agendas['date_finish']);
        $formattedDateFr = $semaine[date('w', $date)] . date('j', $date) . $mois[date('n', $date)];

        if (!isset($agendaByDate[$formattedDateFr])) {
            $agendaByDate[$formattedDateFr] = [];
        }
        $agendaByDate[$formattedDateFr][] = $agendas;
    }

    $agendaHTML = '';
    foreach ($agendaByDate as $date => $agendas) {
        $agendaHTML .= "<h2>$date</h2>";

        foreach ($agendas as $agenda) {
            $agendaHTML .= "<div class='agenda_list_item'>";
            $agendaHTML .= "<h3>" . $agenda['title'] . "</h3>";
            if ($agenda['type'] == "eval") {
                $agendaHTML .= "<p>Évaluation</p>";
            }
            if ($agenda['type'] == "devoir") {
                $agendaHTML .= "<p>Devoir à rendre</p>";
            }
            if ($agenda['checked'] == 1) {
                $agendaHTML .= "<input type='checkbox' name='checkbox' id='checkbox' checked>";
            } else {
                $agendaHTML .= "<input type='checkbox' name='checkbox' id='checkbox'>";
            }
            $agendaHTML .= "<p>" . $agenda['subject_name'] . "</p>";
            $agendaHTML .= "</div>";
        }
    }

    echo json_encode([
        'status' => 'success',
        'agenda_html' => $agendaHTML,
        'eval_count' => $eval_cont,
        'agenda_count' => $agenda_cont
    ]);
}
?>