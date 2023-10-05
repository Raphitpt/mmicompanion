<?php
session_start();
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

$sql_informations = "SELECT informations.*, users.role FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.group_info = :edu_group_common
                    UNION ALL
                    SELECT informations.*, users.role FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.group_info LIKE :edu_group_perso ORDER BY date DESC";

$query_informations = $dbh->prepare($sql_informations);
$query_informations->execute([
    'edu_group_common' => 'all',
    'edu_group_perso' => '%' . $user_sql['edu_group'] . '%'
]);

if ($user_sql['role'] === "prof") {
    $sql_informations = "SELECT informations.*, users.role, users.name, users.pname FROM informations INNER JOIN users ON informations.id_user = users.id_user WHERE informations.id_user = :id_user ORDER BY date DESC";
    $query_informations = $dbh->prepare($sql_informations);
    $query_informations->execute([
        'id_user' => $user['id_user'],
    ]);
}

$informations = $query_informations->fetchAll();

if (isset($_POST['submit'])) {
    $group_info = $_POST['group_info'];
    if ($group_info=="") {
        $sql_informations = "SELECT * FROM informations WHERE id_user = :id_user AND user_role = 'prof'";
        $query_informations = $dbh->prepare($sql_informations);
        $query_informations->execute([
            'id_user' => $user['id_user'],
        ]);
    }else{
        $sql_informations = "SELECT * FROM informations WHERE id_user = :id_user AND user_role = 'prof' AND group_info = :group_info";
        $query_informations = $dbh->prepare($sql_informations);
        $query_informations->execute([
            'id_user' => $user['id_user'],
            'group_info' => $group_info
        ]);
    }
    $informations_prof = $query_informations->fetchAll();
} else{
    $sql_informations = "SELECT * FROM informations WHERE id_user = :id_user AND user_role = 'prof'";
    $query_informations = $dbh->prepare($sql_informations);
    $query_informations->execute([
        'id_user' => $user['id_user'],
    ]);
    $informations_prof = $query_informations->fetchAll();
}




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

        <?php generateBurgerMenuContent($user_sql['role']) ?>
    </header>
    <main class="main-informations">
        <div style="height:30px"></div>
        <div class="info_title_flextop-informations">
            <div class="title_trait">
                <h1>Informations</h1>
                <div></div>
            </div>
            <?php 
            if (str_contains($user_sql['role'], "chef") || str_contains($user_sql['role'], "admin") || str_contains($user_sql['role'], "prof") || str_contains($user_sql['role'], "BDE")) {
                ?>
                <div class="info_title_flextopright-informations">
                    <a href="./informations_add.php">Ajouter</a>
                </div>
                <?php
            } ?>
        </div>
        <div style="height:20px"></div> 
        <div class="container-informations">
            <?php  
                foreach ($informations as $information) : 
                    $name_color = "";
                    $timestamp = strtotime($information['date']); // Convertit la date en timestamp
                    $newDate = date("d-m-Y H:i", $timestamp);
                    $userRole = $information['user_role'];
                    if (str_contains($information['user_role'], "prof")) {
                        $name_color = "#5cceff";
                        $userRole = $information['user'];
                    } elseif (str_contains($information['user_role'], "chef")) {
                        $name_color = "#FFA02F";
                    } elseif (str_contains($information['user_role'], "admin")) {
                        $name_color = "#FF3333";
                    } elseif (str_contains($information['user_role'], "BDE")) {
                        $name_color = "#bca5ff";
                    } else{
                        $name_color = "#C882FF";
                    }
                    ?>
                    <div class="item-information">
                        <div class="item_content_title-information">
                            <div class="item_content_title_flextop-information">
                                <h2><?= $information['titre'] ?></h2>
                            </div>
                            <div class="item_content_title_flexbottom-information">
                                <p><?= $newDate ?></p>
                                <p style="background-color : <?php echo $name_color ?>"><?= ucwords($userRole) ?></p>
                            </div>
                        </div>
                        <div class="item_content_text-information">
                            <?php 
                            echo nl2br($information['content']); 
                            ?>
                        </div>
                        <?php if($information['id_user'] === $user['id_user'] || str_contains($user_sql['role'], "admin")){ ?>
                        <div class="item_button-informations">
                            <a href='./information_edit.php?id_user=<?php echo $user['id_user'] ?>&id_information=<?php echo $information['id_infos'] ?>'><i class='fi fi-br-pencil blue'></i></a>
                            <a href='./information_delete.php?id_user=<?php echo $user['id_user'] ?>&id_infos=<?php echo $information['id_infos'] ?>'><i class='fi fi-br-trash red'></i></a>
                        </div>
                        <?php } ?>
                    </div>
                <?php endforeach; 

            ?>
        </div>
        <div style="height:30px"></div>
    </main>

    <script src="../assets/js/menu-navigation.js"></script>
    <script src='../assets/js/tree.min.js'></script>
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

        const myTree = new Tree('.form_groupe_content_input-informations', {
            data: treeData,
            closeDepth: 1,
            onChange: function() {
                document.getElementById("group_info").value = this.values;
                let group_info = document.getElementById("group_info").value;
                console.log(group_info);
            },
        });


        // let buttonValidate = document.querySelector('.form_button-informations_add input');
        // let groupInfo = document.querySelector('#group_info');

        // buttonValidate.addEventListener('click', function() {
        //     groupInfo.value="";
        // })
        
    </script>
</body>

</html>