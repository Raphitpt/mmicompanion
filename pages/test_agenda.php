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
        WHERE a.id_user = :id_user AND a.type !='eval' AND a.type!='devoir'
        ORDER BY a.date_finish ASC";

$stmt_agenda = $dbh->prepare($sql_agenda);
$stmt_agenda->execute([
    'id_user' => $users['id_user']
]);
$agenda_user = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);

$sql_eval = "SELECT a.*, s.name_subject AS subject_name
FROM agenda a
JOIN sch_subject s ON a.id_subject = s.id_subject
WHERE a.edu_group = :edu_group 
AND (a.type = 'eval' OR a.type = 'devoir')
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
$html = '';
$html .= '
    <div style="height:30px"></div>
    <div class="agenda_title-agenda">
        <div class="agenda_title_flextop-agenda">
            <div class="title_trait">
                <h1>L\'agenda</h1>
                <div></div>
            </div>

            <div class="agenda_title_flextopright-agenda">
                <a href="./agenda_add.php">Ajouter</a>
            </div>
        </div>
        <div style="height:15px"></div>
        <div class="agenda_title_flexbottom-agenda">';

            if ($agenda_cont == 0) {
                $html .= "<p>Aucune tache à faire</p>";
            } else if ($agenda_cont == 1) {
                $html .= "<p>" . $agenda_cont . " tâche à faire</p>";
            } else {
$html .= "<p>" . $agenda_cont . " tâches non faites</p>";
            }

            if ($eval_cont == 0) {
                $html .= "<p>Aucune évaluation prévue</p>";
            } else if ($eval_cont == 1) {
                $html .= "<p>" . $eval_cont . " évaluation prévue</p>";
            } else {
                $html .= "<p>" . $eval_cont . " évaluations prévues</p>";
            }

    foreach ($agenda as $agendas) {
        $date = strtotime($agendas['date_finish']);
        $formattedDateFr = $semaine[date('w', $date)] . date('j', $date) . $mois[date('n', $date)];

        if (!isset($agendaByDate[$formattedDateFr])) {
            $agendaByDate[$formattedDateFr] = [];
        }
        $agendaByDate[$formattedDateFr][] = $agendas;
    }

    $html .= '</div>
    </div>
    <div style="height:25px"></div>
    <div class="agenda_content-agenda">';

    foreach ($agendaByDate as $date => $agendas) {
                $html .= '<div class="agenda_content_list-agenda">';
        $html .= '<h2>' . $date . '</h2>';
        $html .= '<div style="height:10px"></div>';

        foreach ($agendas as $agenda) {
                        $html .= '<div class="agenda_content_list_item-agenda">';
            $html .= '<div class="agenda_content_list_item_flextop-agenda">';
            if ($agenda['checked'] == 1) {
                $html .= '<input type="checkbox" name="checkbox" id="checkbox" checked>';
            } else {
                $html .= '<input type="checkbox" name="checkbox" id="checkbox">';
            }
            $html .= '<div>';
            $html .= '<h3>' . $agenda['title'] . '</h3>';
            $html .= '<p>' . $agenda['subject_name'] . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div style="height:10px"></div>';
        }
        $html .= '</div>';
    }
$html .= '
    </div>
</main>
';
    echo $html;
