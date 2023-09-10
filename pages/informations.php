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

$sql_informations = "SELECT informations.*, users.role FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.group_info = :edu_group_common
                    UNION ALL
                    SELECT informations.*, users.role FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.group_info = :edu_group_perso
                    UNION ALL
                    SELECT informations.*, users.role FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.group_info = :edu_group_but";

$query_informations = $dbh->prepare($sql_informations);
$query_informations->execute([
    'edu_group_common' => 'all',
    'edu_group_perso' => $user_sql['edu_group'],
    'edu_group_but' => substr($user_sql['edu_group'], 0, 4)
]);

$informations = $query_informations->fetchAll();

echo head("MMI Companion | Informations");
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
    <main class="main-informations">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Informations</h1>
            <div></div>
        </div>
        <div style="height:20px"></div>
        <div class="container-informations">
            <?php foreach ($informations as $information) : 
                $name_color = "";
                if ($information['role'] == "eleve") {
                    $name_color = "#FFB141";
                } elseif ($information['role'] == "prof") {
                    $name_color = "#5cceff";
                } elseif ($information['role'] == "admin") {
                    $name_color = "#6C757D";
                } elseif (strpos($information['role'], 'BDE') !== false) {
                    $name_color = "#bca5ff";
                }
                ?>
                <div class="item-information">
                    <div class="item_content_title-information">
                        <div class="item_content_title_flexleft-information">
                            <h2><?= $information['titre'] ?></h2>
                            <p><?= $information['date'] ?></p>
                        </div>
                        <div class="item_content_title_flexright-information" style="background-color : <?php echo $name_color ?>">
                            <p><?= $information['user'] ?></p>
                        </div>
                    </div>
                    <div class="item_content_text-information">
                        <p><?= $information['content'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');
    </script>
</body>

</html>