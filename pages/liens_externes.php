<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);


// Obligatoire pour afficher la page
echo head("MMI Companion | Liens externes");

?>

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Liens externes') ?>

    <main class="main_all">
        <div style="height:30px"></div>
        <div class="container-liens_externes">
            <a href="https://zimbra.univ-poitiers.fr" target="_blank">
                <div class="item-liens_externes red">
                    <div class="item_flextop-liens_externes">
                        <h1>Messagerie
                        <br>(webmail)
                        </h1>
                        <img src="./../assets/img/messagerie.webp" alt="Une personne envoyant un email">
                    </div>
                    <div class="item_flexbottom-liens_externes">
                        <?php 
                        if ($user_sql['role'] == "prof") {
                            echo "<p>Votre messagerie de l’université de Poitiers</p>";
                        }else{
                            echo "<p>Ta messagerie de l’université de Poitiers</p>";
                        }
                        ?>
                    </div>
                </div>
            </a>
            <a href="https://cas.univ-poitiers.fr/cas/login?service=https://ent.univ-poitiers.fr/uPortal/Login" target="_blank">
                <div class="item-liens_externes purple">
                    <div class="item_flextop-liens_externes">
                        <h1>ENT</h1>
                        <img src="./../assets/img/ENT.webp" alt="Une personne qui travaille">
                    </div>
                    <div class="item_flexbottom-liens_externes">
                        <?php 
                        if ($user_sql['role'] == "prof") {
                            echo "<p>Votre espace numérique de travail</p>";
                        }else{
                            echo "<p>Ton espace numérique de travail</p>";
                        }   
                        ?>
                    </div>
                </div>
            </a>
            <a href="https://auth.univ-poitiers.fr/cas/login?service=https%3A%2F%2Fupdago.univ-poitiers.fr%2Flogin%2Findex.php%3FauthCAS%3DCAS" target="_blank">
                <div class="item-liens_externes orange">
                    <div class="item_flextop-liens_externes updago_img">
                        <h1>UPdago</h1>
                        <img src="./../assets/img/UPdago.webp" alt="Logo de UPdago">
                    </div>
                    <div class="item_flexbottom-liens_externes">
                        <?php 
                        if ($user_sql['role'] == "prof") {
                            echo "<p>Votre plateforme d’enseignement en ligne</p>";
                            
                        }else{
                            echo "<p>Ta plateforme d’enseignement en ligne</p>";
                        }
                        ?>
                    </div>
                </div>
            </a>
            <a href="https://iut-angouleme.univ-poitiers.fr/" target="_blank">
                <div class="item-liens_externes blue">
                    <div class="item_flextop-liens_externes iut_img">
                        <h1>IUT Angoulême</h1>
                        <img src="./../assets/img/Logo_IUT_Angoulême.webp" alt="Logo de l'IUT d'Angoulême">
                    </div>
                    <div class="item_flexbottom-liens_externes">
                        <p>Site internet de l'IUT d'Angoulême</p>
                    </div>
                </div>
            </a>
            <a href="https://concours48h.fr" target="_blank">
                <div class="item-liens_externes brown">
                    <div class="item_flextop-liens_externes concours48h_img">
                        <h1>Concours 48h</h1>
                        <img src="./../assets/img/logo_48h.webp" alt="Logo du concours 48h">
                    </div>
                    <div class="item_flexbottom-liens_externes">
                        <p>Le site officiel du concours 48h</p>
                    </div>
                </div>
            </a>
        
        </div>
        
        <div style="height:30px"></div>

        <canvas id="fireworks"></canvas>
      </main>

      <script src="../assets/js/script_all.js?v=1.1"></script> 
        <script src="../assets/js/fireworks.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_liens_externes-header');
        select_background_profil.classList.add('select_link-header');

        // const menu_jour = document.querySelector('#menu_jour');
        // const precedent = document.querySelector('#precedent');
        // const suivant = document.querySelector('#suivant');
        // const xhr = new XMLHttpRequest();
        // xhr.open('POST', '../pages/menu.php');
        // xhr.onload = () => {
        //     if (xhr.status === 200) {
        //         const data = JSON.parse(xhr.responseText);
        //         menu_jour.innerHTML = data;

                
        //     }
        // }
        // xhr.send();

        // precedent.addEventListener('click', function(){
        //     const mealJourney = document.querySelectorAll('.meal');
        //     mealJourney.forEach(meal => {
        //         if (meal.classList.contains('active')) {
        //             meal.classList.remove('active');
        //             // selectionne la div précédente qui à la classe meal
        //             meal.previousElementSibling.classList.add('active');
        //         }
        //     })

        // });

        // suivant.addEventListener('click', function(){
        //     const mealJourney = document.querySelectorAll('.meal');
        //     mealJourney.forEach(meal => {
        //         if (meal.classList.contains('active')) {
        //             meal.classList.remove('active');
        //             meal.nextElementSibling.classList.add('active');
        //         }
        //     })
        // });

    </script>
</body>
</html>
