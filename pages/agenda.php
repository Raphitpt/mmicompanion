<!-- Script pour l'affichage des taches dans l'agenda en fonction de son TP -->
<?php
session_start();
require "../bootstrap.php";

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./accueil.php');
    exit;
}

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie

// Requete pour récupérer les taches de l'utilisateur sans recuperer les évaluations, en les triant par date de fin et par ordre alphabétique
// --------------------
$sql_agenda = "SELECT a.*, s.*
        FROM agenda a 
        JOIN sch_subject s ON a.id_subject = s.id_subject 
        WHERE a.id_user = :id_user AND a.type !='eval' AND a.type!='devoir' AND a.date_finish >= CURDATE()
        ORDER BY a.date_finish ASC, a.title ASC";

$stmt_agenda = $dbh->prepare($sql_agenda);
$stmt_agenda->execute([
    'id_user' => $users['id_user']
]);
$agenda_user = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
// --------------------
// Fin de la récupération des taches

// Requetes pour récupérer les évaluations de son TP
// --------------------
$sql_eval = "SELECT a.*, s.* FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND a.type = 'eval' AND a.date_finish >= CURDATE() ORDER BY a.date_finish ASC, a.title ASC";
$stmt_eval = $dbh->prepare($sql_eval);
$stmt_eval->execute([
    'edu_group' => $users['edu_group']
]);
$eval = $stmt_eval->fetchAll(PDO::FETCH_ASSOC);
// --------------------
// Fin de la récupération des évaluations

// Fusionne les deux tableaux pour pouvoir les afficher dans l'ordre
$agenda = array_merge($agenda_user, $eval);
$eval_cont = count($eval);
$agenda_cont = count($agenda);


// Tableaux pour traduire les dates en français
// --------------------
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
// --------------------
// Fin des tableaux pour traduire les dates en français

// Tableau pour regrouper les éléments par date
$agendaByDate = [];
$tachesNonTermineesRestantes = 0 - $eval_cont;

foreach ($agenda as $agendas) {
    if ($agendas['checked'] != 1) {
        $tachesNonTermineesRestantes++;
    }
}


// --------------------
// Récupérer les couleurs des matières

$sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
$stmt_color = $dbh->prepare($sql_color);
$stmt_color->execute();
$colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);


// Obligatoire pour afficher la page
echo head("MMI Companion - Agenda");
?>

<body class="body-all">
    <!-- Menu de navigation -->
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

        <?php generateBurgerMenuContent() ?>
    </header>

    <!-- Corps de la page -->
    <main class="main-agenda">
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
                // Systeme de compteur de taches non terminées ou terminées
                // On compte le nombre d'occurences de taches non terminées
                // Cette variable est aussi utile pour savoir si la tache est checked ou pas
                // Ca incrémente la valeur si on coche ou pas en js
                $tachesNonTerminees = 0;
                if ($tachesNonTermineesRestantes == 0) {
                    echo "<p id='compteTaches'>Aucune tache à faire</p>";
                } else if ($tachesNonTermineesRestantes == 1) {
                    echo "<p id='compteTaches'>" . $tachesNonTermineesRestantes . " tâche à faire</p>";
                } else {
                    echo "<p id='compteTaches'>" . $tachesNonTermineesRestantes . " tâches non faites</p>";
                }

                if ($eval_cont == 0) {
                    echo "<p>Aucune évaluation prévue</p>";
                } else if ($eval_cont == 1) {
                    echo "<p>" . $eval_cont . " évaluation prévue</p>";
                } else {
                    echo "<p>" . $eval_cont . " évaluations prévues</p>";
                }

                // Gère l'affichage des taches en affichant la date en français qui correspond à la date finale de la tache
                // Elle ajoute la date en français au tableau $agendaByDate qui repertorie toute les taches
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

            // Si il n'y a pas d'évènements dans l'agenda, afficher un message
            if (empty($agendaByDate)) {
                echo "<div class='agenda_content_list-agenda'>";
                echo "<h2>Aucune tâche de prévu</h2>";
                echo "</div>";
            }

            // Parcours les éléments par date et les affiche
            foreach ($agendaByDate as $date => $agendas) {
                echo "<div class='agenda_content_list-agenda'>";
                echo "<h2>$date</h2>";
                echo "<div style='height:10px'></div>";

                foreach ($agendas as $agenda) {
                    echo "<div class='agenda_content_list_item-agenda'>";
                    echo "<div class='agenda_content_list_item_flexleft-agenda'>";
                    if ($agenda['type'] == "eval") {
                        echo "<i class='fi fi-br-comment-info'></i>";
                    }
                    if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                        if ($agenda['checked'] == 1) {
                            echo "<input type='checkbox' name='checkbox' class='checkbox' data-idAgenda='" . $agenda['id_task'] . "'' checked>";
                        } else {
                            echo "<input type='checkbox' name='checkbox' class='checkbox' onclick='updatePoints(10)' data-idAgenda='" . $agenda['id_task'] . "''>";
                        }
                    }

                    echo "<div>";
                    if ($agenda['type'] == "eval") {
                        echo "<h3 class='title_subject-agenda'>[Évaluation] " . $agenda['title'] . "</h3>";
                    }
                    if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                        echo "<h3 class='title_subject-agenda'>" . $agenda['title'] . "</h3>";
                    }
                    echo "<div class='agenda_content_subject-agenda'>";
                    foreach ($colors as $color) {
                        if ($color['id_subject'] == $agenda['id_subject']) {
                            echo "<div class='circle_subject-agenda' style='background-color:" . $color['color_ressource'] . "'></div>";
                            break;
                        }
                    }
                    // echo "<div class='circle_subject-agenda' style='background-color:#" . $agenda['color'] . "'></div>";
                    echo "<p>" . $agenda['name_subject'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='agenda_content_list_item_flexright-agenda'>";
                    // Ne pas afficher la corbeille si l'utilisateur est un étudiant et que c'est une évaluation
                    if($agenda['type'] == "eval" && $users['role'] = "etudiant"){
                        echo "<i class='fi fi-br-trash red' hidden></i>";
                    } 
                    else {
                        echo "<a href='agenda_edit.php?id_user=".$users['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-pencil blue'></i></a><a href='agenda_del.php/?id_user=".$users['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-trash red'></i></a>";
                    }

                    echo "</div>";
                    echo "</div>";
                    echo "<div style='height:10px'></div>";
                }
            }
            ?>
        </div>
    </main>
    <div style="height:20px"></div>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');


        // Fonction pour mettre à jour le compteur de tâches
        function updateCompteurTaches() {
            const checkboxes = document.querySelectorAll(".checkbox");
            let compteur = <?php echo $tachesNonTermineesRestantes; ?>; // Valeur initiale du compteur

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    if (this.checked) {
                        compteur--; // Décrémenter le compteur si la tâche est cochée
                    } else {
                        compteur++; // Incrémenter le compteur si la tâche est décochée
                    }
                    // Mettre à jour l'affichage du compteur
                    if (compteur === 0) {
                        document.getElementById("compteTaches").textContent = "Aucune tâche à faire";
                    } else if (compteur === 1) {
                        document.getElementById("compteTaches").textContent = compteur + " tâche à faire";
                    } else {
                        document.getElementById("compteTaches").textContent = compteur + " tâches à faire";
                    }
                });
            });
        }

        window.addEventListener("DOMContentLoaded", function() {
            // On appel la fonction
            updateCompteurTaches();
            let checkboxes = document.querySelectorAll(".checkbox");
            checkboxes.forEach(function(checkbox) {
                // Ici on fait un requete au fichier coche_agenda.php pour mettre à jour la base de donnée lors d'une coche ou décoche
                checkbox.addEventListener("change", function() {

                    let idAgenda = this.getAttribute("data-idAgenda");
                    let checkedValue = this.checked ? 1 : 0;

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "./coche_agenda.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send("idAgenda=" + encodeURIComponent(idAgenda) + "&checked=" + encodeURIComponent(checkedValue));
                });
            });
        });

        // Vérification que la case à cocher est cochée ou non
        // Si le case est cochée, on barre le nom de la tâche et inversement

        function handleCheckboxChange() {
            let checkbox = this;
            let heading = checkbox.parentNode.querySelector(".title_subject-agenda");

            if (checkbox.checked) {
                heading.style.textDecoration = "line-through";
            } else {
                heading.style.textDecoration = "none";
            }
        }

        window.addEventListener("DOMContentLoaded", function() {
            let checkboxes = document.querySelectorAll(".checkbox");
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", handleCheckboxChange);

                // Vérification initiale de l'état de la case à cocher
                handleCheckboxChange.call(checkbox); // Appel de la fonction avec la case à cocher comme contexte
            });
        });
    </script>
</body>

</html>