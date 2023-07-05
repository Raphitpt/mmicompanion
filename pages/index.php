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
    font-family: 'Montserrat', sans-serif;
    width: 50vw;
    height: 100%;
  }
  .fc-timegrid-slot {
    height: 30px !important
}

</style>

<body>
<header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Agenda</h1>
            </div>
        </div>

        <div class="burger_content-header">
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
                <a href="./todolist.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-calendar"></i>
                        <p>Agenda</p>
                    </div>
                </a>
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

<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/common@5.11.5/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/build/ical.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script>

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

    let calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'fr',
      eventMinHeight: 75,
      height: 'auto',
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
      slotMinTime: '08:00',
      slotMaxTime: '18:30',
      hiddenDays: [0, 6],
      allDaySlot: false,
    });

    calendar.render();
  });
</script>
