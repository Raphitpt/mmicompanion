<?php
session_start();
require '../bootstrap.php';
$user = onConnect($dbh);

// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $html = "<div class='item_content-agenda'>";
        $html .= "<h2>$date</h2>";
        $html .= "<div class='ligne_agenda'></div>";
        $html .= "<div style='height:10px'></div>";

        foreach ($events as $event) {
            $html .= "<div class='item_list-agenda'>";
            $html .= "<div class='item_list_flexleft-agenda'>";

            if ($event['type'] == "eval") {
                $html .= "<i class='fi fi-br-comment-info'></i>";
            }
            // Affichage de la coche ou de l'indication rouge si c'est une évaluation
            if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                $html .= "<i class='fi fi-br-comment-info' style='visibility: hidden;'></i>";
            }

            $html .= "<div class='content_item_list_flexleft-agenda'>";

            foreach ($colors as $color) {
                if ($color['id_subject'] == $event['id_subject']) {
                    $html .= "<div class='header_title_subject-agenda'>";
                    $html .= "<div class='circle_subject-agenda' style='background-color:" . $color['color_ressource'] . "'></div>";
                    if ($agenda['type'] == "eval") {
                        $html .= "<p class='subject-agenda'>[Évaluation] " . $agenda['name_subject'] . "</p>";
                    } else {
                        $html .= "<p class='subject-agenda'>" . $agenda['name_subject'] . "</p>";
                    }

                    $html .= "</div>";
                    break;
                }
            }
            // Affichage du tire de l'event de l'agenda
            $html .= "<label for='checkbox-" . $agenda['id_task'] . "' class='title_subject-agenda'>" . $agenda['title'] . "</label>";

            // Affichage du contenu de l'event de l'agenda
            $html .= "<div class='description_item_list_flexleft-agenda'>";
            if (isset($agenda['content']) && !empty($agenda['content'])) {
                $html .= $agenda['content'];
            }
            $html .= "</div>";
            $html .= "<div class='author_item_list_flexleft-agenda'>";
            if ($event['role'] == "prof") {
                $html .= "<p class='name_subject-agenda'>De : <span>" . substr($event['pname'], 0, 1) . '. ' . $event['name'] . "</span></p></br>";
            }
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            
            $html .= "<div class='item_list_flexright-agenda'>";
            $html .= "<div class='menu_dropdown_item_list_flexright-agenda'>";
            $html .= "<i class='fi fi-br-menu-dots'></i>";
            // $html .= "<span class=''></span>";
            // $html .= "<span class='button_circle_dropdown-agenda'></span>";
            // $html .= "<span class='button_circle_dropdown-agenda'></span>";

            
            $html .= "<div class='content_menu_dropdown_item_list_flexright-agenda'>"; // Début du dropdown menu container

            // Condition pour afficher le bouton edit et delete en fonction du role de l'utilisateur
            if ($user_sql['role'] == "prof") {
                $html .= "<a href='agenda_edit.php?id_user=" . $agenda['id_user'] . "&id_task=" . $agenda['id_task'] . "'class='blue'><i class='fi fi-br-pencil blue'></i>Éditer</a>";
                $html .= "<a href='agenda_del.php/?id_user=" . $agenda['id_user'] . "&id_task=" . $agenda['id_task'] . "' id='delete-trash' class='red'><i class='fi fi-br-trash red'></i>Supprimer</a>";
            }

            $html .= "</div>"; // Fin du dropdown menu container
            $html .= "</div>"; // Fin du dropdown menu
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
