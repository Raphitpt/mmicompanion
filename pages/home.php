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
            <a role="button" class="item_button_nav-home" href="./calendar.php">
                <i class="fi fi-br-book-bookmark"></i>
                <p>Agenda</p>
            </a>
            <a role="button" class="item_button_nav-home" href="./calendar.php">
                <i class="fi fi-br-info"></i>
                <p>Informations</p>
            </a>
            <a role="button" class="item_button_nav-home" href="./calendar.php">
                <i class="fi fi-br-book-alt"></i>
                <p>Vie scolaire</p>
            </a>
        </div>





        <!-- <div>
            <h1>Accueil</h1>
            <p>Bienvenue sur l'application de gestion des ressources de l'IUT de Lens.</p>
            <p>Vous pouvez consulter les ressources disponibles dans le menu de gauche.</p>
            <p>Vous pouvez également consulter les prochains cours dans le tableau ci-dessous.</p>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Enseignant</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Salle</th>
                        <th>Temps restant</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $nextCours['summary'] ?></td>
                        <td><?= $nextCours['description'] ?></td>
                        <td id="tmstpCours"><?= $nextCours['dtstart_tz'] ?></td>
                        <td><?= $nextCours['debut'] ?> - <?= $nextCours['fin'] ?></td>
                        <td><?= $nextCours['location'] ?></td>
                        <td id="tempsBefore">0</td>
                    </tr>
                </tbody>
            </table>
            <form>
                <button type="submit">send notification</button>
            </form>
            <?php echo getMenu(); ?> 
        </div> -->
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

        const tmstpCours = document.getElementById('tmstpCours').innerHTML;
        const tempsBefore = document.getElementById('tempsBefore');
        function tempsRestant(x){
            y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6Z');
            let now = new Date();
            let dateCours = new Date(y);
            let diff = dateCours - now;
            let diffSec = diff / 1000;
            let diffMin = diffSec / 60;
            let diffHeure = diffMin / 60;
            let diffJour = diffHeure / 24;
            tempsBefore.innerHTML = "";
            tempsBefore.innerHTML = Math.floor(diffJour) + ' jours ' + Math.floor(diffHeure % 24) + ' heures ' + Math.floor(diffMin % 60) + ' minutes ' + Math.floor(diffSec % 60) + ' secondes';
        }
        setInterval(function () {
            tempsRestant(tmstpCours);
        }, 1000);
    </script>

</body>
</html>
