<?php

require '../bootstrap.php';
$user = onConnect($dbh);

$user_sql = userSQL($dbh, $user);

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

if ($user_sql['role'] == 'eleve') {
    header('Location: ./informations.php');
    exit;
}

session_start();

if (isset($_POST['submit'])) {
    if (!empty($_POST['titre']) && !empty($_POST['user']) && !empty($_POST['content'])) {
        if (!empty($_POST['group_info'])) {
            $json_group_info = json_decode($_POST['group_info']);
            $group_info = $json_group_info;
            $group_info = implode(',', $group_info);
        }

        $title = $_POST['titre'];
        $name = $_POST['user'];
        $user_role = $_POST['role'];
        $content = $_POST['content'];
        // $content = str_replace('<br />', PHP_EOL, $content);

        if ($user_role == 'chef') {
            $group_info = $user['edu_group'];
        }

        $sql = "UPDATE informations SET titre=:titre, user=:user, user_role=:user_role, content=:content, group_info=:group_info WHERE id_infos=:id_information";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'titre' => $title,
            'user' => $name,
            'user_role' => $user_role,
            'content' => $content,
            'group_info' => $group_info,
            'id_information' => $id_information
        ]);
        header('Location: ./informations.php');
        exit();
    }
}

echo head('MMI Companion | Informations');
?>
<link rel="stylesheet" href="./../trumbowyg/dist/ui/trumbowyg.min.css">

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Informations', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <main class="main_all">
        <div style="height:30px"></div>
        <div class="title_trait">
            <h1>Ajouter une information</h1>
            <div></div>
        </div>
        <div style="height:20px"></div>
        <form action="" method="post" class="form_informations_add" id="formtest">
            <div class="form_title_input-informations_add">
                <input type="text" name="titre" id="titre" placeholder="Ajouter un titre à l'information" required value="<?php echo $information['titre'] ?>">
            </div>
            <div class="form_input-informations_add">
                <label for="user_input">Utilisateur</label>
                <input type="text" name="user" id="user_input" placeholder="Utilisateur" value="<?php echo $information['user'] ?> " readonly>
            </div>
            <?php
            $user_name = "";
            $user_name = substr($user['pname'], 0, 1) . '. ' . $user['name'];

            if (str_contains($user_sql['role'], 'admin')) {
                echo "<div class='form_role_input-informations_add'>";
                if (str_contains($information['user_role'], 'admin')) {
                    echo "<input type='radio' name='role' value='admin' id='admin' checked>";
                } else {
                    echo "<input type='radio' name='role' value='admin' id='admin'>";
                }
                echo "<label for='admin'>Je veux que mon rôle s'affiche en tant qu'administrateur</label>";
                echo "</div>";
            }
            if (str_contains($user_sql['role'], 'chef')) {
                echo "<div class='form_role_input-informations_add'>";
                if (str_contains($information['user_role'], 'chef')) {
                    echo "<input type='radio' name='role' value='chef' id='chef' checked>";
                } else {
                    echo "<input type='radio' name='role' value='chef' id='chef'>";
                }
                echo "<label for='chef'>Je veux que mon rôle s'affiche en tant que chef du TP</label>";
                echo "</div>";
            }
            if (str_contains($user_sql['role'], 'BDE')) {
                echo "<div class='form_role_input-informations_add'>";
                if (str_contains($information['user_role'], 'BDE')) {
                    echo "<input type='radio' name='role' value='BDE' id='bde' checked>";
                } else {
                    echo "<input type='radio' name='role' value='BDE' id='bde'>";
                }
                echo "<label for='bde'>Je veux que mon rôle s'affiche en tant que BDE</label>";
                echo "</div>";
            }
            if (str_contains($user_sql['role'], 'prof')) {
                echo "<div class='form_role_input-informations_add'>";
                echo "<input type='hidden' name='role' value='prof' id='prof'>";
                echo "</div>";
            }
            // if (str_contains($user_sql['role'], 'BDE') || str_contains($user_sql['role'], 'admin') || str_contains($user_sql['role'], 'chef')){
            //     echo "<div class='form_role_input-informations_add'>";
            //     if (str_contains($information['user_role'], $user_name)) {
            //         echo "<input type='radio' name='role' value='" . $user_name . "'id='user' checked>";
            //     }else{
            //         echo "<input type='radio' name='role' value='" . $user_name . "'id='user'>";
            //     }

            //     echo "<label for='user'>Je veux que mon rôle s'affiche avec mon nom d'utilisateur</label>";
            //     echo "</div>";
            // }

            ?>
            <div class="form_content-informations_add">
                <p>Contenu</p>
                <textarea class="form_content_input-informations_add" id="editor"><?php echo $information['content'] ?></textarea>
                <input name="content" id="content" type="hidden">
            </div>





            <div class="form_groupe_input-informations_add">
                <p>Groupe</p>
                <div class="form_groupe_content_input-informations_add"></div>
            </div>
            <input type="hidden" name="group_info" id="group_info">
            <div class="form_button-informations_add">
                <a role="button" href='./informations.php'>Annuler</a>
                <input type="submit" name="submit" class="form_butttonValidate-informations" value="Valider">
            </div>
            <div style="height:20px"></div>

        </form>



    </main>

    <script src="../assets/js/script_all.js?v=1.1"></script>
    <script src="../assets/js/fireworks.js"></script>
    <script src="../assets/js/tree.min.js"></script>
    <script src="./../trumbowyg/dist/trumbowyg.min.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_informations-header');
        select_background_profil.classList.add('select_link-header');
        $('#editor').trumbowyg();
        // let contenuTexte = $('#editor').trumbowyg('html');
        // let about = document.querySelector('#content');
        // about.value = contenuTexte;

        $(document).ready(function() {
            $('#formtest').submit(function(event) {
                var contenuTexte = $('#editor').trumbowyg('html');
                $('#content').val(contenuTexte);
            });
        });
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

            loaded: function() {
                const values = this.values;
                this.values = <?php print($tableauDeChaines) ?>;
            },

            onChange: function() {
                document.getElementById("group_info").value = JSON.stringify(this.values);
                console.log(this.values);
            },
        });

        // Sélectionnez les boutons radio
        let radioInputs = document.querySelectorAll("input[name=role]");
        console.log(radioInputs);

        // Sélectionnez l'élément .form_groupe_input-informations_add
        let formGroupe = document.querySelector(".form_groupe_input-informations_add");

        radioInputs.forEach(radioInput => {

            if (radioInput.id == "chef") {
                formGroupe.classList.add("hidden");
            } else {
                formGroupe.classList.remove("hidden");
            }

            radioInput.addEventListener("change", function() {
                if (radioInput.id == "chef") {
                    formGroupe.classList.add("hidden");
                } else {
                    formGroupe.classList.remove("hidden");
                }
            });

        });
    </script>

</body>

</html>