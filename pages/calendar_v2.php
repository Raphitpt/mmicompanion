<?php
session_start();
require "../bootstrap.php";

use ICal\ICal;

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
    <?php //generateBurgerMenuContent($user_sql['role'], 'Emploi du temps', notifsHistory($dbh, $user['id_user'], $user['edu_group'])); 
    ?>
    <main class="main_all">
        <div class="menu_content-menu">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php
                    $ical = new ICal('./../backup_cal/BUT2-TP3.ics');
                    $events = $ical->events();
                    usort($events, function ($a, $b) {
                        return strtotime($a->dtstart) - strtotime($b->dtstart);
                    });


                    // Générer la liste des jours de l'année scolaire (du 1er septembre au 31 juillet)
                    $currentDate = new DateTime('first day of September ' . date('Y') - 1);
                    $endOfYear = new DateTime('last day of July ' . date('Y'));

                    $daysOfWeek = []; // Initialisez $daysOfWeek en tant qu'array vide

                    while ($currentDate <= $endOfYear) {
                        // Exclure les samedis (6) et dimanches (0)
                        if ($currentDate->format('N') != 6 && $currentDate->format('N') != 7) {
                            $date = $currentDate->format('Y-m-d');
                            echo '<div class="swiper-slide';

                            // Ajouter la classe today si la date est celle d'aujourd'hui
                            if ($currentDate->format('Y-m-d') == date('Y-m-d')) {
                                echo ' today';
                            }

                            echo '">';
                            echo "<h2>{$date}</h2>";

                            $eventsForDay = array_filter($events, function ($event) use ($date) {
                                return date('Y-m-d', strtotime($event->dtstart)) === $date;
                            });

                            if (!empty($eventsForDay)) {
                                foreach ($eventsForDay as $event) {
                                    echo "<div>";
                                    echo $event->summary;
                                    echo $event->location;
                                    echo $event->description;

                                    // Conversion de la date et de l'heure
                                    $startDateTime = new DateTime($event->dtstart);
                                    $formattedStartTime = $startDateTime->format('Ymd\THis\Z');
                                    echo $formattedStartTime;

                                    $endDateTime = new DateTime($event->dtend);
                                    $formattedEndTime = $endDateTime->format('Ymd\THis\Z');
                                    echo $formattedEndTime;

                                    echo "</div>";
                                    // Ajouter la date au tableau $daysOfWeek
                                    $daysOfWeek[] = $date;
                                }
                            } else {
                                echo "<p>Aucun événement pour cette journée.</p>";
                            }

                            echo '</div>';
                        }



                        $currentDate->modify('+1 day');
                    }
                    // Utiliser count() seulement si $daysOfWeek est un tableau
                    if (is_array($daysOfWeek)) {
                        echo 'Nombre de jours de la semaine : ' . count($daysOfWeek);
                    } else {
                        echo 'Le tableau $daysOfWeek n\'est pas défini ou n\'est pas un tableau.';
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
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
                slidesPerView: 1, // Nombre de diapositives à afficher simultanément
                spaceBetween: 10,
                navigation: {
                    nextEl: ".btn_next",
                    prevEl: ".btn_prev",
                },
                on: {
                    init: function() {
                        checkSlideCount();
                    },
                    slidesLength: function() {
                        checkSlideCount();
                    },

                }
            });
            swiper.slideTo(getSlideIndexByClass('today'),0, false);

            function getSlideIndexByClass(className) {
                let slides = document.querySelectorAll('.swiper-wrapper .swiper-slide');
                for (let i = 0; i < slides.length; i++) {
                    if (slides[i].classList.contains(className)) {
                        return i;
                    }
                }
                return 0;
            }
    </script>
</body>

</html>