<!-- Script pour l'affichage des taches dans l'agenda en fonction de son TP -->
<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
if ($user['role'] == "prof") {

    header('Location: ./calendar.php');
    exit;
}

// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

$year_here = date('o'); // Obtenez l'année actuelle au format ISO-8601
$week_here = date('W'); // Obtenez le numéro de semaine actuel
// Formatez la date au format "YYYY-Www"
$current_week_year = $year_here . '-W' . $week_here;
$today = new DateTime();
// Requete pour récupérer les taches de l'utilisateur sans recuperer les évaluations, en les triant par date de fin et par ordre alphabétique
$edu_group_all = substr($user_sql['edu_group'], 0, 4);
// --------------------
$sql_agenda = "SELECT a.*, s.*
FROM agenda a
JOIN sch_subject s ON a.id_subject = s.id_subject
WHERE a.id_user = :id_user
  AND a.type != 'eval'
  AND a.type != 'devoir'
  AND (
    (a.date_finish LIKE '____-__-__' AND a.date_finish >= :current_date)
    OR
    (a.date_finish LIKE '____-W__' AND a.date_finish >= :current_week_year)
  )
ORDER BY a.title ASC;
";

$stmt_agenda = $dbh->prepare($sql_agenda);
$stmt_agenda->execute([
    'id_user' => $user['id_user'],
    'current_week_year' => $current_week_year,
    'current_date' => $today->format('Y-m-d')
]);
$agenda_user = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
// --------------------
// Fin de la récupération des taches

// Requetes pour récupérer les évaluations de son TP
// --------------------

$sql_eval = "SELECT a.*, s.*, u.name, u.pname, u.role 
FROM agenda a 
JOIN sch_subject s ON a.id_subject = s.id_subject 
JOIN users u ON a.id_user = u.id_user 
WHERE (a.edu_group = :edu_group OR a.edu_group = :edu_group_all) 
AND a.type = 'eval' 
AND (
    (a.date_finish LIKE '____-__-__' AND a.date_finish >= :current_date)
    OR
    (a.date_finish LIKE '____-W__' AND a.date_finish >= :current_week_year)
  ) 
ORDER BY a.title ASC";


$stmt_eval = $dbh->prepare($sql_eval);
$stmt_eval->execute([
    'edu_group' => $user_sql['edu_group'],
    'edu_group_all' => $edu_group_all,
    'current_week_year' => $current_week_year,
    'current_date' => $today->format('Y-m-d')
]);
$eval = $stmt_eval->fetchAll(PDO::FETCH_ASSOC);

// --------------------
// Fin de la récupération des évaluations

// Fusionne les deux tableaux pour pouvoir les afficher dans l'ordre
$sql_devoir = "SELECT a.*, s.*, u.name, u.pname, u.role FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject JOIN users u ON a.id_user = u.id_user WHERE (a.edu_group = :edu_group OR a.edu_group = :edu_group_all) AND a.type = 'devoir'AND ((a.date_finish LIKE '____-__-__' AND a.date_finish >= :current_date)OR(a.date_finish LIKE '____-W__' AND a.date_finish >= :current_week_year)) ORDER BY a.title ASC";
$stmt_devoir = $dbh->prepare($sql_devoir);
$stmt_devoir->execute([
    'edu_group' => $user_sql['edu_group'],
    'edu_group_all' => $edu_group_all,
    'current_week_year' => $current_week_year,
    'current_date' => $today->format('Y-m-d')
]);
$devoir = $stmt_devoir->fetchAll(PDO::FETCH_ASSOC);

$agenda = array_merge($agenda_user, $eval);
$agenda = array_merge($agenda, $devoir);
$eval_cont = count($eval);
$agenda_cont = count($agenda);


$sql_chef = "SELECT pname, name FROM users WHERE edu_group = :edu_group AND role LIKE '%chef%'";
$stmt_chef = $dbh->prepare($sql_chef);
$stmt_chef->execute([
    'edu_group' => $user_sql['edu_group']
]);
$chef = $stmt_chef->fetch(PDO::FETCH_ASSOC);

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



// --------------------
// Récupérer les couleurs des matières

$sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
$stmt_color = $dbh->prepare($sql_color);
$stmt_color->execute();
$colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);


// --------------------

// On récupère les données du formulaire du tutoriel pour ajouter l'année et le tp de l'utilisateur à la base de données
if (isset($_POST['button-validate'])) {
    $update_user = "UPDATE users SET tuto_agenda = 1 WHERE id_user = :id_user";
    $stmt = $dbh->prepare($update_user);
    $stmt->execute([
        'id_user' => $user['id_user']
    ]);
    header('Location: ./agenda.php');
    exit();
}


// Obligatoire pour afficher la page
echo head("MMI Companion | Agenda");
?>
<!-- Mise en place du tutoriel -->
<?php
if ($user_sql['tuto_agenda'] == 0) { ?>

    <body class="body-tuto_agenda">
        <!-- Menu de navigation -->
        <?php generateBurgerMenuContent($user_sql['role'], 'Agenda') ?>

        <main class="main_tuto-agenda">
            <form action="" method="post" class="form-tuto_agenda">
                <div class="title_tuto-agenda">
                    <img src="./../assets/img/agenda_emoji.png" alt="Emoji d'un livre">
                    <h1>Comment fonctionne l’agenda ?</h1>
                </div>
                <p class="p_trait-agenda">Dans chaque TP, un.e étudiant.e est chargé.e d’ajouter les devoirs à l’agenda et de le maintenir à jour pour les autres étudiant.e.s</p>
                <p>On vous invite à discuter entre vous pour déterminer l’étudiant.e qui sera chargée de mettre à jour l’agenda.
                    <br>Par la suite, l’étudiant.e volontaire doit nous contacter pour qu’on lui attribue son rôle.
                </p>
                <div class="title_content_tuto-agenda">
                    <img src="./../assets/img/hand-pointing_emoji.png" alt="Emoji d'une main qui pointe vers le texte">
                    <h2>Peut-on changer l’étudiant.e ?</h2>
                </div>
                <p>OUI ! Et c’est l’objectif. Une fois que l’on a attribué une première fois le rôle, l’étudiant.e verra une option dans la page profil pour transmettre son rôle à un.e autre étudiant.e volontaire.</p>
                <p><span style="font-weight:700">Petit tips :</span> tu peux ajouter des tâches personnelles que seul toi verra en plus des tâches de l’agenda de ton TP.</p>
                <div class="container_button_tuto-agenda">
                    <input type="submit" id="button_tuto_agenda-validate" class="button_tuto-agenda" name="button-validate" value="Compris">
                </div>
            </form>
          </main>

    </body>

      <script src="../assets/js/menu-navigation.js?v=1.1"></script> 
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');
    </script>
<?php
} else {

?>

    <body class="body-all">
        <!-- Menu de navigation -->
        <?php generateBurgerMenuContent($user_sql['role'], 'Agenda') ?>

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
                    echo "<p style='font-weight: bold;'>Groupe : " . $user_sql['edu_group'] . "</p>";
                    if (!empty($chef)) {
                        echo "<p style='font-weight: bold;'>Responsable : " . $chef['pname'] . " " . $chef['name'] . "</p>";
                    } else {
                        echo "<p style='font-weight: bold;'>Responsable : Aucun</p>";
                    }
                    ?>
                    <div style="height:15px"></div>
                    <p id='compteTaches'></p>
                    <?php
                    // Systeme de compteur de taches non terminées ou terminées
                    // On compte le nombre d'occurences de taches non terminées
                    // Cette variable est aussi utile pour savoir si la tache est checked ou pas
                    // Ca incrémente la valeur si on coche ou pas en js


                    if ($eval_cont == 0) {
                        echo "<p>Aucune évaluation prévue</p>";
                    } else if ($eval_cont == 1) {
                        echo "<p>" . $eval_cont . " évaluation prévue</p>";
                    } else {
                        echo "<p>" . $eval_cont . " évaluations prévues</p>";
                    }

                    // Gère l'affichage des taches en affichant la date en français qui correspond à la date finale de la tache
                    // Elle ajoute la date en français au tableau $agendaByDate qui repertorie toute les taches
                    // dd($agenda);
                    $agendaMerged = [];

                    // Obtenez la date d'aujourd'hui au format Y-m-d
                    $currentDate = date('Y-m-d');

                    usort($agenda, 'compareDates');

                    foreach ($agenda as $agendas) {
                        $date = strtotime($agendas['date_finish']); // Convertit la date en timestamp

                        if (preg_match('/^\d{4}-W\d{2}$/', $agendas['date_finish'])) {
                            // Si la date est au format "YYYY-Www", extrayez l'année et le numéro de semaine
                            $week = intval(substr($agendas['date_finish'], -2));
                            $formattedDateFr = "Semaine $week";

                            // Vérifiez si c'est la semaine actuelle
                            if ($agendas['date_finish'] == $current_week_year) {
                                $formattedDateFr = "Cette semaine";
                            }
                        } else {
                            // Si la date n'est pas au format "YYYY-Www", formatez-la en français
                            $formattedDateFr = $semaine[date('w', $date)] . date('j', $date) . $mois[date('n', $date)];

                            // Vérifiez si c'est aujourd'hui
                            if ($agendas['date_finish'] == $currentDate) {
                                $formattedDateFr = "Aujourd'hui";
                            }

                            // Vérifiez si c'est demain
                            $tomorrowDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                            if ($agendas['date_finish'] == $tomorrowDate) {
                                $formattedDateFr = "Demain";
                            }
                        }

                        // Utilisez la date formatée en tant que clé pour stocker les éléments dans un tableau unique
                        if (!isset($agendaMerged[$formattedDateFr])) {
                            $agendaMerged[$formattedDateFr] = [];
                        }
                        $agendaMerged[$formattedDateFr][] = $agendas;
                    }

                    ?>
                </div>
            </div>
            <div style="height:25px"></div>
            <div class="agenda_content-agenda">
                <?php

                // Si il n'y a pas d'évènements dans l'agenda, afficher un message
                if (empty($agendaMerged)) {
                    echo "<div class='agenda_content_list-agenda'>";
                    echo "<h2>Aucune tâche de prévu</h2>";
                    echo "</div>";
                }

                // Parcours les éléments par date et les affiche
                foreach ($agendaMerged as $date => $agendas) {
                    echo "<div class='agenda_content_list-agenda'>";
                    // affichage de la date et de son trait
                    echo "<h2>$date</h2>";
                    echo "<div class='ligne_agenda'></div>";
                    echo "<div style='height:10px'></div>";

                    foreach ($agendas as $agenda) {
                        echo "<div class='agenda_content_list_item-agenda'>";
                        echo "<div class='agenda_content_list_item_flexleft-agenda'>";

                        if ($agenda['type'] == "eval") {
                            echo "<i class='fi fi-br-comment-info'></i>";
                        }
                        // Affichage de la coche ou de l'indication rouge si c'est une évaluation
                        if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                            if (getEventCheckedStatus($dbh, $agenda['id_task'], $user['id_user']) == 1) {
                                echo "<input type='checkbox' name='checkbox' class='checkbox' id='checkbox-" . $agenda['id_task'] . "' data-idAgenda='" . $agenda['id_task'] . "'' checked>";
                            } else {
                                echo "<input type='checkbox' name='checkbox' class='checkbox' id='checkbox-" . $agenda['id_task'] . "' onclick='updatePoints(10)' data-idAgenda='" . $agenda['id_task'] . "''>";
                            }
                        }

                        echo "<div class='agenda_title_content_list_item_flexleft-agenda'>";
                        // Affichage de la matière de l'event de l'agenda et la couleur associée ainsi que évaluation devant
                        foreach ($colors as $color) {
                            if ($color['id_subject'] == $agenda['id_subject']) {
                                echo "<div class='header_title_subject-agenda'>";
                                echo "<div class='circle_subject-agenda' style='background-color:" . $color['color_ressource'] . "'></div>";
                                if ($agenda['type'] == "eval") {
                                    echo "<p class='subject-agenda'>[Évaluation] " . $agenda['name_subject'] . "</p>";
                                } else {
                                    echo "<p class='subject-agenda'>" . $agenda['name_subject'] . "</p>";
                                }

                                echo "</div>";
                                break;
                            }
                        };
                        // Affichage du tire de l'event de l'agenda
                        echo "<label for='checkbox-" . $agenda['id_task'] . "' class='title_subject-agenda'>" . $agenda['title'] . "</label>";

                        // Affichage du contenu de l'event de l'agenda
                        echo "<div class='agenda_description-agenda'>";
                        if (isset($agenda['content']) && !empty($agenda['content'])) {
                            echo $agenda['content'];
                        }
                        echo "</div>";
                        echo "<div class='agenda_content_subject-agenda'>";

                        // Affichage du nom du professeur qui a ajouté l'event de l'agenda, si il y a
                        if (isset($agenda['role']) && $agenda['role'] == "prof") {
                            echo "<p class='name_subject-agenda'>De : <span>" . substr($agenda['pname'], 0, 1) . '. ' . $agenda['name'] . "</span></p></br>";
                        }
                        // echo "<div class='circle_subject-agenda' style='background-color:#" . $agenda['color'] . "'></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='agenda_content_list_item_flexright-agenda'>";
                        echo "<div class='agenda_dropdown_menu_edit-agenda'>";
                        if (($agenda['type'] == "eval" || $agenda['type'] == "devoir") && str_contains($user_sql['role'], 'eleve')){
                            echo "";
                        } else{
                        echo "<span class='button_circle_dropdown-agenda'></span>";
                        echo "<span class='button_circle_dropdown-agenda'></span>";
                        echo "<span class='button_circle_dropdown-agenda'></span>";
                        }
                        
                        echo "<div class='dropdown-content'>"; // Début du dropdown menu container

                        // Condition pour afficher le bouton edit et delete en fonction du role de l'utilisateur
                        if (($agenda['type'] == "eval" || $agenda['type'] == "devoir") && str_contains($user_sql['role'], 'eleve')) {
                            echo "<i class='fi fi-br-trash red' hidden></i>";
                        } elseif ($user_sql['role'] == "admin" || $user_sql['role'] == "chef") {
                            echo "<a href='agenda_edit.php?id_user=" . $agenda['id_user'] . "&id_task=" . $agenda['id_task'] . "'class='blue'><i class='fi fi-br-pencil blue'></i>Éditer</a>";
                            echo "<a href='agenda_del.php/?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "' id='delete-trash' class='red'><i class='fi fi-br-trash red'></i>Supprimer</a>";
                        } else {
                            echo "<a href='agenda_edit.php?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "'class='blue'><i class='fi fi-br-pencil blue'></i>Éditer</a>";
                            echo "<a href='agenda_del.php/?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "' id='delete-trash'class='red'><i class='fi fi-br-trash red'></i>Supprimer</a>";
                        }

                        echo "</div>"; // Fin du dropdown menu container
                        echo "</div>"; // Fin du dropdown menu
                        echo "</div>";
                        echo "</div>";
                        echo "<div style='height:10px'></div>";
                    }

                    echo "</div>";
                }
                ?>
            </div>
            
            <canvas id="fireworks"></canvas>

            <div style="height:20px"></div>
        </main>

          <script src="../assets/js/menu-navigation.js?v=1.1"></script> 
        <script src="../assets/js/fireworks.js"></script>
        

        <script>

            // Faire apparaître le background dans le menu burger
            let select_background_profil = document.querySelector('#select_background_agenda-header');
            select_background_profil.classList.add('select_link-header');
            const deleteTrash = document.querySelectorAll('#delete-trash');

            deleteTrash.forEach(function(trash) {
                trash.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm("Voulez-vous vraiment supprimer cette tâche ?")) {
                        window.location.href = this.getAttribute('href');
                    }
                });
            });

            // Fonction pour mettre à jour le compteur de tâches

            const resultParagraph = document.getElementById('compteTaches');
            let checkboxes = document.querySelectorAll(".checkbox");

            function countChecked() {
                let count = 0;

                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        count++;
                    }
                });
                if (count === 0) {
                    resultParagraph.textContent = `Aucune tâche à faire`;
                } else if (count === 1) {
                    resultParagraph.textContent = `${count} tâche à faire`;
                } else {
                    resultParagraph.textContent = `${count} tâches à faire`;
                }
            }


            window.addEventListener("DOMContentLoaded", function() {

                const resultParagraph = document.getElementById('compteTaches');
                let checkboxes = document.querySelectorAll(".checkbox");
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', countChecked);

                    // Vérification initiale de l'état de la case à cocher
                    handleCheckboxChange.call(checkbox); // Appel de la fonction avec la case à cocher comme contexte
                });

                // Appel initial pour afficher le nombre de tâches au chargement de la page
                countChecked();
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
                        xhr.send("idAgenda=" + encodeURIComponent(idAgenda) + "&checked=" + encodeURIComponent(checkedValue) + "&id_user=" + encodeURIComponent(<?php echo $user['id_user']; ?>));
                    });
                });

                let dropdowns = document.querySelectorAll(".agenda_dropdown_menu_edit-agenda");

                dropdowns.forEach(function(dropdown) {
                    dropdown.addEventListener("click", function(event) {
                        event.stopPropagation(); // Empêche la propagation de l'événement de clic à la fenêtre
                        let dropdownContent = dropdown.querySelector(".dropdown-content");
                        dropdownContent.style.display = (dropdownContent.style.display === "block") ? "none" : "block";
                    });
                });

                // Ferme le menu déroulant lors d'un clic à l'extérieur de celui-ci
                window.addEventListener("click", function(event) {
                    dropdowns.forEach(function(dropdown) {
                        let dropdownContent = dropdown.querySelector(".dropdown-content");
                        if (!dropdown.contains(event.target)) {
                            dropdownContent.style.display = "none";
                        }
                    });
                });
            });

            // Vérification que la case à cocher est cochée ou non
            // Si le case est cochée, on barre le nom de la tâche et inversement

            function handleCheckboxChange() {
                let checkbox = this;
                let heading = checkbox.parentNode.querySelector(".title_subject-agenda");
                let subject_agenda = checkbox.parentNode.querySelector(".agenda_content_subject-agenda");
                let content = checkbox.parentNode.querySelector(".agenda_description-agenda");

                if (checkbox.checked) {
                    heading.style.textDecoration = "line-through";
                    subject_agenda.style.opacity = "0.5";
                    content.style.display = "none";

                } else {
                    heading.style.textDecoration = "none";
                    subject_agenda.style.opacity = "1";
                    content.style.display = "block";
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

<?php
}
?>

</html>