<?php
session_start();
require "../bootstrap.php";

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
$user = onConnect($dbh);

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = userSQL($dbh, $user);


// --------------------

// On récupère les données du formulaire du tutoriel pour ajouter l'année et le tp de l'utilisateur à la base de données
if (isset($_POST['button-validate'])) {
    $update_user = "UPDATE users SET tuto_agenda = 1 WHERE id_user = :id_user";
    $stmt = $dbh->prepare($update_user);
    $stmt->execute([
      'id_user' => $user['id_user']
    ]);
    header('Location: ./agenda_prof.php');
    exit();
  }

    // Obligatoire pour afficher la page
    $additionalStyles = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />';
    echo head("MMI Companion | Agenda", $additionalStyles);

?>
<!-- Mise en place du tutoriel -->
<?php
  if ($user_sql['tuto_agenda'] == 0) { ?>
  <body class="body-tuto_agenda">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Agenda',notifsHistory($dbh, '56', 'BUT2-TP3')) ?>

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

  <script src="../assets/js/script_all.js?v=1.1"></script> 

<script>
    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_agenda-header');
    select_background_profil.classList.add('select_link-header');
</script>

<?php 
}else{

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tp = $_POST['tp'];
        $but = $_POST['but'];
    
        $edu_group = $but . '-' . $tp;
    
        $agendaMerged = getAgendaProf($dbh, $user, $edu_group);

        $chefTP = viewChef($dbh, $edu_group);
    }
    
    
    // --------------------
    // Récupérer les couleurs des matières
    
    $sql_color = "SELECT * FROM sch_ressource INNER JOIN sch_subject ON sch_ressource.name_subject = sch_subject.id_subject";
    $stmt_color = $dbh->prepare($sql_color);
    $stmt_color->execute();
    $colors = $stmt_color->fetchAll(PDO::FETCH_ASSOC);

?>

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Agenda', notifsHistory($dbh, '56', 'BUT2-TP3')) ?>

    <!-- Corps de la page -->
    <main class="main_all">
        <div style="height:30px"></div>
        <div class="title-agenda">
            <div class="title_flextop-agenda">
                <div class="title_trait">
                    <h1>L'agenda</h1>
                    <div></div>
                </div>

                <div class="title_flextopright-agenda" id="ajouter_agenda_prof">
                    <a>Ajouter</a>
                </div>
            </div>
        </div>

        <div style="height:25px"></div>
        
        <div class="description-agenda">
            <form class="flex_form_description-agenda" action="" method="POST">
                <div class="select_description-agenda">
                    <select name="but" id="but">
                        <option value="BUT1" <?php echo isset($_POST['but']) && $_POST['but'] == 'BUT1' ? 'selected' : ''; ?>>BUT1</option>
                        <option value="BUT2" <?php echo isset($_POST['but']) && $_POST['but'] == 'BUT2' ? 'selected' : ''; ?>>BUT2</option>
                        <option value="BUT3" <?php echo isset($_POST['but']) && $_POST['but'] == 'BUT3' ? 'selected' : ''; ?>>BUT3</option>
                    </select>
                    <select name="tp" id="tp">
                        <option value="TP1" <?php echo isset($_POST['tp']) && $_POST['tp'] == 'TP1' ? 'selected' : ''; ?>>TP1</option>
                        <option value="TP2" <?php echo isset($_POST['tp']) && $_POST['tp'] == 'TP2' ? 'selected' : ''; ?>>TP2</option>
                        <option value="TP3" <?php echo isset($_POST['tp']) && $_POST['tp'] == 'TP3' ? 'selected' : ''; ?>>TP3</option>
                        <option value="TP4" <?php echo isset($_POST['tp']) && $_POST['tp'] == 'TP4' ? 'selected' : ''; ?>>TP4</option>
                    </select>
                </div>
                <div class="btn_description-agenda">
                    <input type="submit" value="Valider" class="button_agenda">
                </div>
            </form>
            <div class="description_container_content-agenda">
                <p>Responsable de l'agenda : <span style="font-weight: 600" ><?php echo $chefTP ?></span></p>
            </div>
        </div>

        <div style="height:15px"></div>

        <div class="container_content-agenda">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper agenda">
                    
                    <?php

                    // Si il n'y a pas d'évènements dans l'agenda, afficher un message
                    if (empty($agendaMerged)) { ?>

                        <div class='item_content-agenda'>
                            <p>Aucune tâche de prévu</p>
                        </div>

                    <?php } ?>

                    <?php
                    // Parcours les éléments par date et les affiche
                    foreach ($agendaMerged as $semaine => $jours) { ?>
                    <div class="swiper-slide">
                        <div class='item_content-agenda'>
                            <div class="item_title_content-agenda">
                                <i class="fi fi-br-book-bookmark"></i>
                                <p><?php echo $semaine ?></p>
                            </div>

                            <div class="container_list_content-agenda">
                                <?php 
                                if (empty($jours)) {
                                   echo "<p>Aucune tâche de prévu</p>";
                                }
                                ?>
                            <?php 
                            foreach ($jours as $jour => $agendas) { ?>
                            <div class="item_list_content_agenda">
                                <?php if (!str_contains($jour, 'Semaine')) { ?>
                                    <div class="item_title_list_content-agenda">
                                        <p><?php echo $jour ?></p>
                                        <div></div>
                                    </div>
                                <?php } ?>
                                

                                <div class="container_list-agenda">
                                <?php 
                                foreach ($agendas as $agenda) {
                                    echo "<div class='item_list-agenda'>";
                                        echo "<div class='item_list_flexleft-agenda'>";

                                            if ($agenda['type'] == "eval") {
                                                echo "<i class='fi fi-sr-square-exclamation'></i>";
                                            } elseif ($agenda['type'] == "devoir" || $agenda['type'] == "autre") {
                                                echo "<i class='fi fi-sr-square'></i>";
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

                                    echo "<div class='item_list_flexright-agenda'>";
                                        echo "<div class='menu_dropdown_item_list_flexright-agenda'>";
                                            echo "<div class='btn_menu_dropdown_item_list_flexright-agenda'>";

                                            if (($agenda['type'] == "eval" || $agenda['type'] == "devoir") && str_contains($user_sql['role'], 'prof')){
                                                echo "";
                                            } else{
                                                echo "<i class='fi fi-sr-menu-dots'></i>";
                                            }

                                            echo "</div>";
                                        
                                            echo "<div class='content_menu_dropdown_item_list_flexright-agenda menu_dropdown_close'>";

                                                // Condition pour afficher le bouton edit et delete en fonction du role de l'utilisateur
                                                if (($agenda['type'] == "eval" || $agenda['type'] == "devoir") && str_contains($user_sql['role'], 'prof')) {
                                                    echo "<i class='fi fi-br-trash red' hidden></i>";
                                                } elseif ($user_sql['role'] == "admin" || $user_sql['role'] == "chef") {
                                                    echo "<a href='agenda_edit.php?id_user=" . $agenda['id_user'] . "&id_task=" . $agenda['id_task'] . "'class='blue'><i class='fi fi-br-pencil blue'></i>Éditer</a>";
                                                    echo "<a href='agenda_del.php/?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "' id='delete-trash' class='red'><i class='fi fi-br-trash red'></i>Supprimer</a>";
                                                } else {
                                                    echo "<a href='agenda_edit.php?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "'class='blue'><i class='fi fi-br-pencil blue'></i>Éditer</a>";
                                                    echo "<a href='agenda_del.php/?id_user=" . $user['id_user'] . "&id_task=" . $agenda['id_task'] . "' id='delete-trash'class='red'><i class='fi fi-br-trash red'></i>Supprimer</a>";
                                                }

                                            echo "</div>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo "</div>"; 
                                    } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    </div>

                    <div class="btn_content-menu btn_next_agenda">
                        <p>Suivant</p>
                        <i class="fi fi-br-angle-right"></i>
                    </div>
                    <div class="btn_content-menu btn_prev_agenda">
                        <i class="fi fi-br-angle-left"></i>
                        <p>Précédent</p>
                    </div>

                </div>
            </div>
        
        <div style="height:20px"></div>
    </main>
    
    <script src="../assets/js/script_all.js?v=1.1"></script> 
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- <script src="../assets/js/fireworks.js"></script> -->

    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');


        // --------------------


        // Swiper
        let swiper = new Swiper(".mySwiper", {
            autoHeight: true,
            spaceBetween: 30,
            navigation: {
                nextEl: ".btn_next_agenda",
                prevEl: ".btn_prev_agenda",
            },
        });


        // --------------------

        const deleteTrash = document.querySelectorAll('#delete-trash');
        deleteTrash.forEach(function(trash) {
            trash.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm("Voulez-vous vraiment supprimer cette tâche ?")) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });

        // --------------------

        let checkboxes = document.querySelectorAll(".checkbox");

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
                            console.log(xhr.responseText);
                        }
                    };
                    xhr.send("idAgenda=" + encodeURIComponent(idAgenda) + "&checked=" + encodeURIComponent(checkedValue) + "&id_user=" + encodeURIComponent(<?php echo $user['id_user']; ?>) + "&load=" + encodeURIComponent(false));
                });
            });
        });

        // --------------------

        const ajouterAgenda = document.querySelector('#ajouter_agenda_prof');
    
        ajouterAgenda.addEventListener('click', function(){
            window.location.href = "./agenda_add_prof.php?but=" + butSelect.value + "&tp=" + tpSelect.value;
        });
            
        </script>

</body>

<?php 
}
?>

</html>
