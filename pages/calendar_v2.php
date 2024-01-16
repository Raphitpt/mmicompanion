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
    <main class="main_all">
        <div class="menu_content-menu">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper" id="calendarContainer">
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

            // Charger le fichier ICS de manière asynchrone
            fetch('./../backup_cal/BUT2-TP3.ics')
                .then(response => response.text())
                .then(icsData => {
                    // Analyser les données ICS
                    const data = ical.parseICS(icsData);

                    // Créer un tableau de jours de septembre à juillet (en excluant les week-ends)
                    const startDate = new Date(Date.UTC(new Date().getUTCFullYear() - 1, 8, 1));
                    const endDate = new Date(Date.UTC(new Date().getUTCFullYear(), 6, 31));

                    // Convertir les dates au fuseau horaire de Paris (heure d'Europe centrale)
                    startDate.setUTCHours(startDate.getUTCHours() + 2);
                    endDate.setUTCHours(endDate.getUTCHours() + 2);

                    const allDays = getDates(startDate, endDate);

                    // Stocker les événements par date
                    const eventsByDate = {};
                    for (let k in data) {
                        if (data.hasOwnProperty(k)) {
                            var event = data[k];
                            if (data[k].type === 'VEVENT') {
                                // Convertir la date au format ISO (YYYY-MM-DD)
                                const dateKey = `${event.start.getFullYear()}-${padZeroes(event.start.getMonth() + 1)}-${padZeroes(event.start.getDate())}`;

                                function padZeroes(value) {
                                    return value.toString().padStart(2, '0');
                                }
                                console.log(dateKey)
                                if (!eventsByDate[dateKey]) {
                                    eventsByDate[dateKey] = [];
                                }
                                eventsByDate[dateKey].push(event);
                            }
                        }
                    }
                    // Fri Sep 01 2023 00:00:00 GMT+0200 (heure d’été d’Europe centrale)
                    // Fri Dec 01 2023 08:00:00 GMT+0100 (heure normale d’Europe centrale)

                    // Créer les slides
                    const eventsContainer = document.getElementById('calendarContainer');
                    allDays.forEach(day => {
                        // Convertir la date au format ISO (YYYY-MM-DD)
                        const dateKey = day.toISOString().split('T')[0];
                        const eventsForDate = eventsByDate[dateKey] || [];
                        // console.log(eventsByDate);

                        // Trier les événements par ordre chronologique
                        eventsForDate.sort((a, b) => new Date(a.start) - new Date(b.start));

                        const slideElement = document.createElement('div');
                        slideElement.classList.add('swiper-slide');

                        // Afficher la date correspondant à la slide
                        const slideDateString = `${day.getDate()} ${months[day.getMonth()]} ${day.getFullYear()}`;
                        const dateHeader = document.createElement('h2');
                        dateHeader.textContent = slideDateString;
                        slideElement.appendChild(dateHeader);

                        eventsForDate.forEach(event => {
                            const eventElement = document.createElement('div');
                            // Utiliser directement les dates
                            eventElement.innerHTML = `
            <p>Début : ${event.start}</p>
            <p>Fin : ${event.end}</p>
            <p>Lieu : ${event.location}</p>
            <p>Description : ${event.description}</p>
            <hr>
        `;
                            slideElement.appendChild(eventElement);
                        });

                        eventsContainer.appendChild(slideElement);
                    });

                    // Initialiser Swiper
                    let swiper = new Swiper(".mySwiper", {
                        slidesPerView: 1,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: ".btn_next",
                            prevEl: ".btn_prev",
                        },
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement ou de l\'analyse du fichier ICS', error);
                });
        });

        function getDates(startDate, endDate) {
            const dates = [];
            let currentDate = new Date(Date.UTC(startDate.getUTCFullYear(), startDate.getUTCMonth(), startDate.getUTCDate()));
            endDate = new Date(Date.UTC(endDate.getUTCFullYear(), endDate.getUTCMonth(), endDate.getUTCDate()));

            while (currentDate <= endDate) {
                // Exclure les samedis (6) et dimanches (0)
                if (currentDate.getUTCDay() !== 0 && currentDate.getUTCDay() !== 6) {
                    dates.push(new Date(currentDate));
                }
                currentDate.setUTCDate(currentDate.getUTCDate() + 1);
            }
            return dates;
        }
    </script>
</body>

</html>

</html>