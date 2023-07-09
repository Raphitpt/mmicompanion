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
$cal_link = calendar($users['edu_group']); ?>

<?php echo head('MMI Companion | Accueil');

if (isset($_GET['submit'])) {
  $message = $_GET['message'];
  $title = $_GET['title'];
  $group = "";
  sendNotification($message, $title, $group);
  exit();
}

?>


<body class="body-index">
  <header class="header-index">
    <div class="content_header-index">

      <div class="burger-header-index" id="burger-header">
        <i class="fi fi-br-bars-sort"></i>
      </div>

      <div class="content-header-index">
        <div class="content_title-header-index">
          <h1>Salut <span style="font-weight:800">
              <?php echo "Arnaud" ?><span></h1>
          <p>en ligne</p>
        </div>
        <div style="width:10px"></div>
        <a href="./profil.php">
          <div class="content_img-header-index">
            <img src="./../assets/img/profil-1.svg" alt="Photo de profil">
            <div></div>
          </div>
        </a>
      </div>
    </div>

    <div class="burger_content-header" id="burger_content-header">
      <div style="height:60px"></div>
      <div class="burger_content_title-header">
        <img src="./../assets/img/mmicompanion.svg" alt="">
        <h1>MMI Companion</h1>
      </div>
      <div class="burger_content_content-header">
        <div class="burger_content_trait_header"></div>
        <a href="./index.php">
          <div class="burger_content_link-header">
            <i class="fi fi-br-home"></i>
            <p>Vue d'ensemble</p>
            <div class="select_link-header"></div>
          </div>
        </a>
        <div class="burger_content_link-header" onclick="getDataFromFile('./test_agenda.php')">
          <i class="fi fi-br-calendar"></i>
          <p>Agenda</p>
        </div>
        <div class="burger_content_trait_header"></div>
        <a href="./messages.php">
          <div class="burger_content_link-header">
            <i class="fi fi-br-comment-alt"></i>
            <p>Messages</p>
          </div>
        </a>
        <a href="./mail.php">
          <div class="burger_content_link-header">
            <i class="fi fi-br-envelope"></i>
            <p>Boite mail</p>
          </div>
        </a>
        <div class="burger_content_trait_header"></div>
        <a href="./sante.php">
          <div class="burger_content_link-header">
            <i class="fi fi-br-doctor"></i>
            <p>Mon bien être</p>
          </div>
        </a>
        <a href="./profil.php">
          <div class="burger_content_link-header">
            <i class="fi fi-br-user"></i>
            <p>Mon profil</p>
          </div>
        </a>
      </div>
    </div>
  </header>
  
  <main class="container main-index">
    <div style="height:30px"></div>
    <div class="title_trait">
      <h1>L'emploi du temps</h1>
      <div></div>
    </div>
    <div style="height:15px"></div>
    <div id="calendar"></div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/build/ical.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script>
  <script src="../assets/js/menu-navigation.js"></script>
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
      const url1 = 'https://corsproxy.io/?' + encodeURIComponent('https://calendar.google.com/calendar/ical/rtiphonet%40gmail.com/private-5a957604340233123df1415b08b46c24/basic.ics');
      let calendarEl = document.getElementById("calendar");
      var eventColors = {};
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
        height: '70vh',
        initialView: "timeGridDay",
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

          let eventContent = '<div class="fc-title">' + arg.event.title + '</div>';

          if (eventDescription) {
            eventContent += '<div class="fc-description">' + eventDescription + '</div>';
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
          let eventColor = eventColors[eventTitle];

          if (eventColor) {
            arg.el.style.backgroundColor = eventColor;
          }
        }
      });

      // Fonction pour générer une couleur aléatoire
      function generateRandomColor() {
        let letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      }
      calendar.getEvents().forEach(function(event) {
        let eventTitle = event.title;

        if (!eventColors[eventTitle]) {
          eventColors[eventTitle] = generateRandomColor();
        }
      });

      calendar.render();
    });
  </script>
</body>

</html>