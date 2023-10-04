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

        <?php generateBurgerMenuContent($user_sql['role']) ?>
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

        <?php generateBurgerMenuContent($user_sql['role']) ?>
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

    <div style="height:20px"></div>
        
        <div class="select_but_agenda">
          <select name="but" id="but">
            <option value="BUT1">BUT1</option>
            <option value="BUT2">BUT2</option>
            <option value="BUT3">BUT3</option>
          </select>
          <select name="tp" id="tp">
            <option value="TP1">TP1</option>
            <option value="TP2">TP2</option>
            <option value="TP3">TP3</option>
            <option value="TP4">TP4</option>
          </select>
        </div>
            
        <div style="height:15px"></div>
        
            <div class="agenda_title_flexbottom-agenda">
                <?php
                // Affiche le responsable de l'agenda
                    echo "<p style='font-weight: bold;' id='responsable'>Responsable : " . viewChef($dbh, "BUT1-TP1") . "</p>";

                ?>
                <div style="height:15px"></div>
                <?php
                // Systeme de compteur de taches non terminées ou terminées
                // On compte le nombre d'occurences de taches non terminées
                // Cette variable est aussi utile pour savoir si la tache est checked ou pas
                // Ca incrémente la valeur si on coche ou pas en js

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
        </div>
    </main>
    <div style="height:20px"></div>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');


        const butSelect = document.getElementById('but');
        const tpSelect = document.getElementById('tp');
        const agendaMain = document.querySelector('.agenda_content-agenda');
        window.addEventListener('load', loadAgenda);
        // Fonction pour effectuer la requête XHR en utilisant POST
        function loadAgenda() {
            const selectedBut = butSelect.value;
            const selectedTp = tpSelect.value;

            let edu_group = selectedBut + '-' + selectedTp;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const viewChefValue = response.viewChef;
                    const agendaHtmlValue = response.agendaHtml;
                    if (viewChefValue != false){
                        document.getElementById('responsable').innerHTML = "Responsable : " + viewChefValue.pname +" "+ viewChefValue.name;
                    }
                    else{
                        document.getElementById('responsable').innerHTML = "Responsable : Aucun";
                    };
                    agendaMain.innerHTML = agendaHtmlValue;
                }
            };

            // Préparez les données à envoyer en tant que paramètres POST
            const data = new FormData();
            data.append('edu_group', edu_group);

            // Envoyer la requête POST vers agenda.php
            xhr.open('POST', 'agenda_index.php', true);
            xhr.send(data);
            }
        
        // Écouteurs d'événements pour les changements d'options
        butSelect.addEventListener('change', loadAgenda);
        tpSelect.addEventListener('change', loadAgenda);
            </script>

</body>

<?php 
}
?>

</html>