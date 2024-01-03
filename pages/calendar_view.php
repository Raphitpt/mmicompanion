<?php
session_start();
include './../bootstrap.php';

$user = onConnect($dbh);

date_default_timezone_set('Europe/Paris');
echo head("MMI Companion | Emploi du temps");


if (isset($_GET['title']) && isset($_GET['description']) && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['color'])) {
    $title = $_GET['title'];
    $description = $_GET['description'];
    $dateStart = $_GET['start']; 
    $dateStartObj = DateTime::createFromFormat('D M d Y H:i:s e+', $dateStart);
    $dateStart = $dateStartObj ? $dateStartObj->format('Y-m-d\TH:i:s') : '';
    $dateEnd = $_GET['end'];
    $dateEndObj = DateTime::createFromFormat('D M d Y H:i:s e+', $dateEnd);
    $dateEnd = $dateEndObj ? $dateEndObj->format('Y-m-d\TH:i:s') : '';
    $location = $_GET['location'];
    $color = $_GET['color'];

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

        <?php generateBurgerMenuContent($user['role']) ?>

         
    </header>
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
            <input type="text" name="title" class="input_title-calendar_add" placeholder="Ajouter un titre" disabled value="<?= $title ?>">
            <div class="trait_agenda_add"></div>

            <div class="content_inputs-calendar_add">
                    <div class="content_inputs_date-calendar_add">
                        <label for="date_start" class="label-calendar_add">
                            <h2>Ajouter une date de début</h2>
                        </label>
                        <div style="height:5px"></div>
                        <div class="container_input_date-calendar_add">
                            <input type="datetime-local" name="date_start" class="input_date-calendar_add input-calendar_add"
                                value="<?php echo str_replace(' ', 'T', $dateStart)  ?>">
                        </div>
                    </div>
                    <div style="height:10px"></div>
                    <div class="content_inputs_date-calendar_add">
                        <label for="date_end" class="label-calendar_add">
                            <h2>Ajouter une date de fin</h2>
                        </label>
                        <div style="height:5px"></div>
                        <div class="container_input_date-calendar_add">
                            <input type="datetime-local" name="date_end" class="input_date-calendar_add input-calendar_add"
                                value="<?php echo str_replace(' ', 'T', $dateEnd) ?>">
                        </div>
                    </div>

                <div class="trait_agenda_add"></div>

                <textarea name="description" class="input-calendar_add input_textarea-calendar_add" placeholder="Ajouter une description" disabled><?php if(isset($description)){echo $description;}; ?></textarea>

                <div class="trait_agenda_add"></div>

                <input type="text" name="location" class="input-calendar_add" placeholder="Ajouter un lieu" value="<?php if(isset($location)){echo $location;}; ?>" disabled>

                <div class="trait_agenda_add"></div>

                <div class="content_input_color-calendar_add">
                    <label for="color" class="label-calendar_add">
                        <h2>Ajouter une couleur</h2>
                    </label>
                    <input type="color" name="color" value="<?= $color ?>" disabled/>
                    
                </div>
            </div>

            <div style="height:25px"></div>
            <div class="form_button-agenda">
                <a role="button" href='./calendar.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>


    </main>
    <script src="../assets/js/menu-navigation.js"></script>
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
<?php
} else {
    header('Location: ./calendar.php');
}
?>