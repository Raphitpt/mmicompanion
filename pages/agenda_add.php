<!-- Script pour la gestion d'ajout d'une tache dans l'agenda -->

<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
// --------------------


$sql_user = "SELECT * FROM users WHERE id_user = :id_user";
$stmt_user = $dbh->prepare($sql_user);
$stmt_user->execute([
    ':id_user' => $user['id_user']
]);
$user_sql = $stmt_user->fetch(PDO::FETCH_ASSOC);

// On vérifie si le formulaire est rempli et si oui on ajoute la tache dans la base de donnée
// On appelle certaines variable du cookie pour les ajouter dans la base de donnée
// --------------------
if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date']) && !empty($_POST['school_subject'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
    } else {
        $type = "autre";
    }
    if (isset($_POST['content']) && !empty($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        $content = "";
    }

    $school_subject = $_POST['school_subject'];
    $sql = "INSERT INTO agenda (title, date_finish, type, id_user, id_subject, edu_group, content) VALUES (:title, :date, :type, :id_user, :id_subject, :edu_group, :content)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'id_user' => $user['id_user'],
        'type' => $type,
        'id_subject' => $school_subject,
        'edu_group' => $user_sql['edu_group'],
        'content' => $content
    ]);
    header('Location: ./agenda.php');
    exit();
}
// Fin de la vérification du formulaire



// Petit bout de code pour récupérer les matières dans la base de donnée et les utiliser dans le select du formulaire
// --------------------
// $sql_subject = "SELECT * FROM sch_subject ORDER BY name_subject ASC";

if (strpos($user_sql['edu_group'], 'BUT1') !== false) {
    $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
    FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R1%' OR rs.code_ressource LIKE 'R2%' OR rs.code_ressource LIKE 'SAE1%' OR rs.code_ressource LIKE 'SAE2%'
    ORDER BY ss.name_subject ASC";
} elseif (strpos($user_sql['edu_group'], 'BUT2') !== false) {
    $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
    FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R3%' OR rs.code_ressource LIKE 'R4%' OR rs.code_ressource LIKE 'SAE3%' OR rs.code_ressource LIKE 'SAE4%'
    ORDER BY ss.name_subject ASC";
} elseif (strpos($user_sql['edu_group'], 'BUT3') !== false) {
    $sql_subject = "SELECT DISTINCT rs.name_subject, ss.id_subject, ss.name_subject
    FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R5%' OR rs.code_ressource LIKE 'R6%' OR rs.code_ressource LIKE 'SAE5%' OR rs.code_ressource LIKE 'SAE6%'
    ORDER BY ss.name_subject ASC";
} else {
    $sql_subject = "SELECT rs.*, ss.name_subject, ss.id_subject FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject ORDER BY ss.name_subject ASC";
}
$stmt_subject = $dbh->prepare($sql_subject);
$stmt_subject->execute();
$subject = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);

// --------------------
// Fin de la récupération des matières

// Obligatoire pour afficher la page
echo head("MMI Companion | Agenda");
?>
<link rel="stylesheet" href="./../trumbowyg/dist/ui/trumbowyg.min.css">

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Agenda') ?>
    <!-- Fin du menu de navigation -->
    
    <!-- Corps de la page -->
    <main class="main-agenda">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Ajouter une tâche</h1>
            <div></div>
        </div>
        <div style="height:25px"></div>
        <div class="agenda-agenda_add">
            <!-- Formualaire d'ajout d'une tache, comme on peut le voir, l'envoi de ce formulaire ajoute 30 points à la personne grâce au code -->
            <form class="form-agenda_add" method="POST" action="" onsubmit="updatePoints(30)" id="formagenda">

                <input type="text" name="title" class="input_title-agenda_add" placeholder="Ajouter un titre" required>
                <div class="trait_agenda_add"></div>
                <div class="form_content-informations_add">
                    <label for="content" class="label-agenda_add">
                        <h2>Ajouter un contenu</h2>
                    </label>
                    <div style="height:5px"></div>
                    <textarea class="form_content_input-informations_add" id="editor"></textarea>
                    <input name="content" id="content" type="hidden">
                </div>
                <div class="trait_agenda_add"></div>
                <label for="date" class="label-agenda_add">
                    <h2>Ajouter une date</h2>
                </label>
                <div style="height:5px"></div>
                <div class="container_date-agenda_add">
                    <div class="container_input_date-agenda_add">
                        <i class="fi fi-br-calendar"></i>
                        <input type="date" name="date" class="input_date-agenda_add input-agenda_add" value="<?php echo date('Y-m-d'); ?>" placeholder="yyyy-mm-dd" min="<?php echo date("Y-m-d") ?>" required>
                    </div>
                    <div id="cocheWeek" class="container_input_week-agenda_add">
                        <input type="checkbox" id="choosenWeek" name="choosenWeek" />
                        <label for="choosenWeek">Afficher les semaines</label>
                    </div>
                </div>
                



                <!-- Affiche en fonction du role, certaine options sont cachés pour certaines personnes -->
                <?php if (str_contains($user_sql['role'], 'chef') || str_contains($user_sql['role'], 'admin')) { ?>
                    <div style="height:15px"></div>
                    <label for="type" class="label-agenda_add">
                        <h2>Type de tâche</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input-agenda_add">
                        <i class="fi fi-br-list"></i>
                        <select name="type" class="input_select-agenda_add input-agenda_add" required>
                            <option value="eval">Évaluation</option>
                            <option value="devoir">Devoir à rendre</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                <?php } ?>

                <div class="trait_agenda_add"></div>
                <label for="type" class="label-agenda_add">
                    <h2>Ajouter une matière</h2>
                </label>
                <div style="height:5px"></div>
                <div class="container_input-agenda_add">
                    <i class="fi fi-br-graduation-cap"></i>
                    <select name="school_subject" class="input_select-agenda_add input-agenda_add" required>
                        <?php
                        foreach ($subject as $subjects) {
                            echo "<option value='" . $subjects['id_subject'] . "'>" . $subjects['name_subject'] . "</option>";
                        }; ?>
                    </select>
                </div>

                <div style="height:25px"></div>
                <div class="form_button-agenda">
                    <a role="button" href='./agenda.php'>Annuler</a>
                    <input type="submit" name="submit" value="Valider">
                </div>
                <div style="height:20px"></div>

            </form>
        </div>

    </main>
    <script src="../assets/js/menu-navigation.js"></script>
    <script src="./../trumbowyg/dist/trumbowyg.min.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');

        // Vérifier si l'utilisateur utilise un appareil iOS
        function isIOS() {
            return /iPhone|iPad|iPod/i.test(navigator.userAgent);
        }

        // Sélectionner l'élément avec la classe .input_date-agenda_add
        const inputElement = document.querySelector('.input_date-agenda_add');

        // Vérifier si l'utilisateur est sur un appareil iOS
        if (isIOS()) {
            // Supprimer le padding-left
            inputElement.style.paddingLeft = '0';
        }
        
        $('#editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['undo', 'redo'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['link'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ],
        });

        $(document).ready(function() {
            $('#formagenda').submit(function(event) {
                var contenuTexte = $('#editor').trumbowyg('html');
                $('#content').val(contenuTexte);
            });
        });

        
        const dateInput = document.querySelector('[name="date"]');
        const choosenWeekCheckbox = document.querySelector('#choosenWeek');
        const cocheWeek = document.querySelector('#cocheWeek');

        function isSafari() {
            return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        }
        // Vérifiez si le navigateur est Safari
        if (isSafari()) {
            // Masquez la case à cocher sur Safari
            cocheWeek.style.display = 'none';
        }
        choosenWeekCheckbox.addEventListener('change', function() {
            if (choosenWeekCheckbox.checked) {
                dateInput.type = 'week';
                dateInput.min = '<?php echo date('Y-\WW') ?>';
                dateInput.placeholder = 'yyyy-Www';
                dateInput.value = '<?php echo date('Y-\WW') ?>';
            } else {
                dateInput.type = 'date';
                dateInput.value = '<?php echo date('Y-m-d'); ?>'
                dateInput.min = '<?php echo date("Y-m-d"); ?>'; // Rétablissez la valeur min
            }
        });
    </script>
</body>

</html>