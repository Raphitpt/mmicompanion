<!-- Script pour la gestion d'ajout d'une tache dans l'agenda -->

<?php
session_start();
require "../bootstrap.php";

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// On vérifie si le formulaire est rempli et si oui on ajoute la tache dans la base de donnée
// On appelle certaines variable du cookie pour les ajouter dans la base de donnée
// --------------------
if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['date']) && !empty($_POST['school_subject'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $school_subject = $_POST['school_subject'];
    $sql = "INSERT INTO agenda (title, date_finish, type, id_user, id_subject, edu_group) VALUES (:title, :date, :type, :id_user, :id_subject, :edu_group)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'id_user' => $users['id_user'],
        'type' => $type,
        'id_subject' => $school_subject,
        'edu_group' => $users['edu_group']
    ]);
    header('Location: ./agenda.php');
    exit();
}
// --------------------
// Fin de la vérification du formulaire


// Petit bout de code pour récupérer les matières dans la base de donnée et les utiliser dans le select du formulaire
// --------------------
$sql_subject = "SELECT * FROM sch_subject";
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

        <div class="burger_content-header" id="burger_content-header">
            <div style="height:60px"></div>
            <div class="burger_content_title-header">
                <img src="./../assets/img/mmicompanion.svg" alt="">
                <h1>MMI Companion</h1>
            </div>
            <div class="burger_content_content-header">
                <div class="burger_content_trait_header"></div>
                <a href="./index.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-home"></i>
                        <p>Vue d'ensemble</p>
                    </div>

                </a>
                <a href="./agenda.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-calendar"></i>
                        <p>Agenda</p>
                        <div class="select_link-header"></div>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./messages.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-comment-alt"></i>
                        <p>Messages</p>
                    </div>
                </a>
                <a href="./mail.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-envelope"></i>
                        <p>Boite mail</p>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./sante.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-doctor"></i>
                        <p>Mon bien être</p>
                    </div>
                </a>
                <a href="./profil.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-user"></i>
                        <p>Mon profil</p>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./logout.php">
                    <div class="burger_content_link-header logout-header">
                        <i class="fi fi-br-delete-user"></i>
                        <p>Se déconnecter</p>
                    </div>
                </a>
            </div>
        </div>
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

                <input type="text" name="title" class="input_title-agenda_add" placeholder="Ajouter un titre" required>
                <div class="trait_agenda_add"></div>

                <label for="date" class="label-agenda_add">
                    <h2>Ajouter une date</h2>
                </label>
                <div style="height:5px"></div>
                <div class="container_input-agenda_add">
                    <i class="fi fi-br-calendar"></i>
                    <input type="date" name="date" class="input_date-agenda_add input-agenda_add" value="<?php echo date('Y-m-d'); ?>" placeholder="yyyy-mm-dd" min="<?php echo date("Y-m-d")?>" required>
                </div>
                

                
                <!-- Affiche en fonction du role, certaine options sont cachés pour certaines personnes -->
                <?php if ($users['role'] == "chef" || $users['role'] == "admin") { ?>
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
</body>

</html>