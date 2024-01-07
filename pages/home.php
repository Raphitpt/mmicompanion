<?php

session_start();
require '../bootstrap.php';


function fetchUser($dbh, $userId)
{
    $ppOriginalQuery = "SELECT pp_link, score FROM users WHERE id_user = :id_user";
    $ppOriginalStatement = $dbh->prepare($ppOriginalQuery);
    $ppOriginalStatement->execute(['id_user' => $userId]);
    return $ppOriginalStatement->fetch(PDO::FETCH_ASSOC);
}

function fetchColors($dbh)
{
    $sqlColor = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
    $stmtColor = $dbh->prepare($sqlColor);
    $stmtColor->execute();
    return $stmtColor->fetchAll(PDO::FETCH_ASSOC);
}

function fetchAgenda($dbh, $user, $eduGroup)
{
    return getAgenda($dbh, $user, $eduGroup);
}

$user = onConnect($dbh);
$nextCours = nextCours($user['edu_group']);
// dd($nextCours);
setlocale(LC_TIME, 'fr_FR.UTF-8');

$userSqlFiber = new Fiber(function () use ($dbh, $user) {
    return userSQL($dbh, $user);
});

$ppOriginalFiber = new Fiber(function () use ($dbh, $user) {
    return fetchUser($dbh, $user['id_user']);
});

$agendaMergedFiber = new Fiber(function () use ($dbh, $user) {
    return fetchAgenda($dbh, $user, userSQL($dbh, $user)['edu_group']);
});

// Start fibers and get results
$user_sql = $userSqlFiber->start();
$user_sql = $userSqlFiber->getReturn();
$pp_original = $ppOriginalFiber->start();
$pp_original = $ppOriginalFiber->getReturn();
if (srt_contains($user_sql['role'], "eleve" ) || str_contains($user_sql['role']),"admin") ) {
    $agendaMerged = $agendaMergedFiber->start();
    $agendaMerged = $agendaMergedFiber->getReturn();
}




// -----------------------------


$userCahier = getUserCahier($dbh, $user_sql['edu_group']);
// dd($userCahier);
if ($userCahier != 'null') {
    $nomUserCahier = ucwords(strtolower($userCahier['prenom'])) . ' ' . ucwords(strtolower($userCahier['nom']));
} else {
    $nomUserCahier = 'Personne';
}


// -----------------------------

$agendaMerged = getAgenda($dbh, $user, $user_sql['edu_group']);
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

$colorsFiber = new Fiber(function () use ($dbh) {
    return fetchColors($dbh);
});

$colors = $colorsFiber->start();

$additionalStyles = (str_contains($user_sql['role'], 'prof'))
    ? '<link async rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />'
    : '';

echo head('MMI Companion | Accueil', $additionalStyles);
?>

<body class="body-all">

    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Accueil', notifsHistory($dbh, $user_sql['id_user'], $user_sql['edu_group'])); ?>

    <main class="main_all">
        <div id="push-permission" class="popup_notification">
            <div class="content_popup_notification">
                <p>Activez les notifications</p>
                <button id="enable-notifications">Accepter</button>
            </div>
        </div>
        <div style="height:30px"></div>
        <div class="title-home">
            <div class="illustration_title-home">
                <img id="preview" class="profil_picture-img" src="<?php echo $pp_original['pp_link'] ?>" alt="Photo de profil">
            </div>
            <div class="content_title-home">
                <?php if (!str_contains($user_sql['role'], 'prof')) { ?>
                    <div class="identite_content_title-home">
                        <h1>Bonjour <span style="font-weight:800"><?php echo ucfirst($user['pname']) ?></span></h1>
                        <img src="./../assets/img/hello_emoji.webp" alt="">
                    </div>
                <?php } else { ?>
                    <div class="identite_content_title-home">
                        <h1>Bonjour <span style="font-weight:800"><?php echo substr($user['pname'], 0, 1) . '. ' . $user['name']; ?></span></h1>
                        <img src="./../assets/img/hello_emoji.webp" alt="">
                    </div>
                <?php } ?>
                <div class="date_content_title-home">
                    <h2>Chargement...</h2>
                    <div class="trait_date_content_title-home"></div>
                </div>
            </div>
        </div>

        <div style="height:20px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-calendar-lines"></i>
                    <h1>Le prochain cours</h1>
                </div>
                <div></div>
            </div>

            <a href="./calendar_view.php?title=<?php echo $nextCours['summary'] ?>&location=<?php echo $nextCours['location'] ?>&description=<?php echo $nextCours['description'] ?>&color=#fff&start=<?php echo $nextCours['debut'] ?>&end=<?php echo $nextCours['fin'] ?>&page=home.php">
                <div class="content_prochain_cours-home">
                    <div class="description_prochain_cours-home">
                        <p><?php echo $nextCours['summary'] ?></p>
                        <p><?php echo $nextCours['location'] ?> - <?php echo $nextCours['description'] ?></p>
                    </div>
                    <div class="date_content_prochain_cours-home">
                        <p>De <?php echo $nextCours['debut'] ?> à <?php echo $nextCours['fin'] ?></p>
                        <p id="tempsBefore">Chargement...</p>
                    </div>
                </div>
            </a>
        </section>

        <div style="height:20px"></div>

        <div class="container_buttons_nav-home">
            <a role="button" class="item_button_nav-home" href="./calendar_dayview.php">
                <i class="fi fi-br-calendar-lines"></i>
                <p>Emploi du temps</p>
            </a>
            <?php if (!str_contains($user_sql['role'], 'prof')) { ?>
                <a role="button" class="item_button_nav-home" href="./agenda.php">
                    <i class="fi fi-br-book-bookmark"></i>
                    <p>Agenda</p>
                </a>
            <?php } else { ?>
                <a role="button" class="item_button_nav-home" href="./agenda_prof.php">
                    <i class="fi fi-br-book-bookmark"></i>
                    <p>Agenda</p>
                </a>
            <?php } ?>
            <a role="button" class="item_button_nav-home" href="./informations.php">
                <i class="fi fi-br-info"></i>
                <p>Informations</p>
            </a>
            <?php if (!str_contains($user_sql['role'], 'prof')) { ?>
                <a role="button" class="item_button_nav-home" href="./scolarite.php">
                    <i class="fi fi-br-book-alt"></i>
                    <p>Scolarité</p>
                </a>
            <?php } else { ?>
                <a role="button" class="item_button_nav-home" href="./liens_externes.php">
                    <i class="fi fi-br-link-alt"></i>
                    <p>Liens externes</p>
                </a>
            <?php } ?>
        </div>

        <div style="height:30px"></div>

        <?php if (!str_contains($user_sql['role'], 'prof')) { ?>
            <section class="section-home">
                <div class="title_trait-home">
                    <div class="title_content_trait-home">
                        <i class="fi fi-br-book-bookmark"></i>
                        <h1 id="agendaTitle">Cette semaine</h1>
                    </div>
                    <div></div>
                </div>

                <div class="content_agenda-home">

                    <?php if (explode("-", $user_sql['edu_group'])[0] != "BUT3") { ?>
                        <div class="proprietaire_cahier_agenda-home">
                            <p><span style="font-weight:700">Propriétaire du cahier : </span><?php echo $nomUserCahier ?></p>
                        </div>
                    <?php  } ?>

                    <div class="container_numbers_agenda-home">
                        <a href="./agenda.php">
                            <div class="item_number_agenda-home">
                                <i class="fi fi-sr-square-exclamation"></i>
                                <p>...</p>
                            </div>
                        </a>
                        <a href="./agenda.php">
                            <div class="item_number_agenda-home">
                                <i class="fi fi-sr-checkbox"></i>
                                <p>...</p>
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
                                            echo "<p>" . $agenda['name_subject'] . "</p>";
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

        <?php } else { ?>

            <section class="section-home">

                <div class="container_slider_pages-home">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper home">
                            <a role="button" href="./agenda_prof.php" class="swiper-slide">
                                <div class="item_slider_page-home">
                                    <div class="item_illustration_slider_page-home">
                                        <img src="./../assets/img/illustration_agenda.svg" alt="">
                                    </div>
                                    <div class="item_content_slider_page-home">
                                        <div class="item_title_content_slider_page-home">
                                            <div>
                                                <i class="fi fi-br-book-bookmark"></i>
                                                <p>Agenda</p>
                                            </div>
                                            <p>Une nouvelle évaluation ?</p>
                                            <p>Vérifiez sa présence dans l'agenda.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a role="button" href="./informations_add.php" class="swiper-slide">
                                <div class="item_slider_page-home">
                                    <div class="item_illustration_slider_page-home">
                                        <img src="./../assets/img/illustration_info.svg" alt="">
                                    </div>
                                    <div class="item_content_slider_page-home">
                                        <div class="item_title_content_slider_page-home">
                                            <div>
                                                <i class="fi fi-br-info"></i>
                                                <p>Informations</p>
                                            </div>
                                            <p>Une absence de prévue ?</p>
                                            <p>Prévenez vite les étudiant.e.s.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a role="button" href="./calendar_dayview.php" class="swiper-slide">
                                <div class="item_slider_page-home">
                                    <div class="item_illustration_slider_page-home">
                                        <img src="./../assets/img/illustration_calendar.svg" alt="">
                                    </div>
                                    <div class="item_content_slider_page-home">
                                        <div class="item_title_content_slider_page-home">
                                            <div>
                                                <i class="fi fi-br-calendar-lines"></i>
                                                <p>Emploi du temps</p>
                                            </div>
                                            <p>Quand est votre prochain cours ?</p>
                                            <p>Consultez votre emploi du temps.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>


                </div>

            </section>

            <div style="height:30px"></div>

        <?php } ?>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-restaurant"></i>
                    <h1>Menu du jour (en bêta)</h1>
                </div>
                <div></div>
            </div>

            <a href="./menu.php">
                <div class='content_menu-home'>
                <?php if (empty(getMenuToday())==true) { ?>
                    <div class="swiper-slide item_menu_content-menu">
                        <p style='font-weight:600'>Le menu est indisponible</p>
                    </div>
                    <?php } else{
                        echo getMenuToday();
                    } ?>
                </div>
            </a>

        </section>

        <div style="height:30px"></div>

    </main>

    <script src="../assets/js/script_all.js?v=1.1"></script>
            
    <?php
    if (str_contains($user_sql['role'], 'prof')) { ?>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <?php } ?>

    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_home-header');
        select_background_profil.classList.add('select_link-header');

        // -----------------------------

        <?php
        if (str_contains($user_sql['role'], 'prof')) { ?>
            let swiper = new Swiper(".mySwiper", {
                autoHeight: true,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
            });
        <?php } ?>


        // Faire apparaitre la date du jour

        // Obtenir la date du jour
        let dateDuJour = new Date();

        // Options pour formatter la date en français (changez cela selon vos besoins)
        let options = {
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        };

        // Mettre à jour le contenu de l'élément <h2> avec la date du jour
        document.querySelector('.date_content_title-home h2').innerHTML = "Nous sommes le <span style='font-weight:700'>" + dateDuJour.toLocaleDateString('fr-FR', options) + "</span>";

        // -----------------------------

        // // Faire apparaitre le temps restant avant le prochain cours

        const tmstpCours = '<?php echo $nextCours['dtstart']; ?>';
        const tempsBefore = document.getElementById('tempsBefore');

        function tempsRestant(x) {
            // Ajouter "Z" à la fin de la date pour indiquer que c'est en temps universel coordonné (UTC)
            y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6');
            let now = new Date();

            // Convertir la date du cours en objet Date et spécifier le fuseau horaire (par exemple, "Europe/Paris")
            let dateCours = new Date(y);

            // Calculer la différence entre les deux dates
            let diff = dateCours - now;
            let diffSec = diff / 1000;
            let diffMin = diffSec / 60;
            let diffHeure = diffMin / 60;

            tempsBefore.innerHTML = "Dans ";

            if (diffHeure >= 48) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffHeure / 24) + ' jours</span>';
            } else if (diffHeure >= 1) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffHeure) + 'h ' + Math.floor(diffMin % 60) + 'min</span>';
                if (diffHeure < 1) {
                    tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.ceil(diffMin) + ' minutes</span>';
                }
            } else if (dateCours <= now) {
                tempsBefore.innerHTML = "Maintenant";
            }
        }

        setInterval(function() {
            tempsRestant(tmstpCours);
        }, 1000);



        <?php
        if (!str_contains($user_sql['role'], 'prof')) { ?>


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

                // Si on est après le vendredi, passer à la semaine suivante
                if (currentDay > 4) {
                    startOfWeek.setDate(startOfWeek.getDate() + 7); // Ajouter 7 jours pour passer à la semaine suivante
                    endOfWeek.setDate(endOfWeek.getDate() + 7);
                }

                // Mettre à jour le titre
                document.querySelector('#agendaTitle').innerText = "Cette semaine (" + formatDate(startOfWeek) + " au " + formatDate(endOfWeek) + ")";
            }

            // Appeler la fonction pour mettre à jour le titre
            updateAgendaTitle();


            // -----------------------------

            let checkboxes = document.querySelectorAll(".checkbox");

            document.addEventListener("DOMContentLoaded", function() {
                let idAgenda = null;
                let checkedValue = null;

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./coche_agenda.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let data = JSON.parse(xhr.responseText);
                        let nbEval = data.nbEval;
                        let nbDevoir = data.nbDevoir;
                        if (nbDevoir == 0) {
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = "Pas de tâche";
                        } else if (nbDevoir == 1) {
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = nbDevoir + " tâche à faire";
                        } else {
                            document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = nbDevoir + " tâches à faire";
                        }

                        if (nbEval == 0) {
                            document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = "Pas d'évaluation";
                        } else if (nbEval == 1) {
                            document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = nbEval + " évaluation";
                        } else {
                            document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = nbEval + " évaluations";
                        }
                    }
                };
                xhr.send("load=" + encodeURIComponent(true));
            });


            document.addEventListener("DOMContentLoaded", function() {
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
                                let data = JSON.parse(xhr.responseText);
                                let nbEval = data.nbEval;
                                let nbDevoir = data.nbDevoir;
                                console.log(data);
                                if (nbDevoir == 0) {
                                    document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = "Pas de tâche";
                                } else if (nbDevoir == 1) {
                                    document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = nbDevoir + " tâche à faire";
                                } else {
                                    document.querySelector('.container_numbers_agenda-home a:last-child .item_number_agenda-home p').innerText = nbDevoir + " tâches à faire";
                                }

                                if (nbEval == 0) {
                                    document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = "Pas d'évaluation";
                                } else if (nbEval == 1) {
                                    document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = nbEval + " évaluation";
                                } else {
                                    document.querySelector('.container_numbers_agenda-home a:first-child .item_number_agenda-home p').innerText = nbEval + " évaluations";
                                }
                            }
                        };
                        xhr.send("idAgenda=" + encodeURIComponent(idAgenda) + "&checked=" + encodeURIComponent(checkedValue) + "&id_user=" + encodeURIComponent(<?php echo $user['id_user']; ?>) + "&load=" + encodeURIComponent(false));
                    });
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

        <?php } ?>

        // -----------------------------


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
