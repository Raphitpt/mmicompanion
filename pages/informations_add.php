<?php

require '../bootstrap.php';
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
  }

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


if ($user_sql['role'] == 'eleve') {
    header('Location: ./informations.php');
    exit;
}


session_start();

if (isset($_POST['submit'])) {
    if (!empty($_POST['titre']) && !empty($_POST['user']) && !empty($_POST['content']) && !empty($_POST['group_info'])) {
        $json_group_info = json_decode($_POST['group_info']);
        $group_info = $json_group_info;
        $group_info = implode(',', $group_info);

        $title = $_POST['titre'];
        $name = $_POST['user'];
        $user_role = $_POST['role'];
        $content = $_POST['content'];

        $sql = "INSERT INTO informations (titre, user, user_role, content, group_info, id_user) VALUES (:titre, :user, :user_role, :content, :group_info, :id_user)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'titre' => $title,
            'user' => $name,
            'user_role' => $user_role,
            'content' => $content,
            'group_info' => $group_info,
            'id_user' => $user['id_user']

        ]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "L'information a bien été ajoutée";
            $message = "Nouvelle information";
            $body = 'Une nouvelle information de ' . $name . ' a été ajoutée !';
            $group = $group_info;
            sendNotification($message, $body, $group);
        } else {
            $_SESSION['error'] = "Une erreur est survenue";
        }
        header('Location: ./informations.php');
        exit();
    }
}

echo head('MMI Companion | Informations');
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
                <input type="text" name="titre" id="titre" placeholder="Ajouter un titre à l'information" required>
            </div>
            <div class="form_input-informations_add">
                <label for="user_input">Utilisateur</label>
                <input type="text" name="user" id="user_input" placeholder="Utilisateur" value="<?php echo substr($user['pname'], 0, 1) . '. ' . $user['name']; ?>" readonly>
            </div>
            <?php 
                if(str_contains($user_sql['role'], 'admin')){
                    echo "<div class='form_role_input-informations_add'>";
                    echo "<input type='radio' name='role' value='admin' id='admin'>";
                    echo "<label for='admin'>Je veux que mon rôle s'affiche en tant qu'administrateur</label>";
                    echo "</div>";
                }
                if (str_contains($user_sql['role'], 'chef')){
                    echo "<div class='form_role_input-informations_add'>";
                    echo "<input type='radio' name='role' value='chef' id='chef'>";
                    echo "<label for='chef'>Je veux que mon rôle s'affiche en tant que chef du TP</label>";
                    echo "</div>";
                }
                if (str_contains($user_sql['role'], 'BDE')){
                    echo "<div class='form_role_input-informations_add'>";
                    echo "<input type='radio' name='role' value='BDE' id='bde'>";
                    echo "<label for='bde'>Je veux que mon rôle s'affiche en tant que BDE</label>";
                    echo "</div>";
                }

                if (str_contains($user_sql['role'], 'BDE') || str_contains($user_sql['role'], 'admin') || str_contains($user_sql['role'], 'chef')){
                    echo "<div class='form_role_input-informations_add'>";
                    echo "<input type='radio' name='role' value='" . substr($user['pname'], 0, 1) . '. ' . $user['name'] . "'id='user' checked>";
                    echo "<label for='user'>Je veux que mon rôle s'affiche avec mon nom d'utilisateur</label>";
                    echo "</div>";
                }
            
            ?>
            <div class="form_input-informations_add">
                <label for="content">Contenu</label>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenu de l'information"></textarea>
            </div>
            <div class="form_groupe_input-informations_add">
                <p>Groupe</p>
                <div class="form_groupe_content_input-informations_add"></div>
            </div>
            <input type="hidden" name="group_info" id="group_info">
            <div class="form_button-informations_add">
                <a role="button" href='./informations.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>
            <div style="height:20px"></div>

        </form>

    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script src="../assets/js/tree.min.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');

        const treeData = [{
                id: 'BUT1',
                text: 'BUT1',
                children: [{
                        id: 'BUT1-TP1',
                        text: 'TP1',
                    },
                    {
                        id: 'BUT1-TP2',
                        text: 'TP2'
                    },
                    {
                        id: 'BUT1-TP3',
                        text: 'TP3',
                    },
                    {
                        id: 'BUT1-TP4',
                        text: 'TP4'
                    },
                ],
            },
            {
                id: 'BUT2',
                text: 'BUT2',
                children: [{
                        id: 'BUT2-TP1',
                        text: 'TP1',
                    },
                    {
                        id: 'BUT2-TP2',
                        text: 'TP2'
                    },
                    {
                        id: 'BUT2-TP3',
                        text: 'TP3',
                    },
                    {
                        id: 'BUT2-TP4',
                        text: 'TP4'
                    },
                ],
            },
            {
                id: 'BUT3',
                text: 'BUT3',
                children: [{
                        id: 'BUT3-TP1',
                        text: 'TP1',
                    },
                    {
                        id: 'BUT3-TP2',
                        text: 'TP2'
                    },
                    {
                        id: 'BUT3-TP3',
                        text: 'TP3',
                    },
                    {
                        id: 'BUT3-TP4',
                        text: 'TP4'
                    },
                ],
            },
        ];

        const myTree = new Tree('.form_groupe_content_input-informations_add', {
            data: treeData,
            closeDepth: 1,
            onChange: function() {
                document.getElementById("group_info").value = JSON.stringify(this.values);
                console.log(this.values);
            },
        });
    </script>

</body>

</html>