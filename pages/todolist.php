<?php
session_start();
require "../bootstrap.php";
echo head("Agenda");
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français

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
    header('Location: ./agenda.php');
    exit();
}




$sql_subject = "SELECT * FROM sch_subject";
$stmt_subject = $dbh->prepare($sql_subject);
$stmt_subject->execute();
$subject = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);



?>
<main>
    <div class="agenda_add">
        <form method="POST" action="">
            <input type="text" name="title" placeholder="Titre de l'évènement">
            <input type="date" name="date" placeholder="Date de l'évènement">
            <label for="eval">Évaluation</label>
            <input type="hidden" name="eval" value="0">
            <input type="checkbox" name="eval" value="1">
            <label for="devoir_rendre">Devoir à rendre</label>
            <input type="hidden" name="devoir_rendre" value="0">
            <input type="checkbox" name="devoir_rendre" value="1">
            <select name="school_subject">
                <?php
                foreach ($subject as $subjects) {
                    echo "<option value='" . $subjects['id_subject'] . "'>" . $subjects['name_subject'] . "</option>";
                }; ?>
            </select>
            <input type="submit" name="submit" value="Ajouter">
        </form>
    </div>
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
        $agenda = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
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
                if ($agenda['eval'] == 1) {
                    echo "<p>Évaluation</p>";
                }
                if ($agenda['devoir_rendre'] == 1) {
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