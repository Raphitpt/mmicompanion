<?php
session_start();
require '../bootstrap.php';

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);

$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

$sql_informations = "SELECT * FROM informations WHERE group_info = :edu_group_common
                    UNION ALL
                    SELECT * FROM informations WHERE group_info = :edu_group_perso
                    UNION ALL
                    SELECT * FROM informations WHERE group_info = :edu_group_but";

$query_informations = $dbh->prepare($sql_informations);
$query_informations->execute([
    'edu_group_common' => 'all',
    'edu_group_perso' => $user_sql['edu_group'],
    'edu_group_but' => substr($user_sql['edu_group'], 0, 4)
]);

$informations = $query_informations->fetchAll();

echo head("Informations");
?>
<body class="body-tuto_agenda">
    <!-- Menu de navigation -->
    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header" id="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Informations</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent() ?>
    </header>
    <main>
        <div class="content_main">
            <div class="content_title-main">
                <h2>Informations</h2>
            </div>
            <div class="content_informations">
                <?php foreach ($informations as $information) : ?>
                    <div class="content_information">
                        <div class="content_title-information">
                            <h3><?= $information['titre'] ?></h3>
                        </div>
                        <div class="content_date-information">
                            <h3><?= $information['date'] ?></h3>
                        </div>
                        <div class="content_user-information">
                            <h3><?= $information['user'] ?></h3>
                        </div>
                        <div class="content_text-information">
                            <p><?= $information['content'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>