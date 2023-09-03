<!-- Fichier index.php qui gère tout, ne pas cassez SVP 😂 -->
<?php
session_start();
require '../bootstrap.php';

// si le cookie n'existe pas, on redirige vers la page d'accueil
if (!isset($_COOKIE['jwt'])) {
  header('Location: ./accueil.php');
  exit;
}

// La on récupère le cookie que l'on à crée à la connection, voir login.php et fonction.php
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie

// On récupère les données de l'utilisateur pour l'EDT
$cal_link = calendar($user['edu_group']);

// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_data = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_data);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);


// On récupère les données du formulaire du tutoriel pour ajouter l'année et le tp de l'utilisateur à la base de données
if (isset($_POST['annee']) && isset($_POST['tp'])) {
  $annee = $_POST['annee'];
  $tp = $_POST['tp'];
  $update_user = "UPDATE users SET edu_group = :edu_group WHERE id_user = :id_user";
  $stmt = $dbh->prepare($update_user);
  $stmt->execute([
    'edu_group' => $annee . "-" . $tp,
    'id_user' => $user['id_user']
  ]);
  header('Location: ./index.php');
  exit();
}

$color_subjects = "SELECT * FROM sch_ressource";
$stmt = $dbh->prepare($color_subjects);
$stmt->execute();
$color_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo head('MMI Companion | Accueil');
?>


<body class="body-all">

  <!-- Mise en place du tutoriel -->
  <?php
  if ($user_data['edu_group'] == 'undefined' || $user_data['edu_group'] == '') { ?>
    <main class="main-welcome">
      <form action="" method="post" class="form-welcome">
        <section class="welcome_page1-index">
          <a href="./logout.php" class="back_btn">
            <i class="fi fi-br-arrow-alt-right"></i>
          </a>
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
            <div id="button_page1-validate" class="button_welcome-index">Valider</div>
          </div>
        </section>

        <section class="welcome_page2-index">
          <div class="back_btn" id="button_page2-back">
            <i class="fi fi-br-arrow-alt-right"></i>
          </div>
          <div class="title_welcome_page2-index">
            <div class="title_content_welcome_page2-index">
              <i class="fi fi-br-download"></i>
              <h1>Installe MMI Companion</h1>
            </div>
            <p>Pour ton information, il est possible d’ajouter MMI Companion sur ta page d’accueil comme une vraie application.</p>
          </div>
          <div class="content_welcome_page2-index">
            <ul>
              <li>
                <span style="font-weight:700">Étape 1 : </span><br />
                Dans ton navigateur web, clique sur l’icône avec les 3 petits points.
              </li>
              <li>
                <span style="font-weight:700">Étape 2 : </span><br />
                Clique sur « Ajouter à l’écran d’accueil » ou « Installer l'application ».
              </li>
              <li>
                <span style="font-weight:700">MMI Companion</span> est maintenant installée sur ta page d'accueil ! Tu peux y accéder plus simplement et rapidement.
              </li>
            </ul>
          </div>
          <div class="trait_content_welcome-index"></div>
          <div id="button_page2-validate" class="button_welcome-index">Valider</div>
        </section>

        <section class="welcome_page3-index">
          <div class="back_btn" id="button_page3-back">
            <i class="fi fi-br-arrow-alt-right"></i>
          </div>
          <div class="title_welcome_page2-index">
            <div class="title_content_welcome_page2-index">
              <h1>Bienvenue sur MMI Companion</h1>
            </div>
            <p>Je te laisse découvrir l’application et nous restons disponible pour répondre à tes questions à cette adresse mail : <span style="font-weight:700">arnaud.graciet@etu.univ-poitiers.fr</span></p>
          </div>
          <div class="trait_content_welcome-index"></div>
          <input type="submit" id="button_page3-validate" class="button_welcome-index" value="C'est parti !">
        </section>
      </form>


    </main>

</body>

<script>
  const button_page1_validate = document.querySelector('#button_page1-validate');
  const button_page2_validate = document.querySelector('#button_page2-validate');
  const button_page2_back = document.querySelector('#button_page2-back');
  const button_page3_back = document.querySelector('#button_page3-back');
  const welcome_page1 = document.querySelector('.welcome_page1-index');
  const welcome_page2 = document.querySelector('.welcome_page2-index');
  const welcome_page3 = document.querySelector('.welcome_page3-index');

  button_page1_validate.addEventListener('click', () => {
    welcome_page1.style.display = 'none';
    welcome_page2.style.display = 'flex';
  })

  button_page2_validate.addEventListener('click', () => {
    welcome_page2.style.display = 'none';
    welcome_page3.style.display = 'flex';
  })

  button_page2_back.addEventListener('click', () => {
    welcome_page1.style.display = 'flex';
    welcome_page2.style.display = 'none';
  })

  button_page3_back.addEventListener('click', () => {
    welcome_page2.style.display = 'flex';
    welcome_page3.style.display = 'none';
  })
</script>
<?php } else {
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

    <?php generateBurgerMenuContent() ?>

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
        <div>
          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $activation_code = generate_activation_code();
            send_activation_email($_POST['mail'], $activation_code);
          }
          ?>
          <form method="post">
            <input type="text" name="mail">
            <input type="submit" value="Envoyer" name="submit">
          </form>
        </div>
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
    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_index-header');
    select_background_profil.classList.add('select_link-header');

    document.addEventListener("DOMContentLoaded", function() {
      // Gestion et affichage de l'emploi du temps en utilisant FullCalendar
      const url1 = 'https://corsproxy.io/?' + encodeURIComponent('<?php echo $cal_link ?>');
      let calendarEl = document.getElementById("calendar");
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
<?php
  }
?>

</html>