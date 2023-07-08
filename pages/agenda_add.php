<?php
session_start();
require "../bootstrap.php";

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français

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


$sql_subject = "SELECT * FROM sch_subject";
$stmt_subject = $dbh->prepare($sql_subject);
$stmt_subject->execute();
$subject = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);


echo head("Ajouter une tache");
?>

<body class="body-agenda">
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
            </div>
        </div>
    </header>

    <main class="main-agenda">
        <div style="height:30px"></div>
        <div class="agenda_title-agenda_add">
            <h1>Ajouter une tâche</h1>
            <div></div>
        </div>
        <div style="height:25px"></div>
        <div class="agenda-agenda_add">
            <form class="form-agenda_add" method="POST" action="">
                <input type="text" name="title" class="input_title-agenda_add" placeholder="Ajouter un titre">
                <div class="trait_agenda_add"></div>
                <label for="date" class="label-agenda_add">
                    <h2>Ajouter une date</h2>
                </label>
                <div style="height:5px"></div>
                <input type="date" name="date" class="input_date-agenda_add" placeholder="Ajouter une date">
                <div style="height:15px"></div>
                <label for="type" class="label-agenda_add">
                    <h2>Type de tâche</h2>
                </label>
                <div style="height:5px"></div>
                <?php if ($users['role'] == "chef" || $users['role'] == "admin") { ?>
                    <select name="type" class="input_select-agenda_add input_type-agenda_add">
                        <option value="eval">Évaluation</option>
                        <option value="devoir">Devoir à rendre</option>
                        <option value="3">Autre</option>
                    </select>
                <?php }else{ ?>
                    <select name="type" class="input_select-agenda_add input_type-agenda_add">
                        <option value="3">Autre</option>
                    </select>
                <?php } ?>

                <div class="trait_agenda_add"></div>
                <label for="type" class="label-agenda_add">
                    <h2>Ajouter une matière</h2>
                </label>
                <div style="height:5px"></div>
                <select name="school_subject" class="input_select-agenda_add input_school-agenda_add">
                    <?php
                    foreach ($subject as $subjects) {
                        echo "<option value='" . $subjects['id_subject'] . "'>" . $subjects['name_subject'] . "</option>";
                    }
                    ; ?>
                </select>
                <div style="height:25px"></div>
                <div class="form_button-agenda">
                    <a role="button" href='./agenda.php'>Annuler</a>
                    <input type="submit" name="submit" value="Valider">
                </div>
                
                
            </form>
        </div>

    </main>
    <script src="../assets/js/script.js"></script>
</body>

</html>