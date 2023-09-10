<!-- Script pour l'affichage des taches dans l'agenda en fonction de son TP -->
<?php
session_start();
require "../bootstrap.php";

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
}

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);


// Requete pour récupérer les taches de l'utilisateur sans recuperer les évaluations, en les triant par date de fin et par ordre alphabétique
// --------------------
$sql_agenda = "SELECT a.*, s.*
        FROM agenda a 
        JOIN sch_subject s ON a.id_subject = s.id_subject 
        WHERE a.id_user = :id_user AND a.type !='eval' AND a.type!='devoir' AND a.date_finish >= CURDATE()
        ORDER BY a.date_finish ASC, a.title ASC";

$stmt_agenda = $dbh->prepare($sql_agenda);
$stmt_agenda->execute([
    'id_user' => $user['id_user']
]);
$agenda_user = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
// --------------------
// Fin de la récupération des taches

// Requetes pour récupérer les évaluations de son TP
// --------------------
$sql_eval = "SELECT a.*, s.* FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND a.type = 'eval' AND a.date_finish >= CURDATE() ORDER BY a.date_finish ASC, a.title ASC";
$stmt_eval = $dbh->prepare($sql_eval);
$stmt_eval->execute([
    'edu_group' => $user_sql['edu_group']
]);
$eval = $stmt_eval->fetchAll(PDO::FETCH_ASSOC);
// --------------------
// Fin de la récupération des évaluations

// Fusionne les deux tableaux pour pouvoir les afficher dans l'ordre
$sql_devoir = "SELECT a.*, s.* FROM agenda a JOIN sch_subject s ON a.id_subject = s.id_subject WHERE a.edu_group = :edu_group AND a.type = 'devoir' AND a.date_finish >= CURDATE() ORDER BY a.date_finish ASC, a.title ASC";
$stmt_devoir = $dbh->prepare($sql_devoir);
$stmt_devoir->execute([
    'edu_group' => $user_sql['edu_group']
]);
$devoir = $stmt_devoir->fetchAll(PDO::FETCH_ASSOC);

$agenda = array_merge($agenda_user, $eval);
$agenda = array_merge($agenda, $devoir);
$eval_cont = count($eval);
$agenda_cont = count($agenda);
usort($agenda, 'compareDates');


$sql_chef = "SELECT pname, name FROM users WHERE edu_group = :edu_group AND role = 'chef'";
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
echo head("MMI Companion - Agenda");
?>
<!-- Mise en place du tutoriel -->
<?php
  if ($user_sql['tuto_agenda'] == 0) { ?>
  <body class="body-tuto_agenda">
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

    <main class="main_tuto-agenda">
      <form action="" method="post" class="form-tuto_agenda">
        <div class="title_tuto-agenda">
            <img src="./../assets/img/agenda_emoji.png" alt="Emoji d'un livre">
            <h1>Comment fonctionne l’agenda ?</h1>
        </div>
        <p class="p_trait-agenda">Dans chaque TP, un.e étudiant.e est chargé.e d’ajouter les devoirs à l’agenda et de le maintenir à jour pour les autres étudiant.e.s</p>
        <p>On vous invite à discuter entre vous pour déterminer l’étudiant.e qui sera chargée de mettre à jour l’agenda. 
        <br>Par la suite, l’étudiant.e volontaire doit nous contacter pour qu’on lui attribue son rôle.</p>
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

<script src="../assets/js/menu-navigation.js"></script>
<script>
    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_agenda-header');
    select_background_profil.classList.add('select_link-header');
</script>
<?php 
}else{

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
                echo "<p style='font-weight: bold;'>Groupe : " . $user_sql['edu_group'] . "</p>";
                if (!empty($chef)){
                    echo "<p style='font-weight: bold;'>Responsable : " . $chef['pname'] . " " . $chef['name'] . "</p>";
                }
                else{
                    echo "<p style='font-weight: bold;'>Responsable : Aucun</p>";
                }
                ?>
                <div style="height:15px"></div>
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

                    echo "<div class='agenda_title_content_list_item_flexleft-agenda'>";
                    if ($agenda['type'] == "eval") {
                        echo "<h3 class='title_subject-agenda'>[Évaluation] " . $agenda['title'] . "</h3>";
                    }
                    if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                        echo "<h3 class='title_subject-agenda'>" . $agenda['title'] . "</h3>";
                    }
                    echo "<div class='agenda_content_subject-agenda'>";
                    foreach ($colors as $color) {
                        if ($color['id_subject'] == $agenda['id_subject']) {
                            echo "<p style='background-color:". $color['color_ressource'] . "'>" . $agenda['name_subject'] . "</p>";
                            // echo "<div class='circle_subject-agenda' style='background-color:" . $color['color_ressource'] . "'></div>";
                            break;
                        }
                    };
                    // echo "<div class='circle_subject-agenda' style='background-color:#" . $agenda['color'] . "'></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='agenda_content_list_item_flexright-agenda'>";
                    // Ne pas afficher la corbeille si l'utilisateur est un étudiant et que c'est une évaluation
                    if(($agenda['type'] == "eval" || $agenda['type'] == "devoir") && $user_sql['role'] == "eleve"){
                        echo "<i class='fi fi-br-trash red' hidden></i>";
                    } 
                    elseif ($user_sql['role'] == "admin" || $user_sql['role'] == "chef") {
                        echo "<a href='agenda_edit.php?id_user=".$agenda['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-pencil blue'></i></a><a href='agenda_del.php/?id_user=".$user['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-trash red'></i></a>";
                    }
                    else {
                        echo "<a href='agenda_edit.php?id_user=".$user['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-pencil blue'></i></a><a href='agenda_del.php/?id_user=".$user['id_user']."&id_task=".$agenda['id_task']."'><i class='fi fi-br-trash red'></i></a>";
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

<?php 
}
?>

</html>