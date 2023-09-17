<?php

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

$id_user = $_GET['id_user'];
$id_information = $_GET['id_information'];

$sql_information = "SELECT * FROM informations WHERE id_infos = :id_information AND id_user = :id_user";
$stmt_information = $dbh->prepare($sql_information);
$stmt_information->execute([
    'id_information' => $id_information,
    'id_user' => $id_user
]);
$information = $stmt_information->fetch(PDO::FETCH_ASSOC);


if ($user_sql['role'] == 'eleve'){
    header('Location: ./informations.php');
    exit;
}

session_start();

if (isset($_POST['submit'])) {
    if(!empty($_POST['titre']) && !empty($_POST['user']) && !empty($_POST['content']) && !empty($_POST['group_info'])){
        $group_info = $_POST['group_info'];
        if(isset($_POST['tp_info']) && !empty($_POST['tp_info'])){
           $group_info = $group_info . '-' . $_POST['tp_info'];
        }
    $title = $_POST['titre'];
    $name = $_POST['user'];
    $content = $_POST['content'];
    
    $sql = "UPDATE informations SET titre=:titre, user=:user, content=:content, group_info=:group_info WHERE id_infos=:id_information";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'titre' => $title,
        'user' => $name,
        'content' => $content,
        'group_info' => $group_info,
        'id_information' => $id_information
    ]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "L'information a bien été ajoutée";
        if ($group_info == 'all'){
            $message = "Nouvelle information";
            $body = 'Une nouvelle information a été ajoutée';
            $group = '';
            sendNotification($message, $body, $group);
        } else {
            $message = "Nouvelle information";
            $body = 'Une nouvelle information a été ajoutée';
            $group = $group_info;
            sendNotification($message, $body, $group);
        }
    } else {
        $_SESSION['error'] = "Une erreur est survenue";
    }
    header('Location: ./informations.php');
    exit();
    }
}

echo head('Ajouter une information');
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
                <h1>Informations</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent() ?>
    </header>
    <main class="main-informations">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Ajouter une information</h1>
            <div></div>
        </div>
        <div style="height:20px"></div>
        <form action="" method="post" class="form_informations_add">
            <div class="form_title_input-informations_add">
                <input type="text" name="titre" id="titre" placeholder="Ajouter un titre à l'information" required value="<?php echo $information['titre']?>">
            </div>
            <div class="form_input-informations_add">
                <label for="user">Utilisateur</label>
                <input type="text" name="user" id="user" placeholder="Utilisateur" value="<?php echo $information['user']?> " disabled>
            </div>
            <div class="form_input-informations_add">
                <label for="content">Contenu</label>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenu de l'information"><?php echo strip_tags($information['content'])?></textarea>
            </div>
            <div class="form_groupe_input-informations_add">
                <div class="form_groupe_content_input-informations_add">
                    <label for="group_info">Groupe :</label>
                    <div class="form_container_checkbox-informations_add">
                        <div>
                            <input type="checkbox"  name="group_info" />
                            <label for="group_info">BUT1</label>
                        </div>
                        <div>
                            <input type="checkbox" name="group_info" />
                            <label for="group_info">BUT2</label>
                        </div>
                        <div>
                            <input type="checkbox"  name="group_info" />
                            <label for="group_info">BUT3</label>
                        </div>
                    </div>
                    <!-- <select name="group_info" id="group_info">
                        <option value="all">Tous</option>
                        <option value="BUT1">BUT1</option>
                        <option value="BUT2">BUT2</option>
                        <option value="BUT3">BUT3</option>
                    </select> -->
                </div>
                <div class="form_groupe_content_input-informations_add">
                    <label for="tp_info">TP :</label>
                    <div class="form_container_checkbox-informations_add">
                        <div>
                            <input type="checkbox"  name="tp_info" />
                            <label for="tp_info">TP1</label>
                        </div>
                        <div>
                            <input type="checkbox" name="tp_info" />
                            <label for="tp_info">TP2</label>
                        </div>
                        <div>
                            <input type="checkbox"  name="tp_info" />
                            <label for="tp_info">TP3</label>
                        </div>
                        <div>
                            <input type="checkbox" name="tp_info" />
                            <label for="tp_info">TP4</label>
                        </div>
                    </div>
                    
                    <!-- <select name="tp_info" id="tp_info" disabled>
                        <option value="">Tous</option>
                        <option value="TP1">TP1</option>
                        <option value="TP2">TP2</option>
                        <option value="TP3">TP3</option>
                        <option value="TP4">TP4</option>
                    </select> -->
                </div>      
            </div>
            <div class="form_button-informations_add">
                <a role="button" href='./informations.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>
                
        </form>
    
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <!-- <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');

        // -------------------------

        window.addEventListener('DOMContentLoaded', function(){
            const group = document.querySelector('#group_info');
            const tp = document.querySelector('#tp_info');
            group.addEventListener('change', function(){
                if (group.value == 'all') {
                    tp.disabled = true;
                } else {
                    tp.disabled = false;
                }
            })
        })

    </script> -->

</body>
</html>