<?php
session_start();
require './../bootstrap.php';

$user = onConnect($dbh);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
$user_sql = userSQL($dbh, $user);

$additionalStyles = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />';

echo head('MMI Companion | Menu du Crousty', $additionalStyles);

?>

<body class="body-all">
    <!-- Menu de navigation -->
    <?php generateBurgerMenuContent($user_sql['role'], 'Menu du Crousty', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <main class="main_all">
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
                    if (empty(getMenu("./../backup_cal/menu.html"))==true) { ?>

                        <div class="swiper-slide item_menu_content-menu">
                            <p style='font-weight:600'>Le menu est indisponible</p>
                         </div>
                    
                    <?php } else{
                    // Récupération du menu
                    $menuDataByDay = getMenu("./../backup_cal/menu.html");

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

                        // Convertir les dates en timestamp
                        $timestampDate = strtotime($date);
                        $timestampCurrentDate = strtotime($currentDate);

                        // Si la date du menu est supérieure ou égale à la date actuelle, c'est une date future
                        if ($timestampDate >= $timestampCurrentDate) {
                ?>
                        <div class="swiper-slide item_menu_content-menu">
                            <?php 
                            if ($menu['Foods'] == null) { ?>
                                <p><?php echo $date ?></p>
                                <p>Pas de menu aujourd'hui</p>
                            <?php } else { ?>
                                <p><?php echo $date ?></p>
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

    <script src="../assets/js/script_all.js?v=1.1"></script> 
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>

        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_menu-header');
        select_background_profil.classList.add('select_link-header');

        // Faire en sorte de supprimer le marginBotom quand il n'y a qu'une slide
        function checkSlideCount() {
            const swiper = document.querySelector('.mySwiper').swiper;

            if (swiper.slides.length <= 1) {
                document.querySelector('.swiper-wrapper').style.marginBottom = "auto";
            } else {
                document.querySelector('.swiper-wrapper').style.marginBottom = "4rem";
            }
        }


        // Swiper
        let swiper = new Swiper(".mySwiper", {
            autoHeight: true,
            spaceBetween: 30,
            navigation: {
                nextEl: ".btn_next",
                prevEl: ".btn_prev",
            },
            on: {
                init: function () {
                    checkSlideCount(); // Vérifiez le nombre de slides lors de l'initialisation
                },
                slidesLength: function () {
                    checkSlideCount(); // Vérifiez le nombre de slides lorsqu'il change
                }
            }
        });




    </script>

</body>


</html>
