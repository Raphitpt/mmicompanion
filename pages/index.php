<?php
session_start();
require '../bootstrap.php';

if (!isset($_COOKIE['jwt'])) {
  header('Location: ./accueil.php');
  exit;
}
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
$cal_link = calendar($users['edu_group']);

echo head('Index');
if(isset($_GET['submit'])){
  $message = $_GET['message'];
  $title = $_GET['title'];
  $group = "";
  sendNotification($message, $title, $group);
  exit();
}
?>
<style>
  #calendar {
    max-width: 900px;
    margin: 40px auto;
  }
</style>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Bienvenue <?php var_dump($users); ?></h1>
        <a href="./logout.php">Logout</a>
        <p id="btn"></p>
        <form method="GET">
          <input type="text" name="message" placeholder="Message de la notif">
          <input type="text" name="title" placeholder="Titre de la notif">
          <input type="submit" name="submit" value="Ajouter">
        </form>
        <!-- <button id="sendNotificationButton">Envoyer une notification</button> -->
      </div>
    </div>
  </div>
  <div id="calendar"></div>
</body>

<!-- <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/common@5.11.5/main.min.css" rel="stylesheet" /> -->
<script src="
https://unpkg.com/ical.js@1.5.0/build/ical.js
"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script> -->
<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
<script src="../assets/js/app.js"></script>
<script>
  let jwt = localStorage.getItem('jwt');

  if (!jwt) {
    // Rediriger vers la page de connexion si le JWT est manquant
    window.location.href = './login.php';
  } else {


    // Exemple d'utilisation de la bibliothèque jQuery pour la requête AJAX
    $.ajax({
      url: '../assets/php/validate_token.php',
      method: 'POST',
      data: {
        jwt: jwt
      },
      success: function(response) {
        // Le JWT est valide, vous pouvez permettre l'accès à la page
      },
      error: function() {
        // Le JWT est invalide ou a expiré, rediriger vers la page de connexion
        window.location.href = './login.php';
      }
    });
  }



  document.addEventListener("DOMContentLoaded", function() {
    const url1 = 'https://corsproxy.io/?' + encodeURIComponent('<?php echo $cal_link; ?>');

    let calendar = new tui.Calendar('#calendar', {
        defaultView: 'day',
         // e.g. true, false, or ['allday', 'time']
        day:{
          workweek: true,
          hourStart: 8,
          hourEnd: 18,

        },
        week: {
    taskView: false,
    scheduleView: ['time'],
  },
        template: {
            monthDayname: function(dayname) {
                return '<span class="calendar-week-dayname-name">' + dayname.label + '</span>';
            }
        }
    });

    let xhr = new XMLHttpRequest();
    xhr.open('GET', url1, true); // Remplacez 'URL_VERS_VOTRE_FICHIER_ICS' par le lien correct vers votre fichier .ics
    xhr.onload = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let icsData = xhr.responseText;

            // Analyser les événements du fichier .ics
            let jcalData = ICAL.parse(icsData);
            let comp = new ICAL.Component(jcalData);
            let veventList = comp.getAllSubcomponents('vevent');
            
            // Ajouter les événements au calendrier
            let schedules = [];
            for (let i = 0; i < veventList.length; i++) {
                let vevent = veventList[i];
                let summary = vevent.getFirstPropertyValue('summary');
                let startDate = vevent.getFirstPropertyValue('dtstart').toJSDate();
                let endDate = vevent.getFirstPropertyValue('dtend').toJSDate();
              
                schedules.push({
                    calendarId: '1',
                    id: String(i + 1),
                    title: summary,
                    start: startDate,
                    end: endDate
                });
            }

            calendar.clear(); // Effacer les événements existants
            calendar.createEvents(schedules); // Ajouter les nouveaux événements
        }
    };
    xhr.send();
    
  });
</script>
