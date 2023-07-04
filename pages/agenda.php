<?php
session_start();
require "../bootstrap.php";

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français


echo head("Agenda");
?>
<main>
    <div class="agenda_list">
        <?php
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
        // var_dump($agenda);
        $semaine = array(
            " Dimanche ", " Lundi ", " Mardi ", " Mercredi ", " Jeudi ",
            " vendredi ", " samedi "
        );
        $mois = array(
            1 => " janvier ", " février ", " mars ", " avril ", " mai ", " juin ",
            " juillet ", " août ", " septembre ", " octobre ", " novembre ", " décembre "
        );
        $agendaByDate = []; // Tableau pour regrouper les éléments par date

        echo "<h1>Agenda</h1>";
        if ($agenda_cont == 0){
            echo "<p>Aucune tache à faire</p>";
        }
        else if ($agenda_cont == 1) {
            echo "<p>" . $agenda_cont . " tache à faire</p>";
        }else{
        echo "<p>" . $agenda_cont . " taches non faites</p>";
        }

        if ($eval_cont == 0){
            echo "<p>Aucune évaluation prévue</p>";
        }
        else if ($eval_cont == 1) {
            echo "<p>" . $eval_cont . " évaluation prévue</p>";
        }else{
            echo "<p>" . $eval_cont . " évaluations prévues</p>";
        }


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

        // Parcours les éléments par date et les affiche
        foreach ($agendaByDate as $date => $agendas) {
            echo "<h2>$date</h2>";

            foreach ($agendas as $agenda) {
                echo "<div class='agenda_list_item'>";
                echo "<h3>" . $agenda['title'] . "</h3>";
                if ($agenda['type'] == "eval") {
                    echo "<p>Évaluation</p>";
                }
                if ($agenda['type'] == "devoir") {
                    echo "<p>Devoir à rendre</p>";
                }
                if ($agenda['checked'] == 1){
                    echo "<input type='checkbox' name='checkbox' id='checkbox' checked>";
                } else {
                    echo "<input type='checkbox' name='checkbox' id='checkbox'>";
                }
                echo "<p>" . $agenda['subject_name'] . "</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
</main>