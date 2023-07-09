<?php
session_start();
require "../bootstrap.php";

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

echo head("Agenda");
?>

<body class="body-agenda">

    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header" id="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Agenda</h1>
            </div>
        </div>

        <div class="burger_content-header" id="burger_content-header">
            <div style="height:60px"></div>
            <div class="burger_content_title-header">
                <img src="./../assets/img/mmicompanion.svg" alt="">
                <h1>MMI Companion</h1>
            </div>
            <div class="burger_content_content-header">
                <div class="burger_content_trait_header"></div>
                <a href="./index.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-home"></i>
                        <p>Vue d'ensemble</p>
                    </div>

                </a>
                <a href="./agenda.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-calendar"></i>
                        <p>Agenda</p>
                        <div class="select_link-header"></div>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./messages.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-comment-alt"></i>
                        <p>Messages</p>
                    </div>
                </a>
                <a href="./mail.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-envelope"></i>
                        <p>Boite mail</p>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./sante.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-doctor"></i>
                        <p>Mon bien être</p>
                    </div>
                </a>
                <a href="./profil.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-user"></i>
                        <p>Mon profil</p>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <main class="main-agenda">
        <?php
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
// Recupére les évaluations
        $sql_eval = "SELECT a.*, s.name_subject AS subject_name FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND a.type = 'eval' ORDER BY a.date_finish ASC";
        $stmt_eval = $dbh->prepare($sql_eval);
        $stmt_eval->execute([
            'edu_group' => $users['edu_group']
        ]);
// Recupére les devoirs à rendre 
        $sql_devoir = "SELECT a.*, s.name_subject AS subject_name FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND a.type = 'devoir' ORDER BY a.date_finish ASC";
        $stmt_devoir = $dbh->prepare($sql_eval);
        $stmt_devoir->execute([
            'edu_group' => $users['edu_group']
        ]);
        $devoir = $stmt_devoir->fetchAll(PDO::FETCH_ASSOC);
        $eval = $stmt_eval->fetchAll(PDO::FETCH_ASSOC);
        $agenda = array_merge($agenda_user, $eval);
        $agenda = array_merge($agenda, $devoir);
        $eval_cont = count($eval);
        $devoir_cont = count($devoir);
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
        $agendaByDate = []; // Tableau pour regrouper les éléments par date
        ?>
        <div style="height:30px"></div>
        <div class="agenda_title-agenda">
            <div class="agenda_title_flextop-agenda">
                <div class="title_trait">
                    <h1>L'agenda</h1>
                    <div></div>
                </div>

                <div class="agenda_title_flextopright-agenda">
                    <a href="./agenda_add.php">Ajouter</a>
                </div>
            </div>
            <div style="height:15px"></div>
            <div class="agenda_title_flexbottom-agenda">
                <?php
                if ($agenda_cont == 0) {
                    echo "<p>Aucune tache à faire</p>";
                } else if ($agenda_cont == 1) {
                    echo "<p>" . $agenda_cont . " tâche à faire</p>";
                } else {
                    echo "<p>" . $agenda_cont . " tâches non faites</p>";
                }

                if ($eval_cont == 0) {
                    echo "<p>Aucune évaluation prévue</p>";
                } else if ($eval_cont == 1) {
                    echo "<p>" . $eval_cont . " évaluation prévue</p>";
                } else {
                    echo "<p>" . $eval_cont . " évaluations prévues</p>";
                }
                if ($devoir_cont == 0) {
                    echo "<p>Aucun devoir prévu</p>";
                } else if ($devoir_cont == 1) {
                    echo "<p>" . $devoir_cont . " devoir prévu</p>";
                } else {
                    echo "<p>" . $devoir_cont . " devoirs prévus</p>";
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
                ?>
            </div>
        </div>
        <div style="height:25px"></div>
        <div class="agenda_content-agenda">
            <?php
            // Parcours les éléments par date et les affiche
            foreach ($agendaByDate as $date => $agendas) {
                echo "<div class='agenda_content_list-agenda'>";
                echo "<h2>$date</h2>";
                echo "<div style='height:10px'></div>";

                foreach ($agendas as $agenda) {
                    echo "<div class='agenda_content_list_item-agenda'>";
                    echo "<div class='agenda_content_list_item_flextop-agenda'>";
                    if ($agenda['checked'] == 1) {
                        echo "<input type='checkbox' name='checkbox' id='checkbox' checked>";
                    } else {
                        echo "<input type='checkbox' name='checkbox' id='checkbox'>";
                    }
                    // if ($agenda['type'] == "eval") {
                    //     echo "<h3>[Évaluation]" . $agenda['title'] . "</h3>";
                    // }
                    // if ($agenda['type'] == "devoir") {
                    //     echo "<h3>".$agenda['title'] . "</h3>";
                    // }
                    echo "<div>";
                    echo "<h3>" . $agenda['title'] . "</h3>";
                    echo "<p>" . $agenda['subject_name'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    // echo "<div class='agenda_content_list_item_flexbottom-agenda'>";
                    // echo "<p>" . $agenda['subject_name'] . "</p>";
                    // echo "</div>";
                    echo "</div>";
                    echo "<div style='height:10px'></div>";
                }
            }
            ?>
        </div>
    </main>
    <div style="height:20px"></div>
    <script src="../assets/js/menu-navigation.js"></script>
</body>

</html>