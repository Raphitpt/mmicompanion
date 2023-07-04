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
<main>
    <div class="agenda_add">
        <form method="POST" action="">
            <input type="text" name="title" placeholder="Titre de l'évènement">
            <input type="date" name="date" placeholder="Date de l'évènement">
            <?php if ($users['role'] == "chef" || $users['role'] == "admin") { ?>
                <select name="type">
                    <option value="eval">Évaluation</option>
                    <option value="devoir">Devoir à rendre</option>
                    <option value="3">Autre</option>
                </select>
            <?php } ?>
            <select name="school_subject">
                <?php
                foreach ($subject as $subjects) {
                    echo "<option value='" . $subjects['id_subject'] . "'>" . $subjects['name_subject'] . "</option>";
                }; ?>
            </select>
            <input type="submit" name="submit" value="Ajouter">
        </form>
    </div>
   
</main>