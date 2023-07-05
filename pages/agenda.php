<?php
session_start();
require "../bootstrap.php";

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français


echo head("Agenda");
?>

<body>
    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Agenda</h1>
            </div>
        </div>

        <div class="burger_content-header">
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
                <a href="./todolist.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-calendar"></i>
                        <p>Agenda</p>
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
    <main>
        <!-- <div class="agenda_add">
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
                }
                ; ?>
            </select>
            <input type="submit" name="submit" value="Ajouter">
        </form>
    </div> -->
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
            ?>
            <div class="content_devoirs-agenda">
                <?php
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
                        if ($agenda['checked'] == 1) {
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
            <?php } ?>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
</body>
</html>
