<?php
session_start();
include './../bootstrap.php';

$user = onConnect($dbh);

date_default_timezone_set('Europe/Paris');


if (isset($_GET['title']) && isset($_GET['description']) && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['color'])) {

    $daysMonth = getDaysMonth();
    $semaine = $daysMonth['semaine'];
    $mois = $daysMonth['mois'];

    $title = $_GET['title'];
    $description = $_GET['description'];

    // Diviser la chaîne en parties en utilisant l'espace comme séparateur
    $parties = explode(' ', $description);

    // Assurez-vous qu'il y a au moins deux parties avant d'affecter aux variables
    if (count($parties) >= 2) {
        // Affecter les parties aux variables appropriées
        $groupe = $parties[0];  // TP2
        $professeur = implode(' ', array_slice($parties, 1));  // M. Dupont
    } else {
        // En cas de professeur externe, son nom est dans le titre donc on extrait la forme M. Dupont
        $pattern = '/\b[A-Za-z]\. [A-Za-z]+\b/';
        if (preg_match($pattern, $title, $matches)) {
            $groupe = $parties[0];  // TP2
            $professeur = $matches[0];  // M. Dupont
        } else {
            $groupe = 'Non défini';  // TP2
            $professeur = 'Non défini';  // M. Dupont
        }
 
    }

    $dateStart = $_GET['start']; 
    $dateStartObj = DateTime::createFromFormat('D M d Y H:i:s e+', $dateStart);
    $dateStart = $dateStartObj ? $dateStartObj->format('Y-m-d\TH:i:s') : '';

    $dateDebutExplode = explode('T', $dateStart);
    if (count($dateDebutExplode) >= 2) {
        $heureDebutExplode = explode(':', $dateDebutExplode[1]);
        $dateStart = $heureDebutExplode[0] . 'h' . $heureDebutExplode[1];
    }

    $dateEnd = $_GET['end'];
    $dateEndObj = DateTime::createFromFormat('D M d Y H:i:s e+', $dateEnd);
    $dateEnd = $dateEndObj ? $dateEndObj->format('Y-m-d\TH:i:s') : '';

    $dateFinExplode = explode('T', $dateEnd);

    if (count($dateFinExplode) >= 2) {
        $heureFinExplode = explode(':', $dateFinExplode[1]);
        $dateEnd = $heureFinExplode[0] . 'h' . $heureFinExplode[1];
    }

    $date = $dateStartObj ? $semaine[$dateStartObj->format('w')] . $dateStartObj->format(' d') . $mois[$dateStartObj->format('n')] : '';
    

    // Calcul de la durée en heures et minutes
    $dureeDebut = $heureDebutExplode[0] * 60 + $heureDebutExplode[1];
    $dureeFin = $heureFinExplode[0] * 60 + $heureFinExplode[1];
    $duree = $dureeFin - $dureeDebut;

    // Convertir la durée en heures et minutes
    $heures = floor($duree / 60);
    $minutes = $duree % 60;
    $duree = $heures . 'h' . $minutes;

    $location = $_GET['location'];
    $color = $_GET['color'];

    $page = $_GET['page'];

    // dd($_GET);


echo head("MMI Companion | Emploi du temps");
?>

<body class="body-all">

    <?php generateBurgerMenuContent($user['role'], 'Emploi du temps', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <main class="main_all">
        <div style="height:15px"></div>

        <div class="title-calendar_view">
            <div style="background-color : <?php echo $color ?>"></div>
            <h1><?php echo $title ?></h1>
        </div>

        <div style="height:20px"></div>

        <div class="content-calendar_view">
            <div class="container-calendar_view">
                <div class="item-calendar_view">
                    <i class="fi fi-br-door-open"></i>
                    <div class="item_content-calendar_view">
                        <p>Salle de cours</p>
                        <p><?php echo $location ?></p>
                    </div>
                </div>
                <div class="item-calendar_view">
                    <i class="fi fi-br-user"></i>
                    <div class="item_content-calendar_view">
                        <p>Professeur.e</p>
                        <p><?php echo $professeur ?></p>
                    </div>
                </div>
                <div class="item-calendar_view">
                    <i class="fi fi-br-users-alt"></i>
                    <div class="item_content-calendar_view">
                        <p>Groupe</p>
                        <p><?php echo $groupe ?></p>
                    </div>
                </div>
            </div>
            <div class="container-calendar_view">
                <div class="item-calendar_view">
                    <i class="fi fi-br-calendar-lines"></i>
                    <div class="item_content-calendar_view">
                        <p>Date du cours</p>
                        <p><?php echo $date ?></p>
                    </div>
                </div>
                <div class="item-calendar_view">
                    <i class="fi fi-br-hourglass"></i>
                    <div class="item_content-calendar_view">
                        <p>Durée du cours</p>
                        <p><?php echo $duree ?></p>
                    </div>
                </div>
                <div class="item-calendar_view">
                    <i class="fi fi-br-clock"></i>
                    <div class="item_content-calendar_view">
                        <p>Début du cours</p>
                        <p><?php echo $dateStart ?></p>
                    </div>
                </div>
                <div class="item-calendar_view">
                    <i class="fi fi-br-clock-eleven-thirty"></i>
                    <div class="item_content-calendar_view">
                        <p>Fin du cours</p>
                        <p><?php echo $dateEnd ?></p>
                    </div>
                </div>
            </div>
            <a role="button" href="./<?php echo $page ?>" class="btn_back-calendar_view">
                <i class="fi fi-br-angle-left"></i>
                <p>Retour</p>
            </a>
        </div>
        
        <div style="height:30px"></div>
    </main>
    <script src="../assets/js/script_all.js?v=1.1"></script> 
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_calendar-header');
        select_background_profil.classList.add('select_link-header');

        // const dateStartInput = document.getElementById('date_start');
        // const dateEndInput = document.getElementById('date_end');

        // dateStartInput.addEventListener('input', () => {
        //     // Obtenez la nouvelle valeur de date de début
        //     const dateStartValue = new Date(dateStartInput.value);

        //     // Ajoutez 15 minutes à la nouvelle date de début
        //     dateStartValue.setMinutes(dateStartValue.getMinutes() + 15);

        //     // Ajoutez 2 heures pour corriger le décalage
        //     dateStartValue.setHours(dateStartValue.getHours() + 1);

        //     // Mettez à jour la date de fin
        //     dateEndInput.min = dateStartValue.toISOString().slice(0, 16);
        //     dateEndInput.value = dateStartValue.toISOString().slice(0, 16);
        // });

    </script>
</body>

</html>
<?php
} else {
    header('Location: ./calendar_dayview.php');
}
?>
