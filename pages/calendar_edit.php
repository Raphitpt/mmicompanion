<?php
session_start();
include './../bootstrap.php';

$user = onConnect($dbh);

date_default_timezone_set('Europe/Paris');

$user_sql = userSQL($dbh, $user);

// Date de début
$dateActuelle = date("Y-m-d H:i");
$timestamp = strtotime($dateActuelle);
// Ajouter 1 heure et arrondir vers le haut
$nouveauTimestamp = ceil(($timestamp) / 3600) * 3600;
// Convertir le timestamp en format de date et d'heure
$dateStartAround = date('Y-m-d\TH:i:s', $nouveauTimestamp);

// Date de fin
$nouveauTimestamp = ceil(($timestamp + 3600) / 3600) * 3600;
// Convertir le timestamp en format de date et d'heure
$dateEndAround = date('Y-m-d\TH:i:s', $nouveauTimestamp);

// On vérifie si le formulaire est rempli et si oui on ajoute la tache dans la base de donnée
// On appelle certaines variable du cookie pour les ajouter dans la base de donnée
// --------------------

$id_event = $_GET['id_event'];
$sql_event = "SELECT * FROM calendar_event WHERE id_event = :id_event";
$stmt_event = $dbh->prepare($sql_event);
$stmt_event->execute([
    'id_event' => $id_event
]);
$event = $stmt_event->fetch(PDO::FETCH_ASSOC);
if (!$event) {
    header('Location: ./calendar_dayview.php');
    exit();
}


if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date_start']) && !empty($_POST['date_end'])) {
    $title = $_POST['title'];
    $dateStart = $_POST['date_start'];
    $dateEnd = $_POST['date_end'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $color = $_POST['color'];

    $sql = "UPDATE calendar_event SET title=:title, start=:start, end=:end, location=:location, description=:description, color=:color WHERE id_event=:id_event";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_event' => $event['id_event'],
        'title' => $title,
        'start' => $dateStart,
        'end' => $dateEnd,
        'location' => $location,
        'description' => $description,
        'color' => $color
    ]);
    header('Location: ./calendar_dayview.php');
    exit();
}
// Fin de la vérification du formulaire


echo head("MMI Companion | Emploi du temps");
?>

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Emploi du temps', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <!-- Fin du menu de navigation -->
    <!-- Corps de la page -->
    <main class="main_all">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Éditer un évènement</h1>
            <div></div>
        </div>
        <div style="height:25px"></div>
        <!-- Formualaire d'ajout d'une tache, comme on peut le voir, l'envoi de ce formulaire ajoute 30 points à la personne grâce au code -->
        <form class="form-calendar_add" method="POST" action="" onsubmit="updatePoints(30)">

            <input type="text" name="title" class="input_title-calendar_add" placeholder="Ajouter un titre" required value="<?= $event['title'] ?>">
            <div class="trait_agenda_add"></div>

            <div class="content_inputs-calendar_add">
                <div class="content_inputs_date-calendar_add">
                    <label for="date_start" class="label-calendar_add">
                        <h2>Ajouter une date de début</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input_date-calendar_add">
                        <input type="datetime-local" name="date_start" class="input_date-calendar_add input-calendar_add" value="<?php echo str_replace(' ', 'T', $event['start'])  ?>" min="<?php echo date("Y-m-d\TH:i") ?>" required>
                    </div>
                </div>
                <div style="height:10px"></div>
                <div class="content_inputs_date-calendar_add">
                    <label for="date_end" class="label-calendar_add">
                        <h2>Ajouter une date de fin</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input_date-calendar_add">
                        <input type="datetime-local" name="date_end" class="input_date-calendar_add input-calendar_add" value="<?php echo str_replace(' ', 'T', $event['end']) ?>" min="<?php echo date("Y-m-d\TH:i") ?>" required>
                    </div>
                </div>

                <div class="trait_agenda_add"></div>

                <textarea name="description" class="input-calendar_add input_textarea-calendar_add" placeholder="Ajouter une description"><?php if (isset($event['description'])) {
                                                                                                                                                echo $event['description'];
                                                                                                                                            }; ?></textarea>

                <div class="trait_agenda_add"></div>

                <input type="text" name="location" class="input-calendar_add" placeholder="Ajouter un lieu" value="<?php if (isset($event['location'])) {
                                                                                                                        echo $event['location'];
                                                                                                                    }; ?>">

                <div class="trait_agenda_add"></div>

                <div class="content_input_color-calendar_add">
                    <label for="color" class="label-calendar_add">
                        <h2>Ajouter une couleur</h2>
                    </label>
                    <input type="color" name="color" value="<?= $event['color'] ?>" />

                </div>
            </div>

            <div style="height:25px"></div>
            <div class="form_button-agenda">
                <a role="button" href='./calendar_dayview.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>
            <div style="height:20px"></div>
            <div class="form_button-calendar_delete">
                <a role="button" href='./calendar_delete.php?id_event=<?= $event['id_event'] ?>&id_user=<?= $user['id_user'] ?>' class="profil_form-button_logout">Supprimer</a>
            </div>
            <div style="height:20px"></div>
        </form>



    </main>
    <script src="../assets/js/script_all.js?v=1.1"></script>
    <script src="../assets/js/fireworks.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_calendar-header');
        select_background_profil.classList.add('select_link-header');

        const dateStartInput = document.getElementById('date_start');
        const dateEndInput = document.getElementById('date_end');

        dateStartInput.addEventListener('input', () => {
            // Obtenez la nouvelle valeur de date de début
            const dateStartValue = new Date(dateStartInput.value);

            // Ajoutez 15 minutes à la nouvelle date de début
            dateStartValue.setMinutes(dateStartValue.getMinutes() + 15);

            // Ajoutez 2 heures pour corriger le décalage
            dateStartValue.setHours(dateStartValue.getHours() + 1);

            // Mettez à jour la date de fin
            dateEndInput.min = dateStartValue.toISOString().slice(0, 16);
            dateEndInput.value = dateStartValue.toISOString().slice(0, 16);
        });
    </script>
</body>

</html>