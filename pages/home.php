<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);
$nextCours = nextCours($user['edu_group']);

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

        <section class="section_prochain_cours-home">
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

        <section class="section_agenda-home">
            <div class="title_trait-home">
                <div class="title_content_trait-home">
                    <i class="fi fi-br-book-bookmark"></i>
                    <h1 id="agendaTitle">...</h1>
                </div>
                <div></div>
            </div>

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

        const tmstpCours = '<?php echo $nextCours['dtstart_tz']; ?>';
        const tempsBefore = document.getElementById('tempsBefore');

        function tempsRestant(x) {
            y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6Z');
            let now = new Date();
            let dateCours = new Date(y);
            let diff = dateCours - now;
            let diffSec = diff / 1000;
            let diffMin = diffSec / 60;
            let diffHeure = diffMin / 60;
            let diffJour = diffHeure / 24;

            tempsBefore.innerHTML = "Dans ";

            if (diffHeure >= 1) {
                tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffHeure % 24) + ' h </span>';
            }

            tempsBefore.innerHTML += "<span style='font-weight:700'>" + Math.floor(diffMin % 60) + ' minutes </span>';
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
        
    </script>

</body>
</html>
