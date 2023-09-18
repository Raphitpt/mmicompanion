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

$information_array = explode(',', $information['group_info']);
$tableauDeChaines = array_map('trim', $information_array);
$tableauDeChaines = json_encode($tableauDeChaines);
$tableauDeChaines = str_replace('"', "'", $tableauDeChaines);

if ($user_sql['role'] == 'eleve'){
    header('Location: ./informations.php');
    exit;
}

session_start();

if (isset($_POST['submit'])) {
    if(!empty($_POST['titre']) && !empty($_POST['user']) && !empty($_POST['content']) && !empty($_POST['group_info'])){
    $json_group_info = json_decode($_POST['group_info']);
    $group_info = $json_group_info;
    $group_info = implode(',', $group_info);

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
                <input type="text" name="user" id="user" placeholder="Utilisateur" value="<?php echo $information['user']?> " readonly>
            </div>
            <div class="form_input-informations_add">
                <label for="content">Contenu</label>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenu de l'information"><?php echo strip_tags($information['content'])?></textarea>
            </div>
            <div class="form_groupe_input-informations_add">
                
            </div>
            <input type="hidden" name="group_info" id="group_info">
            <div class="form_button-informations_add">
                <a role="button" href='./informations.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>
                
        </form>
    
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script src="../assets/js/tree.min.js"></script>
    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');

        const treeData = [
            {
                id: 'BUT1',
                text: 'BUT1',
                children: [
                    {
                        id: 'BUT-TP1',
                        text: 'TP1',
                    },
                    {   id: 'BUT1-TP2', 
                        text: 'TP2' 
                    },
                    {
                        id: 'BUT1-TP3',
                        text: 'TP3',
                    },
                    {   id: 'BUT1-TP4', 
                        text: 'TP4' 
                    },
                ],
            },
            {
                id: 'BUT2',
                text: 'BUT2',
                children: [
                    {
                        id: 'BUT2-TP1',
                        text: 'TP1',
                    },
                    {   id: 'BUT2-TP2', 
                        text: 'TP2' 
                    },
                    {
                        id: 'BUT2-TP3',
                        text: 'TP3',
                    },
                    {   id: 'BUT2-TP4', 
                        text: 'TP4' 
                    },
                ],
            },
            {
                id: 'BUT3',
                text: 'BUT3',
                children: [
                    {
                        id: 'BUT3-TP1',
                        text: 'TP1',
                    },
                    {   id: 'BUT3-TP2', 
                        text: 'TP2' 
                    },
                    {
                        id: 'BUT3-TP3',
                        text: 'TP3',
                    },
                    {   id: 'BUT3-TP4', 
                        text: 'TP4' 
                    },
                ],
            },
        ];

        const myTree = new Tree('.form_groupe_input-informations_add', {
            data: treeData,
            

            loaded: function() {
                const values = this.values;
                this.values = <?php print($tableauDeChaines) ?>;
            },

            onChange: function() {
                document.getElementById("group_info").value = JSON.stringify(this.values);
                console.log(this.values);
        },
        });
        


    </script>

</body>
</html>