<?php
session_start();
require './../bootstrap.php';

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



echo head('MMI Companion | Menu du Crousty');

?>

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Menu du Crousty') ?>

    <main class="main-menu">
        <div style="height:30px"></div>
        <div class="menu_title-menu">
            <div class="title_trait">
                <h1>Le menu du Crousty</h1>
                <div></div>
            </div>
            <div class="content_menu_title-menu">
                <div class="item_menu_title-menu">
                    <i class="fi fi-br-clock"></i>
                    <div class="content_item_menu_title-menu">
                        <p>Horaires</p>
                        <p>Du lundi au vendredi de 11h45 à 13h30.</p>
                    </div>
                </div>
                <div class="item_menu_title-menu">
                    <i class="fi fi-sr-phone-call"></i>
                    <div class="content_item_menu_title-menu">
                        <p>Contact</p>
                        <a href="tel:0545255151">05 45 25 51 51</a>
                    </div>
                </div>
                <div class="item_menu_title-menu">
                    <i class="fi fi-br-site-alt"></i>
                    <div class="content_item_menu_title-menu">
                        <p>Site web</p>
                        <a href="https://www.crous-poitiers.fr/restaurant/r-u-crousty/" target="_blank">https://www.crous-poitiers.fr/restaurant/r-u-crousty/</a>
                    </div>
                </div>
                

            </div>
        </div>

        <div style="height:25px"></div>
        <div class="menu_content-menu">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                <?php 
                    // Récupération du menu
                    $menuDataByDay = getMenu();

                    foreach ($menuDataByDay as $date => $menuInfo) {
                        // Obtenez la date actuelle au format "l j F Y"
                        $currentDate = date('j F Y');

                        // Convertir le mois en français
                        $currentDate = str_replace(
                            array('January', 'February', 'March', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                            array('janvier', 'février', 'mars', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'),
                            $currentDate
                        );

                        $menu = $menuInfo[0];

                        // Si la date du menu est supérieure ou égale à la date actuelle, c'est une date future
                        if ($date >= $currentDate) {
                ?>
                        <div class="swiper-slide">
                            

                            <?php if ($menu['Foods'] == null) { ?>
                                <p><?php echo $date ?></p>
                                <p>Pas de menu aujourd'hui</p>
                            <?php } else { ?>
                                <ul>
                                    <?php foreach ($menu['Foods'] as $food) { ?>
                                        <li><?php echo $food ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                        </div>
                <?php
                        }
                    }
                ?>

                </div>

                <div class="btn_content-menu btn_next">
                    <p>Suivant</p>
                    <i class="fi fi-br-angle-right"></i>
                </div>
                <div class="btn_content-menu btn_prev">
                    <i class="fi fi-br-angle-left"></i>
                    <p>Précédent</p>
                </div>

            </div>
        </div>

    </main>

    <script src="../assets/js/menu-navigation.js?v=1.1"></script> 
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_menu-header');
        select_background_profil.classList.add('select_link-header');

        // Swiper
        let swiper = new Swiper(".mySwiper", {
            navigation: {
                nextEl: ".btn_next",
                prevEl: ".btn_prev",
            },
        });
    </script>

</body>


</html>
