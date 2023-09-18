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


if ($user_sql['role'] == 'eleve') {
    header('Location: ./informations.php');
    exit;
}

session_start();

if (isset($_POST['submit'])) {
    if (!empty($_POST['titre']) && !empty($_POST['user']) && !empty($_POST['content']) && !empty($_POST['group_info'])) {
        if ($_POST['group_info'] == 'all') {
            $group_info = 'all';
        } else {
            foreach ($_POST['group_info'] as $group) {
                foreach ($_POST['tp_info'] as $tp) {
                    $group_info[] = $group . '-' . $tp;
                }
            };
            $group_info = implode(',', $group_info);
        }
        $title = $_POST['titre'];
        $name = $_POST['user'];
        $content = $_POST['content'];

        $sql = "INSERT INTO informations (titre, user, content, group_info, id_user) VALUES (:titre, :user, :content, :group_info, :id_user)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'titre' => $title,
            'user' => $name,
            'content' => $content,
            'group_info' => $group_info,
            'id_user' => $user['id_user']

        ]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "L'information a bien été ajoutée";
            if ($group_info == 'all') {
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
                <input type="text" name="titre" id="titre" placeholder="Ajouter un titre à l'information" required>
            </div>
            <div class="form_input-informations_add">
                <label for="user">Utilisateur</label>
                <input type="text" name="user" id="user" placeholder="Utilisateur" value="<?php echo substr($user['pname'], 0, 1) . '. ' . $user['name']; ?>" readonly>
            </div>
            <div class="form_input-informations_add">
                <label for="content">Contenu</label>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="Contenu de l'information"></textarea>
            </div>
            <div class="form_groupe_input-informations_add">
                <div class="form_groupe_content_input-informations_add">
                    <label for="group_info[]">Groupe :</label>
                    <div class="form_container_checkbox-informations_add">
                        <div>
                            <input type="checkbox" name="group_info" value="all" />
                            <label for="group_info">Tous</label>
                        </div>
                        <div>
                            <input type="checkbox" name="group_info[]" value="BUT1" />
                            <label for="group_info[]">BUT1</label>
                        </div>
                        <div>
                            <input type="checkbox" name="group_info[]" value="BUT2" />
                            <label for="group_info[]">BUT2</label>
                        </div>
                        <div>
                            <input type="checkbox" name="group_info[]" value="BUT3" />
                            <label for="group_info[]">BUT3</label>
                        </div>
                    </div>

                </div>
                <div class="form_groupe_content_input-informations_add">
                    <label for="tp_info">TP :</label>
                    <div class="form_container_checkbox-informations_add">
                        <div>
                            <input type="checkbox" name="tp_info[]" value="TP1" disabled />
                            <label for="tp_info[]">TP1</label>
                        </div>
                        <div>
                            <input type="checkbox" name="tp_info[]" value="TP2" disabled />
                            <label for="tp_info[]">TP2</label>
                        </div>
                        <div>
                            <input type="checkbox" name="tp_info[]" value="TP3" disabled />
                            <label for="tp_info[]">TP3</label>
                        </div>
                        <div>
                            <input type="checkbox" name="tp_info[]" value="TP4" disabled />
                            <label for="tp_info[]">TP4</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form_button-informations_add">
                <a role="button" href='./informations.php'>Annuler</a>
                <input type="submit" name="submit" value="Valider">
            </div>

        </form>

    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');

        window.addEventListener('DOMContentLoaded', () => {
            const tousCheckbox = document.querySelector('input[name="group_info"][value="all"]');
            const groupCheckboxes = document.querySelectorAll('input[name="group_info[]"]');
            const tpCheckboxes = document.querySelectorAll('input[name="tp_info[]"]');
            const but1Checkbox = document.querySelector('input[name="group_info[]"][value="BUT1"]');
            const but2Checkbox = document.querySelector('input[name="group_info[]"][value="BUT2"]');
            const but3Checkbox = document.querySelector('input[name="group_info[]"][value="BUT3"]');

            function toggleCheckboxes(checkboxes, isEnabled) {
                checkboxes.forEach(function(checkbox) {
                    if (checkbox !== tousCheckbox) {
                        checkbox.disabled = isEnabled;
                    }
                });
            }
            tousCheckbox.addEventListener('click', function() {
                toggleCheckboxes(groupCheckboxes, tousCheckbox.checked);
                // toggleCheckboxes(tpCheckboxes, tousCheckbox.checked);
            });

            function enableTpCheckboxes() {
                let isAnyButChecked = but1Checkbox.checked || but2Checkbox.checked || but3Checkbox.checked;
                tpCheckboxes.forEach(function(checkbox) {
                    checkbox.disabled = !isAnyButChecked;
                });
            }

            but1Checkbox.addEventListener('click', enableTpCheckboxes);
            but2Checkbox.addEventListener('click', enableTpCheckboxes);
            but3Checkbox.addEventListener('click', enableTpCheckboxes);
        });
    </script>

</body>

</html>