<?php
session_start();
require "../bootstrap.php";

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
}

// La on récupère le cookie que l'on à crée à la connection
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

// On récupère le lien de l'emploi du temps de l'utilisateur via la base de données
$cal_link = calendar($user_sql['edu_group']);

$color_subjects = "SELECT * FROM sch_ressource";
$stmt = $dbh->prepare($color_subjects);
$stmt->execute();
$color_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Obligatoire pour afficher la page
echo head("MMI Companion | Emploi du temps");

?>
<style>
    #calendar{
        font-size: 13px !important;
    }
    .fc-v-event{
        font-size: 0.58rem !important;
    }

</style>
<body class="body-all">
    <!-- Menu de navigation -->
    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header" id="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Calendrier</h1>
            </div>
        </div>

        <?php generateBurgerMenuContent() ?>
    </header>

    <main class="main-outils">
        <div style="height:30px"></div>
        <div id="calendar">


        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/build/ical.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion et affichage de l'emploi du temps en utilisant FullCalendar
            const url1 = 'https://corsproxy.io/?' + encodeURIComponent('<?php echo $cal_link ?>');
            let calendarEl = document.querySelector("#calendar");
            let eventColors = {

                <?php
                foreach ($color_subjects as $color_subject) {
                    echo "'" . $color_subject['code_ressource'] . "': '" . $color_subject['color_ressource'] . "',";
                }
                ?>
            };
            let calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                slotMinTime: '08:00',
                slotMaxTime: '18:30',
                hiddenDays: [0, 6],
                allDaySlot: false,
                eventMinHeight: 75,
                height: 'calc(100vh - 160px)',
                nowIndicator: true,
                initialView: 'timeGridFourDay',
                views: {
                    timeGridFourDay: {
                        type: 'timeGrid',
                        dayCount: 3
                    }
                },
                headerToolbar: {
                    left: "prev",
                    center: "title",
                    right: "today next",
                },
                // plugins: [DayGridPlugin, iCalendarPlugin],
                events: {
                    url: url1,
                    format: "ics",
                },
                eventContent: function(arg) {

                    let eventLocation = arg.event.extendedProps.location;
                    let eventDescription = arg.event.extendedProps.description;
                    let eventDescriptionModifie = eventDescription.replace(/\([^)]*\)/g, '');
                    let test = eventDescriptionModifie.replace(/(CM|TDA|TDB|TP1|TP2|TP3|TP4) /g, '$1<br>');
                    let eventContent = '<div class="fc-title">' + arg.event.title + '</div>';

                    if (eventDescription) {
                        eventContent += '<div class="fc-description">' + test + '</div>';
                    }

                    if (eventLocation) {
                        eventContent += '<div class="fc-location">' + eventLocation + '</div>';
                    }

                    return {
                        html: eventContent
                    };
                },
                eventDidMount: function(arg) {
                    let eventTitle = arg.event.title;
                    let eventColor = null;

                    // Recherchez une correspondance partielle entre le titre de l'événement et les clés de l'objet eventColors
                    for (let key in eventColors) {
                        if (eventTitle.includes(key)) {
                            eventColor = eventColors[key];
                            break; // Sortez de la boucle dès qu'une correspondance est trouvée
                        }
                    }

                    if (eventColor) {
                        arg.el.style.backgroundColor = eventColor;
                    }
                }
            });

            calendar.render();
        });
    </script>
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_index-header');
        select_background_profil.classList.add('select_link-header');
    </script>
</body>

</html>