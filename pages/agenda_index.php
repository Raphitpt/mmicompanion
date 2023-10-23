<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $edu_group = $_POST['edu_group'];
    $edu_group_all = substr($_POST['edu_group'], 0, 4);
    $current_week_year = date('o-W');
    $today = new DateTime();
    $sql_agenda = "SELECT a.*, s.*, u.name, u.pname, u.role FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject JOIN users u ON a.id_user = u.id_user WHERE (a.edu_group = :edu_group OR a.edu_group = :edu_group_all) AND (a.type = 'devoir' OR a.type = 'eval') AND (DATE_FORMAT(STR_TO_DATE(a.date_finish, '%X-W%V'), '%o-%W') = :current_week_year OR a.date_finish >= :current_date) ORDER BY a.date_finish ASC, a.title ASC";
    $stmt_agenda = $dbh->prepare($sql_agenda);
    $stmt_agenda->execute([
        'edu_group' => $edu_group,
        'edu_group_all' => $edu_group_all,
        'current_week_year' => $current_week_year,
        'current_date' => $today->format('Y-m-d')
    ]);
    $agenda = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
    $agendaByDate = [];
    // Tableaux pour traduire les dates en français
    $semaine = array(
        " Dimanche ",
        " Lundi ",
        " Mardi ",
        " Mercredi ",
        " Jeudi ",
        " Vendredi ",
        " Samedi "
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

    $sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
    $stmt_color = $dbh->prepare($sql_color);
    $stmt_color->execute();
    $colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);


    $agendaByWeek = [];
    $agendaByDate = [];

    foreach ($agenda as $agendas) {
        $date = strtotime($agendas['date_finish']); // Convertit la date en timestamp

        if (preg_match('/^\d{4}-W\d{2}$/', $agendas['date_finish'])) {
            // Si la date est au format "YYYY-Www", extrayez l'année et le numéro de semaine
            $year = intval(substr($agendas['date_finish'], 0, 4));
            $week = intval(substr($agendas['date_finish'], -2));
            $formattedDateFr = "Semaine $week";

            if (!isset($agendaByWeek[$formattedDateFr])) {
                $agendaByWeek[$formattedDateFr] = [];
            }
            $agendaByWeek[$formattedDateFr][] = $agendas;
        } else {
            // Si la date n'est pas au format "YYYY-Www", formatez-la en français
            $formattedDateFr = $semaine[date('w', $date)] . date('j', $date) . $mois[date('n', $date)];

            if (!isset($agendaByDate[$formattedDateFr])) {
                $agendaByDate[$formattedDateFr] = [];
            }
            $agendaByDate[$formattedDateFr][] = $agendas;
        }
    }
    $agendaMerged = $agendaByWeek + $agendaByDate;

    // Créer un tableau de réponse JSON
    $agenda_html = "";
    foreach ($agendaMerged as $date => $agendas) {
        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'id_task' => $agenda['id_task'],
                'type' => $agenda['type'],
                'id_subject' => $agenda['id_subject'],
                'title' => $agenda['title'],
                'name_subject' => $agenda['name_subject'],
                'name' => $agenda['name'],
                'pname' => $agenda['pname'],
                'role' => $agenda['role'],
                'content' => $agenda['content'],
                'color' => '', // Vous pouvez ajouter la couleur ici si nécessaire
            ];

            // Ne pas afficher la corbeille si l'utilisateur est un étudiant et que c'est une évaluation
            // if (($agenda['type'] == "eval" || $agenda['type'] == "devoir")) {
            //     $event['trash'] = false;
            // } elseif ($users['role'] == "admin" || $users['role'] == "chef") {
            //     $event['edit_link'] = 'agenda_edit.php?id_user=' . $agenda['id_user'] . '&id_task=' . $agenda['id_task'];
            //     $event['delete_link'] = 'agenda_del.php/?id_user=' . $users['id_user'] . '&id_task=' . $agenda['id_task'];
            // } else {
            //     $event['edit_link'] = 'agenda_edit.php?id_user=' . $users['id_user'] . '&id_task=' . $agenda['id_task'];
            //     $event['delete_link'] = 'agenda_del.php/?id_user=' . $users['id_user'] . '&id_task=' . $agenda['id_task'];
            // }

            $events[] = $event;
        }

        // Créer la structure HTML pour chaque date
        $html = "<div class='agenda_content_list-agenda'>";
        $html .= "<h2>$date</h2>";
        $html .= "<div style='height:10px'></div>";

        foreach ($events as $event) {
            $html .= "<div class='agenda_content_list_item-agenda'>";
            $html .= "<div class='agenda_content_list_item_flexleft-agenda'>";
            if ($event['type'] == "eval") {
                $html .= "<i class='fi fi-br-comment-info'></i>";
            }
            $html .= "<div class='agenda_title_content_list_item_flexleft-agenda'>";
            if ($event['type'] == "eval") {
                $html .= "<label for='checkbox-" . $event['id_task'] . "' class='title_subject-agenda'>[Évaluation] " . $event['title'] . "</label>";
            }
            if ($event['type'] == "devoir" or $event['type'] == "autre") {
                $html .= "<label for='checkbox-" . $event['id_task'] . "' class='title_subject-agenda'>" . $event['title'] . "</label>";
            }
            if ($event['content'] != "") {
                $html .= "<p class='content'><span>" . $event['content'] . "</span></p>";
            }
            $html .= "<div class='agenda_content_subject-agenda'>";
            if ($event['role'] == "prof") {
                $html .= "<p class='name_subject-agenda'>De : <span>" . substr($event['pname'], 0, 1) . '. ' . $event['name'] . "</span></p></br>";
            }
            foreach ($colors as $color) {
                if ($color['id_subject'] == $event['id_subject']) {
                    $html .= "<p style='background-color:" . $color['color_ressource'] . "'>" . $event['name_subject'] . "</p>";
                    break;
                }
            }
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "<div class='agenda_content_list_item_flexright-agenda'>";
            // if (isset($event['trash']) && $event['trash'] === false) {
            //     $html .= "<i class='fi fi-br-trash red' hidden></i>";
            // } else {
            //     $html .= "<a href='" . $event['edit_link'] . "'><i class='fi fi-br-pencil blue'></i></a><a href='" . $event['delete_link'] . "'><i class='fi fi-br-trash red'></i></a>";
            // }
            $html .= "</div>";
            $html .= "</div>";
            $html .= "<div style='height:10px'></div>";
        }

        $html .= "</div>";

        $agenda_html .= $html;
    }
    $response = array(
        'viewChef' => viewChef($dbh, $edu_group),
        'agendaHtml' => $agenda_html
    );
    echo json_encode($response);
}
