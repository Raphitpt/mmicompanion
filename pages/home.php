<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);
$nextCours = nextCours($user['edu_group']);

setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = userSQL($dbh, $user);


// Recupération du lien de la photo de profil en base de donnée, en local ça ne fonctionnera pas, il faut quel soit en ligne, sauf si l'ajout de la photo et en local
$pp_original = "SELECT pp_link, score FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $user['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);



// -----------------------------

$agendaMerged = getAgenda($dbh, $user, $user_sql['edu_group'], $user_sql);
// dd($agendaMerged);

// --------------------
// Récupérer les couleurs des matières

$sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
$stmt_color = $dbh->prepare($sql_color);
$stmt_color->execute();
$colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);



// Récupérer les tâches à faire pour demain
$tasksForTomorrow = [];

foreach ($agendaMerged as $semaine => $jours) {
    foreach ($jours as $jour => $taches) {
        if ($jour == 'Demain') {
            foreach ($taches as $tache) {
                $tasksForTomorrow[] = $tache;

            }
        }
    }
}

// Maintenant, $tasksForTomorrow est un tableau contenant les titres des tâches pour demain




$sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
$stmt_color = $dbh->prepare($sql_color);
$stmt_color->execute();
$colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);


// -----------------------------


// echo sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "BUT2-TP2");
// dd(notifsHistory($dbh, '56', 'BUT2-TP3'));
echo head('MMI Companion | Accueil');
?>

<body class="body-all">

    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Accueil', notifsHistory($dbh, '56', 'BUT2-TP3')); ?>

    <main class="main_all">
        <div style="height:30px"></div>
        <div class="title-home">
            <div class="illustration_title-home">
                <img id="preview" class="profil_picture-img" src="<?php echo $pp_original['pp_link'] ?>" alt="Photo de profil">
            </div>
            <div class="content_title-home">
                <div class="identite_content_title-home">
                    <h1>Bonjour <span style="font-weight:800"><?php echo ucfirst($user['pname']) ?></span></h1>
                    <img src="./../assets/img/hello_emoji.webp" alt="">
                </div>
                <div class="date_content_title-home">
                    <h2>Chargement...</h2>
                    <div class="trait_date_content_title-home"></div>
                </div>
            </div>
        </div>

        <div style="height:20px"></div> 

        <section class="section-home">
            <!-- <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-calendar-lines"></i>
                    <h1>Le prochain cours</h1>
                </div>
                <div></div>
            </div> -->

            <a href="./calendar_view.php?title=<?php echo $nextCours['summary'] ?>&location=<?php echo $nextCours['location'] ?>&description=<?php echo $nextCours['description'] ?>&color=#fff&start=<?php echo $nextCours['debut'] ?>&end=<?php echo $nextCours['fin'] ?>&page=home.php">
                <div class="content_prochain_cours-home">
                    <div class="description_prochain_cours-home">
                        <p><?php //echo $nextCours['summary'] ?></p>
                        <p><?php //echo $nextCours['location'] ?> - <?php //echo $nextCours['description'] ?></p>
                    </div>
                    <div class="date_content_prochain_cours-home">
                        <p>De <?php //echo $nextCours['debut'] ?> à <?php //echo $nextCours['fin'] ?></p>
                        <p id="tempsBefore">Chargement...</p>
                    </div>
                </div>
            </a>
        </section>

        <div style="height:20px"></div> 

        <div class="container_buttons_nav-home">
            <a role="button" class="item_button_nav-home" href="./calendar.php">
                <i class="fi fi-br-calendar-lines"></i>
                <p>Emploi du temps</p>
            </a>
            <a role="button" class="item_button_nav-home" href="./agenda.php">
                <i class="fi fi-br-book-bookmark"></i>
                <p>Agenda</p>
            </a>
            <a role="button" class="item_button_nav-home" href="./informations.php">
                <i class="fi fi-br-info"></i>
                <p>Informations</p>
            </a>
            <a role="button" class="item_button_nav-home" href="./scolarite.php">
                <i class="fi fi-br-book-alt"></i>
                <p>Scolarité</p>
            </a>
        </div>

        <div style="height:30px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-book-bookmark"></i>
                    <h1 id="agendaTitle">Cette semaine</h1>
                </div>
                <div></div>
            </div>

            <div class="content_agenda-home">
                <div class="proprietaire_cahier_agenda-home">
                    <p><span style="font-weight:700">Propriétaire du cahier</span> : ...</p>
                </div>

                <div class="container_numbers_agenda-home">
                    <a href="./agenda.php">
                        <div class="item_number_agenda-home">
                            <i class="fi fi-sr-square-exclamation"></i>
                            <!-- <?php 
                            if ($eval_count == 0) {
                                echo "<p>Pas d'évaluation</p>";
                            } else if ($eval_count == 1) {
                                echo "<p>" . $eval_count . " évaluation</p>";
                            } else {
                                echo "<p>" . $eval_count . " évaluations</p>";
                            }
                            ?> -->
                        </div>
                    </a>
                    <a href="./agenda.php">
                        <div class="item_number_agenda-home">
                            <i class="fi fi-sr-checkbox"></i>
                            <!-- <?php
                            if ($taches_count == 0) {
                                echo "<p>Pas de tâche</p>";
                            } else if ($taches_count == 1) {
                                echo "<p>" . $taches_count . " tâche à faire</p>";
                            } else {
                                echo "<p>" . $taches_count . " tâches à faire</p>";
                            } 
                            ?> -->
                        </div>
                    </a>
                </div>

                <div class="agenda_tomorrow-home">
                    <p>Demain</p>
                    <div class="container_list-agenda">
                        <?php if (empty($tasksForTomorrow)) {
                            echo "<div class='item_list-agenda'>";
                                echo "<p>Pas de tâche à faire pour demain</p>";
                            echo "</div>";
                        } ?>
                        <?php foreach ($tasksForTomorrow as $agenda) {
                            echo "<div class='item_list-agenda'>";
                                echo "<div class='item_list_flexleft-agenda'>";

                                    if ($agenda['type'] == "eval") {
                                        echo "<i class='fi fi-sr-square-exclamation'></i>";
                                    }
                                    // Affichage de la coche ou de l'indication rouge si c'est une évaluation
                                    if ($agenda['type'] == "devoir" or $agenda['type'] == "autre") {
                                        if (getEventCheckedStatus($dbh, $agenda['id_task'], $user['id_user']) == 1) {
                                            echo "<input type='checkbox' name='checkbox' class='checkbox' id='checkbox-" . $agenda['id_task'] . "' data-idAgenda='" . $agenda['id_task'] . "'' checked>";
                                        } else {
                                            echo "<input type='checkbox' name='checkbox' class='checkbox' id='checkbox-" . $agenda['id_task'] . "' onclick='updatePoints(10)' data-idAgenda='" . $agenda['id_task'] . "''>";
                                        }
                                    }

                                    echo "<label for='checkbox-" . $agenda['id_task'] . "' class='content_item_list_flexleft-agenda'>";
                                    // Affichage de la matière de l'event de l'agenda et la couleur associée ainsi que évaluation devant
                                    foreach ($colors as $color) {

                                        if ($color['id_subject'] == $agenda['id_subject']) {
                                            echo "<div class='subject_item_list_flexleft-agenda'>";
                                                echo "<div style='background-color:" . $color['color_ressource'] . "'></div>";
                                                if ($agenda['type'] == "eval") {
                                                    echo "<p><span style='font-weight:600'>[Évaluation]</span> " . $agenda['name_subject'] . "</p>";
                                                } else {
                                                    echo "<p>" . $agenda['name_subject']. "</p>";
                                                }
                                            echo "</div>";
                                            break;
                                        }
                                        
                                    };
                                    // Affichage du titre de l'event de l'agenda
                                    echo "<div class='title_item_list_flexleft-agenda'>";
                                        echo "<p>" . $agenda['title'] . "</p>";
                                    echo "</div>";

                                    // Affichage du contenu de l'event de l'agenda
                                    echo "<div class='description_item_list_flexleft-agenda'>";
                                    if (isset($agenda['content']) && !empty($agenda['content'])) {
                                        echo $agenda['content'];
                                    }
                                    echo "</div>";

                                    // Affichage du nom du professeur qui a ajouté l'event de l'agenda, si il y a
                                    echo "<div class='author_item_list_flexleft-agenda'>";
                                    if (isset($agenda['role']) && $agenda['role'] == "prof") {
                                        echo "<p class='name_subject-agenda'>De : <span style='font-weight:600'>" . substr($agenda['pname'], 0, 1) . '. ' . $agenda['name'] . "</span></p></br>";
                                    }
                                    echo "</div>";

                                    echo "</label>";
                                echo "</div>";
                            echo "</div>"; 
                            } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </section>

        <div style="height:30px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-restaurant"></i>
                    <h1>Menu du jour (en bêta)</h1>
                </div>
                <div></div>
            </div>

            <div class='content_menu-home'>
                <?php echo getMenuToday(); ?>
            </div>

        </section>

        <div style="height:30px"></div>

    </main>

    <script src="../assets/js/script_all.js?v=1.1"></script>
    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_home-header');
        select_background_profil.classList.add('select_link-header');

        // -----------------------------

        // Faire apparaitre la date du jour

        // Obtenir la date du jour
        let dateDuJour = new Date();

        // Options pour formatter la date en français (changez cela selon vos besoins)
        let options = { weekday: 'long', month: 'long', day: 'numeric' };

        // Mettre à jour le contenu de l'élément <h2> avec la date du jour
        document.querySelector('.date_content_title-home h2').innerHTML = "Nous sommes le <span style='font-weight:700'>" + dateDuJour.toLocaleDateString('fr-FR', options) + "</span>";

        // -----------------------------

        // // Faire apparaitre le temps restant avant le prochain cours

        // const tmstpCours = '<?php //echo $nextCours['dtstart']; ?>';
        // const tempsBefore = document.getElementById('tempsBefore');

        // function tempsRestant(x) {
        //     // Ajouter "Z" à la fin de la date pour indiquer que c'est en temps universel coordonné (UTC)
        //     y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6');
        //     let now = new Date();
            
        //     // Convertir la date du cours en objet Date et spécifier le fuseau horaire (par exemple, "Europe/Paris")
        //     let dateCours = new Date(y);
            
        //     // Calculer la différence entre les deux dates
        //     let diff = dateCours - now;
        //     let diffSec = diff / 1000;
        //     let diffMin = diffSec / 60;
        //     let diffHeure = diffMin / 60;
        //     let diffJour = diffHeure / 24;

        //     tempsBefore.innerHTML = "Dans ";

        //     if (diffHeure >= 1) {
        //         tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffHeure % 24) + ' h </span>';
        //     }

        //     if (diffMin <= 1) {
        //         tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.ceil(diffMin % 60) + ' minute </span>';
        //     } else if (diffMin > 1) {
        //         tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.ceil(diffMin % 60) + ' minutes </span>';
        //     }

        //     if (diff <= 0) {
        //         tempsBefore.innerHTML = "Maintenant";
        //     }
        // }

        // setInterval(function () {
        //     tempsRestant(tmstpCours);
        // }, 1000);


        // -----------------------------

        // Fonction pour formater la date au format 'jj/mm'
        function formatDate(date) {
            let day = date.getDate();
            let month = date.getMonth() + 1; // Les mois commencent à 0, donc ajouter 1

            // Ajouter des zéros initiaux si nécessaire
            day = (day < 10) ? '0' + day : day;
            month = (month < 10) ? '0' + month : month;

            return day + '/' + month;
        }

        // Mettre à jour le titre avec la semaine scolaire actuelle
        function updateAgendaTitle() {
            let today = new Date();
            let currentDay = today.getDay(); // 0 pour dimanche, 1 pour lundi, ..., 6 pour samedi

            // Calculer le début et la fin de la semaine scolaire
            let startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - (currentDay - 1));
            
            let endOfWeek = new Date(today);
            endOfWeek.setDate(today.getDate() + (5 - currentDay));

            // Mettre à jour le titre
            document.querySelector('#agendaTitle').innerText = "Cette semaine (" + formatDate(startOfWeek) + " au " + formatDate(endOfWeek) + ")";
        }

        // Appeler la fonction au chargement de la page
        updateAgendaTitle();


        // -----------------------------

        let checkboxes = document.querySelectorAll(".checkbox");

        checkboxes.forEach(function(checkbox) {
            // Ici on fait une requête au fichier coche_agenda.php pour mettre à jour la base de données lors d'une coche ou décoche
            checkbox.addEventListener("change", function() {

                let idAgenda = this.getAttribute("data-idAgenda");
                let checkedValue = this.checked ? 1 : 0;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./coche_agenda.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let data = JSON.parse(xhr.responseText); // Use xhr.responseText
                        let tachesCount = data.taches_count;
                        console.log(data.message);
                        if (tachesCount == 0) {
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = "Pas de tâche";
                        } else if (tachesCount == 1) {
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = tachesCount + " tâche à faire";
                        } else{
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = tachesCount + " tâches à faire";
                        }
                    }
                };
                xhr.send("idAgenda=" + encodeURIComponent(idAgenda) + "&checked=" + encodeURIComponent(checkedValue) + "&id_user=" + encodeURIComponent(<?php echo $user['id_user']; ?>));
            });
        });

        window.addEventListener("DOMContentLoaded", function() {
            let checkboxes = document.querySelectorAll(".checkbox");
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", handleCheckboxChange);

                // Vérification initiale de l'état de la case à cocher
                handleCheckboxChange.call(checkbox); // Appel de la fonction avec la case à cocher comme contexte
            });
        });



        // // Obtenez la date actuelle
        // var today = new Date();

        // // Parcourez les éléments avec la classe 'meal'
        // var mealDivs = document.querySelectorAll('.meal');
        // console.log(mealDivs);

        // mealDivs.forEach(function(mealDiv) {
        //     // Récupérez le texte à l'intérieur de l'élément <h2> pour obtenir la date du menu
        //     var dateString = mealDiv.querySelector('h2').innerText;

            
            
        //     // Comparez directement la date du texte avec la date actuelle (ignorant l'heure)
        //     if (dateString.includes(today.toLocaleDateString('fr-FR'))) {
        //         // La date du menu correspond à la date actuelle, faites quelque chose avec cet élément
        //         mealDiv.classList.add('active'); // Ajoutez une classe 'active', par exemple
        //     }
        // });

        
    </script>

</body>
</html>
