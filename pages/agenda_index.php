<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $edu_group = $_POST['edu_group'];
    $sql_agenda = "SELECT a.*, s.* FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND (a.type = 'devoir' OR a.type = 'eval') AND a.date_finish >= CURDATE() ORDER BY a.date_finish ASC, a.title ASC";
    $stmt_agenda = $dbh->prepare($sql_agenda);
    $stmt_agenda->execute([
        'edu_group' => $edu_group
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

    foreach ($agenda as $agendas) {
        $date = strtotime($agendas['date_finish']); // Convertit la date en timestamp
        $formattedDate = (new DateTime())->setTimestamp($date)->format('l j F'); // Formate la date
        $formattedDateFr = $semaine[date('w', $date)] . date('j', $date) . $mois[date('n', $date)]; // Traduit la date en français

        // Ajoute l'élément à l'array correspondant à la date
        if (!isset($agendaByDate[$formattedDateFr])) {
            $agendaByDate[$formattedDateFr] = [];
        }
        $agendaByDate[$formattedDateFr][] = $agendas;
    }

    // Créer un tableau de réponse JSON
    $agenda_html = "";

    foreach ($agendaByDate as $date => $agendas) {
        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'type' => $agenda['type'],
                'title' => $agenda['title'],
                'name_subject' => $agenda['name_subject'],
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
            $html .= "<div>";
            if ($event['type'] == "eval") {
                $html .= "<h3 class='title_subject-agenda'>[Évaluation] " . $event['title'] . "</h3>";
            }
            if ($event['type'] == "devoir" or $event['type'] == "autre") {
                $html .= "<h3 class='title_subject-agenda'>" . $event['title'] . "</h3>";
            }
            $html .= "<div class='agenda_content_subject-agenda'>";
            $html .= "<div class='container_circle_subject-agenda'>";
            foreach ($colors as $color) {
                if ($color['id_subject'] == $agenda['id_subject']) {
                    $html .= "<div class='circle_subject-agenda' style='background-color:" . $color['color_ressource'] . "'></div>";
                    break;
                }
            }
            $html .= "</div>";
            $html .= "<p>" . $event['name_subject'] . "</p>";
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
?>