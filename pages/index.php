<?php
session_start();
require '../bootstrap.php';

if (!isset($_COOKIE['jwt'])) {
  header('Location: ./accueil.php');
  exit;
}
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$user = decodeJWT($jwt, $secret_key);
$cal_link = calendar($user['edu_group']);

if (isset($_GET['submit'])) {
  $message = $_GET['message'];
  $title = $_GET['title'];
  $group = "";
  sendNotification($message, $title, $group);
  exit();
}

$user_data = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_data);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);


echo head('MMI Companion | Accueil');
?>


<body class="body-index">
  <?php 
    if ($user_data['edu_group'] == 'indefined') { ?>
      <main class="main-welcome">
        <form action="" method="post" class="form-welcome">
          <div class="welcome_page1-index">
              <div class="title_welcome_page1-index">
                <div class="title_content_welcome_page1-index">
                  <h1>Bonjour <?php echo $user['pname'] ?></h1>
                  <img src="./../assets/img/hello_emoji.png" alt="Emoji d'une main qui fait bonjour">
                </div>
                <p>Bienvenue sur MMI Companion</p>
              </div>
              <div class="trait_title_welcome-index"></div>
              <div class="content_welcome_page1-index">
                <p>Pour commencer, nous avons besoin de quelques informations en plus :</p>
                <div class="content_welcome_questions_page1-index">
                  <div class="content_welcome_questions_content_page1-index">
                    <label for="annee">En quelle année rentres-tu ?</label>
                    <select name="annee" id="annee">
                      <option value="BUT1">BUT 1</option>
                      <option value="BUT2">BUT 2</option>
                      <option value="BUT3">BUT 3</option>
                    </select>
                  </div>
                  <div class="content_welcome_questions_content_page1-index">
                    <label for="tp">Quel est ton TP ?</label>
                    <select name="tp" id="tp">
                      <option value="TP1">TP1</option>
                      <option value="TP2">TP2</option>
                      <option value="TP3">TP3</option>
                      <option value="TP4">TP4</option>
                    </select>
                  </div>
                </div>
                <div class="trait_content_welcome-index"></div>
                <button id="button_page1" class="button_welcome_page1-index">Valider</button>
              </div>
          </div>
        </form>


      </main>
    <?php } 
    
    else {
  ?>


  <header class="header-index">
    <div class="content_header-index">

      <div class="burger-header-index" id="burger-header">
        <i class="fi fi-br-bars-sort"></i>
      </div>

      <div class="content-header-index">
        <div class="content_title-header-index">
          <h1>Salut <span style="font-weight:800">
              <?php echo ucfirst($user['pname']) ?><span></h1>
          <p>en ligne</p>
        </div>
        <div style="width:10px"></div>
        <a href="./profil.php">
          <div class="content_img-header-index">
            <div class="rounded-img">
            <img src="<?php echo $user_data['pp_link'] ?>" alt="Photo de profil">
          </div>
            <div class="green_circle"></div>
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
        <a href="./agenda.php">
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
        <div class="burger_content_trait_header"></div>
          <a href="./logout.php">
            <div class="burger_content_link-header logout-header">
                <i class="fi fi-br-delete-user"></i>
                <p>Se déconnecter</p>
            </div>
          </a>
      </div>
    </div>
  </header>

  <main class="main-index">
    <div style="height:30px"></div>
    <section class="section_calendar-index">
      <div class="title_trait">
        <h1>L'emploi du temps</h1>
        <div></div>
      </div>
      <div style="height:15px"></div>
      <div id="calendar"></div>
    </section>

    <div style="height:30px"></div>

    <section class="section_agenda-index">
        <div class="title_trait">
          <h1>L'agenda</h1>
          <div></div>
        </div>

        
    </section>
    
  </main>

</body>

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
        nowIndicator: true,
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
  <?php 
    }
  ?>
</html>
