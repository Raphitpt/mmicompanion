<!-- Script pour la gestion d'ajout d'une tache dans l'agenda -->

<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);

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
if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date']) && !empty($_POST['school_subject']) && !empty($_POST['but']) && !empty($_POST['tp'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    if (isset($_POST['content']) && !empty($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        $content = "";
    }

    if ($_POST['tp'] == "TDA" || $_POST['tp'] == "TDB") {
        if ($_POST['tp'] == "TDA") {
            $td = [
                'TP1',
                'TP2'
            ];
        } else {
            $td = [
                'TP3',
                'TP4'
            ];
        }

        // On rèpète pour faire 2 lignes dans la base de donnée par tp dans le TD
        foreach ($td as $tpValue) {
            $edu_group = $_POST['but'] . "-" . $tpValue;

            $school_subject = $_POST['school_subject'];
            $sql = "INSERT INTO agenda (title, date_finish, type, id_user, id_subject, edu_group, content) VALUES (:title, :date, :type, :id_user, :id_subject, :edu_group, :content)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'title' => $title,
                'date' => $date,
                'id_user' => $user['id_user'],
                'type' => $type,
                'id_subject' => $school_subject,
                'edu_group' => $edu_group,
                'content' => $content
            ]);
        }
    } else {
        if ($_POST['tp'] == "all") {
            $edu_group = $_POST['but'];
        } else {
            $edu_group = $_POST['but'] . "-" . $_POST['tp'];
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
            'edu_group' => $edu_group,
            'content' => $content

        ]);
    }

    header('Location: ./agenda_prof.php');
    exit();
}

// Fin de la vérification du formulaire

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
                <div class="container_input_date-agenda_add">
                    <i class="fi fi-br-calendar"></i>
                    <input type="date" name="date" class="input_date-agenda_add input-agenda_add" value="<?php echo date('Y-m-d'); ?>" placeholder="yyyy-mm-dd" min="<?php echo date("Y-m-d") ?>" required>
                </div>
                <div id="cocheWeek">
                    <input type="checkbox" id="choosenWeek" name="choosenWeek" />
                    <label for="choosenWeek">Afficher les semaines</label>
                </div>
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
                    </select>
                </div>
                <div style="height:15px"></div>
                <label for="type" class="label-agenda_add">
                    <h2>Sélectionner un groupe</h2>
                </label>
                <div style="height:5px"></div>
                <div class="select_but_agenda_prof">
                    <select name="but" id="but">
                        <?php
                        $butOptions = array("BUT1", "BUT2", "BUT3");
                        $selectedBut = isset($_GET['but']) ? $_GET['but'] : '';

                        foreach ($butOptions as $option) {
                            $selected = ($selectedBut === $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>

                    <select name="tp" id="tp">
                        <option value="all">Tous</option>
                        <option disabled>------ TD ------</option>
                        <option value="TDA">TDA</option>
                        <option value="TDB">TDB</option>
                        <option disabled>------ TP ------</option>
                        <?php
                        $tpOptions = array("TP1", "TP2", "TP3", "TP4");
                        $selectedTp = isset($_GET['tp']) ? $_GET['tp'] : '';

                        foreach ($tpOptions as $option) {
                            $selected = ($selectedTp === $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="trait_agenda_add"></div>
                <label for="type" class="label-agenda_add">
                    <h2>Ajouter une matière</h2>
                </label>
                <div style="height:5px"></div>
                <!-- <div class="container_input-agenda_add">
                    <i class="fi fi-br-graduation-cap"></i>
                    <input type="text" name="school_subject" class="input_select-agenda_add input-agenda_add" id="search_subject" required>
                    <input type="hidden" name="subject" id="subject" required>
                </div> -->
                <div class="container_input-agenda_add">
                    <i class="fi fi-br-graduation-cap"></i>
                    <select name="school_subject" class="input_select-agenda_add input-agenda_add" id="selectSubject" required>
                    </select>
                </div>

                <div style="height:25px"></div>
                <div class="form_button-agenda">
                    <a role="button" href='./agenda_prof.php'>Annuler</a>
                    <input type="submit" name="submit" value="Valider">
                </div>
                <div style="height:20px"></div>

            </form>
        </div>

        <canvas id="fireworks"></canvas>

      </main>
      <script src="../assets/js/menu-navigation.js?v=1.1"></script> 
        <script src="../assets/js/fireworks.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="./../trumbowyg/dist/trumbowyg.min.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');

        window.addEventListener('load', loadSubject);

        // Vérifier si l'utilisateur utilise un appareil iOS
        function isIOS() {
            return /iPhone|iPad|iPod/i.test(navigator.userAgent);
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

        // Sélectionner l'élément avec la classe .input_date-agenda_add
        const inputElement = document.querySelector('.input_date-agenda_add');

        // Vérifier si l'utilisateur est sur un appareil iOS
        if (isIOS()) {
            // Supprimer le padding-left
            inputElement.style.paddingLeft = '0';
        }
        const butSelect = document.querySelector('#but');

        function loadSubject() {
            const selectedBut = butSelect.value;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const selectSubject = document.getElementById('selectSubject'); // Obtenez l'élément select par son ID

                    // Supprimez toutes les options actuelles de l'élément select
                    while (selectSubject.firstChild) {
                        selectSubject.removeChild(selectSubject.firstChild);
                    }

                    // Parcourez les données et ajoutez les options dynamiquement
                    response.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id_subject;
                        option.textContent = subject.name_subject;
                        selectSubject.appendChild(option);
                    });
                }
            };

            // Préparez les données à envoyer en tant que paramètres POST
            const data = new FormData();
            data.append('but', selectedBut);

            // Envoyer la requête POST vers agenda.php
            xhr.open('POST', 'get_subject.php', true);
            xhr.send(data);
        }
        butSelect.addEventListener('change', loadSubject);
    </script>
    <script>
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