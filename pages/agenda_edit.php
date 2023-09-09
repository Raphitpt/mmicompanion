<!-- Script pour la gestion d'ajout d'une tache dans l'agenda -->

<?php
session_start();
require "../bootstrap.php";

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// On récupère les données passées en GET
// --------------------
$id_user = $_GET['id_user'];
$id_task = $_GET['id_task'];

// On récupère les données de la tache
$sql_task = "SELECT * FROM agenda INNER JOIN sch_subject ON agenda.id_subject = sch_subject.id_subject WHERE id_task = :id_task AND id_user = :id_user";
$stmt_task = $dbh->prepare($sql_task);
$stmt_task->execute([
    'id_task' => $id_task,
    'id_user' => $id_user
]);
$task = $stmt_task->fetch(PDO::FETCH_ASSOC);

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
    $school_subject = $_POST['school_subject'];
    $sql = "UPDATE agenda SET title=:title, date_finish=:date, type=:type, id_user=:id_user, id_subject=:id_subject, edu_group=:edu_group WHERE id_task=:id_agenda";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'id_user' => $task['id_user'],
        'type' => $type,
        'id_subject' => $school_subject,
        'edu_group' => $user['edu_group'],
        'id_agenda' => $id_task
    ]);
    header('Location: ./agenda.php');
    exit();
}
// --------------------
// Fin de la vérification du formulaire


$sql_user = "SELECT * FROM users WHERE id_user = :id_user";
$stmt_user = $dbh->prepare($sql_user);
$stmt_user->execute([
    ':id_user' => $user['id_user']
]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);


// Petit bout de code pour récupérer les matières dans la base de donnée et les utiliser dans le select du formulaire
// --------------------
// $sql_subject = "SELECT * FROM sch_subject ORDER BY name_subject ASC";

if (strpos($user['edu_group'], 'BUT1') !== false) {
    $sql_subject = "SELECT rs.*, ss.name_subject, ss.id_subject FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R1%' OR rs.code_ressource LIKE 'R2%' OR rs.code_ressource LIKE 'SAE1%' OR rs.code_ressource LIKE 'SAE2%' ORDER BY ss.name_subject ASC";
} elseif (strpos($user['edu_group'], 'BUT2') !== false) {
    $sql_subject = "SELECT rs.*, ss.name_subject, ss.id_subject FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R3%' OR rs.code_ressource LIKE 'R4%' OR rs.code_ressource LIKE 'SAE3%' OR rs.code_ressource LIKE 'SAE4%' ORDER BY ss.name_subject ASC";
} elseif (strpos($user['edu_group'], 'BUT3') !== false) {
    $sql_subject = "SELECT rs.*, ss.name_subject, ss.id_subject FROM sch_ressource rs
    JOIN sch_subject ss ON rs.name_subject = ss.id_subject
    WHERE rs.code_ressource LIKE 'R5%' OR rs.code_ressource LIKE 'R6%' OR rs.code_ressource LIKE 'SAE5%' OR rs.code_ressource LIKE 'SAE6%' ORDER BY ss.name_subject ASC";
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
echo head("MMI Companion - Agenda");

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
                <h1>Agenda</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent() ?>
        
    </header>
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
            <form class="form-agenda_add" method="POST" action="" onsubmit="updatePoints(30)"> 

                <input type="text" name="title" class="input_title-agenda_add" value="<?php echo $task['title'] ?>" required>
                <div class="trait_agenda_add"></div>

                <label for="date" class="label-agenda_add">
                    <h2>Ajouter une date</h2>
                </label>
                <div style="height:5px"></div>
                <div class="container_input-agenda_add">
                    <i class="fi fi-br-calendar"></i>
                    <input type="date" name="date" class="input_date-agenda_add input-agenda_add" value="<?php echo $task['date_finish'] ?>" placeholder="yyyy-mm-dd" min="<?php echo date("Y-m-d")?>" required>
                </div>
                

                
                <!-- Affiche en fonction du role, certaine options sont cachés pour certaines personnes -->
                <?php if ($user['role'] == "chef" || $user['role'] == "admin") { ?>
                    <div style="height:15px"></div>
                    <label for="type" class="label-agenda_add">
                        <h2>Type de tâche</h2>
                    </label>
                    <div style="height:5px"></div>
                    <div class="container_input-agenda_add">
                        <i class="fi fi-br-list"></i>
                        <select name="type" class="input_select-agenda_add input-agenda_add" required>
                            <option value="eval" <?php if (htmlspecialchars($task['type']) == 'eval') echo 'selected'; ?>>Évaluation</option>
                            <option value="devoir" <?php if (htmlspecialchars($task['type']) == 'devoir') echo 'selected'; ?>>Devoir à rendre</option>
                            <option value="autre" <?php if (htmlspecialchars($task['type']) == 'autre') echo 'selected'; ?>>Autre</option>
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
                        <option value="<?php echo $task['id_subject'] ?>" selected><?php echo $task['name_subject'] ?></option>
                        <?php
                        foreach ($subject as $subjects) {
                            if ($subjects['name_subject'] != $task['name_subject']) {
                                echo "<option value='" . $subjects['id_subject'] . "'>" . $subjects['name_subject'] . "</option>";
                            }
                            
                        }
                        ; ?>
                    </select>
                </div>
                
                <div style="height:25px"></div>
                <div class="form_button-agenda">
                    <a role="button" href='./agenda.php'>Annuler</a>
                    <input type="submit" name="submit" value="Valider">
                </div>
                
            </form>
        </div>

    </main>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_agenda-header');
        select_background_profil.classList.add('select_link-header');
    </script>
    
</body>

</html>