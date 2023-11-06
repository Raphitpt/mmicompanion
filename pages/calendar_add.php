<?php
session_start();
include './../bootstrap.php';

$user = onConnect($dbh);

date_default_timezone_set('Europe/Paris');

$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

// Date actuelle depuis $_GET
$dateActuelle = $_GET['date'];

// Convertir en objet DateTime
$date = new DateTime($dateActuelle);

// Ajouter 8 heures
$date->modify('+8 hours');

// Formater la date de début
$dateStartAround = $date->format('Y-m-d\TH:i:s');

// Ajouter 1 heure pour obtenir la date de fin
$date->modify('+1 hour');

// Formater la date de fin
$dateEndAround = $date->format('Y-m-d\TH:i:s');

// On vérifie si le formulaire est rempli et si oui on ajoute la tache dans la base de donnée
// On appelle certaines variable du cookie pour les ajouter dans la base de donnée
// --------------------
if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date_start']) && !empty($_POST['date_end'])) {
    $title = $_POST['title'];
    $dateStart = $_POST['date_start'];
    $dateEnd = $_POST['date_end'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $color = $_POST['color'];

    $sql = "INSERT INTO calendar_event (id_user, title, start, end, location, description, color) VALUES (:id_user, :title, :start, :end, :location, :description, :color)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_user' => $user['id_user'],
        'title' => $title,
        'start' => $dateStart,
        'end' => $dateEnd,
        'location' => $location,
        'description' => $description,
        'color' => $color
    ]);
    if($stmt->rowCount() > 0){
        $_SESSION['date'] = $dateStart;
        header('Location: ./calendar.php');
        exit();
    }

}
// Fin de la vérification du formulaire


echo head("MMI Companion | Emploi du temps");
?>

<body class="body-all">
    <!-- Menu de navigation -->
    <header>
        <div class="content_header">
            <div class="content_title-header" id="burger-header">
                <div class="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Emploi du temps</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent($user_sql['role']) ?>

         

    </header>
    <!-- Fin du menu de navigation -->
    <!-- Corps de la page -->
    <main class="main-calendar_add">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Ajouter un évènement</h1>
            <div></div>
        </div>
        <div style="height:25px"></div>
        <!-- Formualaire d'ajout d'une tache, comme on peut le voir, l'envoi de ce formulaire ajoute 30 points à la personne grâce au code -->
        <form class="form-calendar_add" method="POST" action="" onsubmit="updatePoints(30)">

            <input type="text" name="title" class="input_title-calendar_add" placeholder="Ajouter un titre" required>
            <div class="trait_agenda_add"></div>

            <div class="content_inputs-calendar_add">
                <div class="content_inputs_date-calendar_add">
                    <label for="date_start" class="label-calendar_add">
                        <h2>Ajouter une date de début</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input_date-calendar_add">
                        <input type="datetime-local" name="date_start" id="date_start" class="input_date-calendar_add input-calendar_add" value="<?php echo $dateStartAround ?>" required>
                    </div>
                </div>
                <div style="height:10px"></div>
                <div class="content_inputs_date-calendar_add">
                    <label for="date_end" class="label-calendar_add">
                        <h2>Ajouter une date de fin</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input_date-calendar_add">
                        <input type="datetime-local" name="date_end" id="date_end" class="input_date-calendar_add input-calendar_add" value="<?php echo $dateEndAround ?>" required>
                    </div>
                </div>
            </div>

            <div class="trait_agenda_add"></div>

            <textarea name="description" class="input-calendar_add input_textarea-calendar_add" placeholder="Ajouter une description"></textarea>

            <div class="trait_agenda_add"></div>

            <input type="text" name="location" class="input-calendar_add input_location-calendar_add" placeholder="Ajouter un lieu">

            <div class="trait_agenda_add"></div>

            <div class="content_input_color-calendar_add">
                <label for="color" class="label-calendar_add">
                    <h2>Ajouter une couleur</h2>
                </label>
                <input type="color" name="color" value="#e66465" />

            </div>
            </div>

            <div style="height:25px"></div>
            <div class="form_button-agenda">
                <a role="button" href='./calendar.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>
            <div style="height:20px"></div>

        </form>


    </main>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_calendar-header');
        select_background_profil.classList.add('select_link-header');
        
        const dateStartInput = document.getElementById('date_start');
        const dateEndInput = document.getElementById('date_end');

        function fixLimitDate(dateStartInput, dateEndInput){
            // Obtenez la nouvelle valeur de date de début
            const dateStartValue = new Date(dateStartInput.value);

            // Ajoutez 15 minutes à la nouvelle date de début
            dateStartValue.setMinutes(dateStartValue.getMinutes() + 30);

            // Ajoutez 2 heures pour corriger le décalage
            dateStartValue.setHours(dateStartValue.getHours() + 1);

            // Mettez à jour la date de fin
            dateEndInput.min = dateStartValue.toISOString().slice(0, 16);
            dateEndInput.value = dateStartValue.toISOString().slice(0, 16);
        }

        dateStartInput.addEventListener('input', () => {
            fixLimitDate(dateStartInput, dateEndInput);
        });
        window.addEventListener('load', () => {
            fixLimitDate(dateStartInput, dateEndInput);
        });
    </script>
</body>

</html>