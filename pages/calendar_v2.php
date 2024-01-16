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
    <?php generateBurgerMenuContent($user_sql['role'], 'Agenda', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <main class="main_all">
        <div class="menu_content-menu">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper agenda" id="calendarContainer">
                    <!-- Calendar events will be dynamically added here -->
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
    <script src="./../assets/js/ical.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

            fetch('./../backup_cal/BUT2-TP3.ics')
                .then(response => response.text())
                .then(icsData => {
                    const data = ical.parseICS(icsData);

                    const startDate = new Date(Date.UTC(new Date().getUTCFullYear() - 1, 8, 1));
                    const endDate = new Date(Date.UTC(new Date().getUTCFullYear(), 6, 31));
                    startDate.setUTCHours(startDate.getUTCHours() + 2);
                    endDate.setUTCHours(endDate.getUTCHours() + 2);

                    const allDays = getDates(startDate, endDate);
                    const eventsByDate = {};

                    for (let k in data) {
                        if (data.hasOwnProperty(k)) {
                            var event = data[k];
                            if (data[k].type === 'VEVENT') {
                                const dateKey = `${event.start.getFullYear()}-${padZeroes(event.start.getMonth() + 1)}-${padZeroes(event.start.getDate())}`;

                                function padZeroes(value) {
                                    return value.toString().padStart(2, '0');
                                }

                                if (!eventsByDate[dateKey]) {
                                    eventsByDate[dateKey] = [];
                                }
                                eventsByDate[dateKey].push(event);
                            }
                        }
                    }

                    const eventsContainer = document.getElementById('calendarContainer');
                    let todayIndex = -1; // Index de la slide correspondant à la date actuelle

                    allDays.forEach((day, index) => {
                        const dateKey = day.toISOString().split('T')[0];
                        const eventsForDate = eventsByDate[dateKey] || [];
                        eventsForDate.sort((a, b) => new Date(a.start) - new Date(b.start));

                        const slideElement = document.createElement('div');
                        slideElement.classList.add('swiper-slide');

                        const slideDateString = `${day.getDate()} ${months[day.getMonth()]} ${day.getFullYear()}`;

                        const dateHeader = document.createElement('div');
                        dateHeader.classList.add('item_title_content-agenda');
                        const bookMark = document.createElement('div');
                        bookMark.classList.add('fi');
                        bookMark.classList.add('fi-br-bookmark');
                        dateHeader.appendChild(bookMark);
                        const dateHeaderP = document.createElement('p');
                        dateHeaderP.textContent = slideDateString;
                        dateHeader.appendChild(dateHeaderP);
                        slideElement.appendChild(dateHeader);

                        eventsForDate.forEach(event => {
                            const eventElement = document.createElement('div');
                            eventElement.classList.add('container_list_content-agenda');
                            let eventDescriptionModifie = event.description.replace(/\([^)]*\)/g, '');
                            let test = eventDescriptionModifie.replace(/(CM|TDA|TDB|TP1|TP2|TP3|TP4) /g, '$1</p><br><p>');
                            eventElement.innerHTML = `
                        <p>Début : ${event.start.getHours()}:${padZeroes(event.start.getMinutes())}</p>
                        <p>Fin : ${event.end.getHours()}:${padZeroes(event.end.getMinutes())}</p>
                        <p>Lieu : ${event.location}</p>
                        <p>Description : ${test}</p>
                        <hr>
                    `;
                            slideElement.appendChild(eventElement);
                        });

                        eventsContainer.appendChild(slideElement);

                        // Comparer la date actuelle avec la date de la slide
                        if (isToday(day)) {
                            todayIndex = index;
                            slideElement.classList.add('today');
                        }
                    });

                    let swiper = new Swiper(".mySwiper", {
                        slidesPerView: 1,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: ".btn_next",
                            prevEl: ".btn_prev",
                        },
                        speed: 500,
                        grabCursor: true,
                        autoHeight: true,
                        initialSlide: todayIndex, // Aller à la slide correspondant à la date actuelle
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement ou de l\'analyse du fichier ICS', error);
                });
        });

        function getDates(startDate, endDate) {
            const dates = [];
            let currentDate = new Date(Date.UTC(startDate.getUTCFullYear(), startDate.getUTCMonth(), startDate.getUTCDate()));
            endDate = new Date(Date.UTC(endDate.getUTCFullYear(), endDate.getUTCMonth(), endDate.getUTCMonth()));

            while (currentDate <= endDate) {
                if (currentDate.getUTCDay() !== 0 && currentDate.getUTCDay() !== 6) {
                    dates.push(new Date(currentDate));
                }
                currentDate.setUTCDate(currentDate.getUTCDate() + 1);
            }
            return dates;
        }

        function isToday(someDate) {
            const today = new Date();
            return someDate.getDate() === today.getDate() &&
                someDate.getMonth() === today.getMonth() &&
                someDate.getFullYear() === today.getFullYear();
        }
    </script>
</body>

</html>

</html>