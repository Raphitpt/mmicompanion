<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);
$nextCours = nextCours($user['edu_group']);

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);


// Recupération du lien de la photo de profil en base de donnée, en local ça ne fonctionnera pas, il faut quel soit en ligne, sauf si l'ajout de la photo et en local
$pp_original = "SELECT pp_link, score FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $user['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);



// -----------------------------

// AGENDA

// Récupérer les évaluations
$year_here = date('o'); // Obtenez l'année actuelle au format ISO-8601
$week_here = date('W'); // Obtenez le numéro de semaine actuel
// Formatez la date au format "YYYY-Www"
$current_week_year = $year_here . '-W' . $week_here;
$today = new DateTime();
// Requete pour récupérer les taches de l'utilisateur sans recuperer les évaluations, en les triant par date de fin et par ordre alphabétique
$edu_group_all = substr($user_sql['edu_group'], 0, 4);

// Obtenez la date actuelle
$today = new DateTime();

// Trouvez le lundi de la semaine actuelle
$startDate = clone $today;
$startDate->modify('Monday this week');

// Créer un tableau avec les dates de la semaine du lundi au vendredi
$week_dates = [];
for ($i = 0; $i < 5; $i++) {
    $week_dates[] = $startDate->format('Y-m-d');
    $startDate->modify('+1 day');
}

$sql_eval_count = "SELECT COUNT(*) as eval_count
FROM agenda a 
JOIN sch_subject s ON a.id_subject = s.id_subject 
JOIN users u ON a.id_user = u.id_user 
WHERE (a.edu_group = :edu_group OR a.edu_group = :edu_group_all) 
AND a.type = 'eval' 
AND (
    (a.date_finish LIKE :date1 OR a.date_finish LIKE :date2 OR a.date_finish LIKE :date3 OR a.date_finish LIKE :date4 OR a.date_finish LIKE :date5 OR a.date_finish = :current_week_year)
)"; 

$stmt_eval_count = $dbh->prepare($sql_eval_count);
$stmt_eval_count->execute([
    'edu_group' => $user_sql['edu_group'],
    'edu_group_all' => $edu_group_all,
    'date1' => $week_dates[0] . '%',
    'date2' => $week_dates[1] . '%',
    'date3' => $week_dates[2] . '%',
    'date4' => $week_dates[3] . '%',
    'date5' => $week_dates[4] . '%',
    'current_week_year' => $current_week_year
]);

$eval_count = $stmt_eval_count->fetchColumn();

// Récupérer les tâches à faire




// -----------------------------


// echo sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "BUT2-TP2");
// dd(notifsHistory($dbh, '56', 'BUT2-TP3'));
echo head('MMI Companion | Accueil');
?>

<body class="body-all">

    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Accueil') ?>

    <main class="main-home">
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
                    <h2>Nous sommes le </h2>
                    <div class="trait_date_content_title-home"></div>
                </div>
            </div>
        </div>

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
            <a role="button" class="item_button_nav-home" href="./absences.php">
                <i class="fi fi-br-book-alt"></i>
                <p>Vie scolaire</p>
            </a>
        </div>

        <div style="height:30px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-calendar-lines"></i>
                    <h1>Le prochain cours</h1>
                </div>
                <div></div>
            </div>

            <div class="content_prochain_cours-home">
                <div class="description_prochain_cours-home">
                    <p><?php echo $nextCours['summary'] ?></p>
                    <p><?php echo $nextCours['location'] ?> - <?php echo $nextCours['description'] ?></p>
                </div>
                <div class="date_content_prochain_cours-home">
                    <p>De <?php echo $nextCours['debut'] ?> à <?php echo $nextCours['fin'] ?></p>
                    <p id="tempsBefore">...</p>
                </div>
            </div>
            
        </section>

        <div style="height:30px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-book-bookmark"></i>
                    <h1 id="agendaTitle">...</h1>
                </div>
                <div></div>
            </div>

            <div class="container_agenda-home">
                <div class="item_agenda-home">
                    <i class="fi fi-sr-square-exclamation"></i>
                    <?php 
                    if ($eval_count == 0) {
                        echo "<p>Aucune évaluation prévue</p>";
                    } else if ($eval_count == 1) {
                        echo "<p>" . $eval_count . " évaluation</p>";
                    } else {
                        echo "<p>" . $eval_count . " évaluations</p>";
                    }
                    ?>
                </div>
                <div class="item_agenda-home">
                    <i class="fi fi-sr-checkbox"></i>
                    <p>8 tâches à faire</p>
                </div>
            </div>
            
        </section>

        <div style="height:30px"></div>

        <section class="section-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-restaurant"></i>
                    <h1>Menu du jour</h1>
                </div>
                <div></div>
            </div>

            <?php echo getMenuToday(); ?>

            
        </section>




    </main>

    <script src="../assets/js/menu-navigation.js?v=1.1"></script>
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
        document.querySelector('.date_content_title-home h2').innerHTML += "<span style='font-weight:700'>" + dateDuJour.toLocaleDateString('fr-FR', options) + "</span>";

        // -----------------------------

        // Faire apparaitre le temps restant avant le prochain cours

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
            let diffJour = diffHeure / 24;

            tempsBefore.innerHTML = "Dans ";

            if (diffHeure >= 1) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffHeure % 24) + ' h </span>';
            }

            if (diffMin <= 1) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.ceil(diffMin % 60) + ' minute </span>';
            } else if (diffMin > 1) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.ceil(diffMin % 60) + ' minutes </span>';
            }

            if (diff <= 0) {
                tempsBefore.innerHTML = "Maintenant";
            }
        }

        setInterval(function () {
            tempsRestant(tmstpCours);
        }, 1000);


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
            document.querySelector('#agendaTitle').innerText = "Mon agenda (" + formatDate(startOfWeek) + " au " + formatDate(endOfWeek) + ")";
        }

        // Appeler la fonction au chargement de la page
        updateAgendaTitle();



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
