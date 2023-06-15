<?php
session_start();
require '../bootstrap.php';

if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
    exit();
}
calendar($_SESSION['user']['edu_group']);
$cal_link = calendar($_SESSION['user']['edu_group']);

echo head('Index');
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Bienvenue <?php var_dump($_SESSION['user']); ?></h1>
                <a href="./logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div id="calendar"></div>
</body>
<script>
        let calendar = new tui.Calendar('#calendar', {
        defaultView: 'week',
        taskView: true,
        template: {
            monthDayname: function(dayname) {
                return '<span class="calendar-week-dayname-name">' + dayname.label + '</span>';
            }
        }
    });

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '<?php echo $cal_link ?>', true); // Remplacez 'URL_VERS_VOTRE_FICHIER_ICS' par le lien correct vers votre fichier .ics
    xhr.onload = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var icsData = xhr.responseText;

            // Analyser les événements du fichier .ics
            var jcalData = ICAL.parse(icsData);
            var comp = new ICAL.Component(jcalData);
            var veventList = comp.getAllProperties('vevent');

            // Ajouter les événements au calendrier
            var schedules = [];
            for (var i = 0; i < veventList.length; i++) {
                var vevent = veventList[i];
                var summary = vevent.getFirstPropertyValue('summary');
                var startDate = vevent.getFirstPropertyValue('dtstart').toJSDate();
                var endDate = vevent.getFirstPropertyValue('dtend').toJSDate();

                schedules.push({
                    calendarId: '1',
                    id: String(i + 1),
                    title: summary,
                    start: startDate,
                    end: endDate
                });
            }

            calendar.clear(); // Effacer les événements existants
            calendar.createSchedules(schedules); // Ajouter les nouveaux événements
        }
    };
    xhr.send();
</script>
<script src="https://cdn.jsdelivr.net/npm/ics@3.2.0/dist/index.min.js"></script>
</script>
