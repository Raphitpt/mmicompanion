<!-- Fichier calendar.php qui gère tout, ne pas cassez SVP 😂 -->
<?php
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);


unset($_SESSION['mail_message']);
// La on récupère le cookie que l'on à crée à la connection, voir login.php et fonction.php
// --------------------

setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie


// Récupèration des données de l'utilisateur directement en base de données et non pas dans le cookie, ce qui permet d'avoir les données à jour sans deconnection
$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
  'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);

// On récupère le lien de l'emploi du temps de l'utilisateur via la base de données
$cal_link = calendar($user_sql['edu_group']);

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
  header('Location: ./calendar.php');
  exit();
}




$color_subjects = "SELECT * FROM sch_ressource";
$stmt = $dbh->prepare($color_subjects);
$stmt->execute();
$color_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);



echo head('MMI Companion | Emploi du temps');
?>

<!-- Mise en place du tutoriel -->
<?php
if ($user_sql['edu_group'] == 'undefined' || $user_sql['edu_group'] == '') { ?>

  <body class="body-welcome">
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
                <label for="annee">En quelle année es-tu ?</label>
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
                <span style="font-weight:700">MMI Companion</span> est maintenant installée sur ta téléphone ! Tu peux y accéder plus simplement et rapidement.
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
              <i class="fi fi-br-bell"></i>
              <h1>Active les notifications</h1>
            </div>
            <p>Une fois que tu auras installé l'application sur ton téléphone, tu pourras activer les notifications depuis la page de ton profil pour ne louper aucune information.</p>
            <div id="push-permission" class="button_notifications-profil"></div>
            <div class="trait_content_welcome-index"></div>
            <div style="height:2px"></div>
            <div id="button_page3-validate" class="button_welcome-index">Valider</div>
          </div>

        </section>

        <section class="welcome_page4-index">
          <div class="back_btn" id="button_page4-back">
            <i class="fi fi-br-arrow-alt-right"></i>
          </div>
          <div class="title_welcome_page2-index">
            <div class="title_content_welcome_page2-index">
              <h1>Bienvenue sur MMI Companion</h1>
            </div>
            <p>Je te laisse découvrir l’application et nous restons disponible pour répondre à tes questions à cette adresse mail : <span style="font-weight:700"><a href="mailto:contact@mmi-companion.fr">contact@mmi-companion.fr</a></span></p>
          </div>
          <div class="trait_content_welcome-index"></div>
          <input type="submit" id="button_page4-validate" class="button_welcome-index" value="C'est parti !">
        </section>
      </form>


    </main>

  </body>

  <script src="../assets/js/app.js"></script>
  <script>
    const button_page1_validate = document.querySelector('#button_page1-validate');
    const button_page2_validate = document.querySelector('#button_page2-validate');
    const button_page3_validate = document.querySelector('#button_page3-validate');
    const button_page4_validate = document.querySelector('#button_page4-validate');
    const button_page2_back = document.querySelector('#button_page2-back');
    const button_page3_back = document.querySelector('#button_page3-back');
    const button_page4_back = document.querySelector('#button_page4-back');
    const welcome_page1 = document.querySelector('.welcome_page1-index');
    const welcome_page2 = document.querySelector('.welcome_page2-index');
    const welcome_page3 = document.querySelector('.welcome_page3-index');
    const welcome_page4 = document.querySelector('.welcome_page4-index');

    button_page4_validate.addEventListener('click', () => {
      location.reload();
    })
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

    button_page3_validate.addEventListener('click', () => {
      welcome_page3.style.display = 'none';
      welcome_page4.style.display = 'flex';
    })

    button_page3_back.addEventListener('click', () => {
      welcome_page2.style.display = 'flex';
      welcome_page3.style.display = 'none';
    })

    button_page4_back.addEventListener('click', () => {
      welcome_page3.style.display = 'flex';
      welcome_page4.style.display = 'none';
    })
  </script>
<?php } else {

?>

  <body class="body-all">

    <!-- <header class="header-calendar">
      <div class="content_header-calendar">

        <div class="burger-header-calendar" id="burger-header">
          <i class="fi fi-br-bars-sort"></i>
        </div>

        <div class="content-header-calendar">
          <div class="content_title-header-calendar">
            <h1>Salut <span style="font-weight:800">
                <?php //echo ucfirst($user['pname']) 
                ?><span></h1>
            <p>en ligne</p>
          </div>
          <div style="width:10px"></div>
          <a href="./profil.php">
            <div class="content_img-header-calendar">
              <div class="rounded-img">
                <img src="<?php //echo $user_sql['pp_link'] 
                          ?>" alt="Photo de profil">
              </div>
              <div class="green_circle"></div>
            </div>
          </a>
        </div>
      </div>

      <?php generateBurgerMenuContent($user_sql['role']) ?>

    </header> -->

    <header>
      <div class="content_header">
        <div class="content_title-header">
          <div class="burger-header" id="burger-header">
            <i class="fi fi-br-bars-sort"></i>
          </div>
          <div style="width:20px"></div>
          <h1>Emploi du temps</h1>
        </div>
      </div>
      <?php generateBurgerMenuContent($user_sql['role']) ?>

    </header>

    <div style="height:15px"></div>

    <main class="main-calendar">
      <?php if ($user_sql['role'] == "prof") { ?>
        <div class="welcome_title-calendar_prof">
          <p>Bienvenue <span style="font-weight:900"><?php echo strtoupper(substr($user['pname'], 0, 1)) . "." . ucfirst($user['name']) ?></span> sur votre espace professeur</p>
          <img src="./../assets/img/hello_emoji.webp" alt="">
        </div>
        <div style="height:15px"></div>
      <?php } ?>
      <p class="last_backup_cal">Dernière sauvegarde: <?php echo date("d F Y H:i:s", filemtime($cal_link)) ?></p>
      <section class="section_calendar-calendar">
        <div class="container_calendar-calendar">

          <div id="calendar"></div>
        </div>
      </section>
    </main>

  </body>

  <script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/build/ical.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script>
  <!-- <script src="../assets/js/swipeCalendar.js"></script> -->
  <script src="../assets/js/menu-navigation.js"></script>
  <script src="../assets/js/app.js"></script>
  <script>
    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_calendar-header');
    select_background_profil.classList.add('select_link-header');

    // -----------------------
    let url1 = './../backup_cal/<?php echo $user_sql['edu_group'] ?>.ics';


    if ("<?php echo $user_sql['role'] ?>" === "autre") {
      url1 = './custom_cal.php';
    }

    document.addEventListener("DOMContentLoaded", function() {
      // Gestion et affichage de l'emploi du temps en utilisant FullCalendar
      let eventSources = [{
        url: url1
      }];

      if (url1 === './custom_cal.php') {
        eventSources[0].format = 'json';
      } else {
        eventSources[0].format = 'ics';
      }

      eventSources.push({
        url: './calendar_event.php'
      });


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
        weekNumbers: true,
        views: {
          timeGridWeek: {
            type: 'timeGrid',
            weekends: false,
            slotLabelInterval: '1:00:00',
          },
          timeGridDay: {
            type: 'timeGrid',
            dayCount: 1,
            weekends: false,
          }
        },
        eventClick: function(info) {
          if (info.event.source.url === './calendar_event.php') {
            window.location.href = './calendar_edit.php?id_event=' + info.event.id;
          }
        },
        allDaySlot: false,
        eventMinHeight: 30,
        height: 'calc(98vh - 95px)',
        nowIndicator: true,
        initialView: "timeGridDay",
        footerToolbar: {
          left: "custom1day",
          center: "addEvent",
          right: "custom3day",
        },
        headerToolbar: {
          left: "customPrevious",
          center: "title",
          right: "today customNext",
        },
        customButtons: {
          custom3day: {
            text: '5 jours',
            click: function() {
              calendar.changeView('timeGridWeek');
              document.querySelectorAll('.fc-v-event').forEach(function(eventEl) {
                eventEl.style.fontSize = '0.8rem !important';
              });
            }
          },
          addEvent: {
            text: '+',
            click: function() {
              let dateOriginale = calendar.getDate();
              const annee = dateOriginale.getFullYear();
              const mois = (dateOriginale.getMonth() + 1).toString().padStart(2, '0');
              const jour = dateOriginale.getDate().toString().padStart(2, '0');
              const dateFormatee = `${annee}-${mois}-${jour}`;
              window.location.href = './calendar_add.php?date=' + dateFormatee;
            }
          },
          custom1day: {
            text: '1 jour',
            click: function() {
              calendar.changeView('timeGridDay');
              document.querySelectorAll('.fc-location').forEach(function(eventEl) {
                eventEl.style.fontSize = '1.5rem !important';
              });
            }
          },
          customNext: {
            icon: 'chevron-right',
            click: function() {
              if (calendar.view.type === 'timeGridWeek') {
                let daysToAdvance = 7;
                calendar.incrementDate({
                  days: daysToAdvance
                });
              } else {
                let daysToAdvance = 1;

                calendar.incrementDate({
                  days: daysToAdvance
                });
                let date = calendar.getDate();
                console.log(date);
              }
            }
          },
          customPrevious: {
            icon: 'chevron-left',
            click: function() {
              if (calendar.view.type === 'timeGridWeek') {
                let daysToGoBack = -7; // Revenir à la vue de jour (-7 jours)

                calendar.incrementDate({
                  days: daysToGoBack
                });
              } else if (calendar.view.type === 'timeGridDay') {
                const currentDate = calendar.getDate();
                const currentDayOfWeek = currentDate.getDay();
                let daysToGoBack;

                if (currentDayOfWeek === 0) {
                  daysToGoBack = 2;
                } else if (currentDayOfWeek === 1) {
                  daysToGoBack = 3;
                } else {
                  daysToGoBack = 1;
                }

                calendar.prev(daysToGoBack);
              }
            }
          },
        },
        // slotEventOverlap: false,
        // plugins: [DayGridPlugin, iCalendarPlugin],
        eventSources: eventSources,
        eventContent: function(arg) {
          let eventTitle = arg.event.title;
          let eventLocation = arg.event.extendedProps.location;
          let eventDescription = arg.event.extendedProps.description;
          let eventHour = arg.timeText;
          let eventDescriptionModifie = eventDescription.replace(/\([^)]*\)/g, '');
          let test = eventDescriptionModifie.replace(/(CM|TDA|TDB|TP1|TP2|TP3|TP4) /g, '$1<br>');
          let eventContent = "";
          // Accédez aux dates de début et de fin de l'événement via arg.event
          let startDate = new Date(arg.event.start);
          let endDate = new Date(arg.event.end);
          // Calculez la différence en millisecondes entre les dates de début et de fin
          let duration = endDate - startDate;
          // Convertissez la durée en heures et minutes
          let hours = Math.floor(duration / 3600000); // 1 heure = 3600000 millisecondes
          let minutes = Math.floor((duration % 3600000) / 60000); // 1 minute = 60000 millisecondes
          // Si la durée est entre 30 minutes et 1 heure, affichez un contenu personnalisé

          if (eventTitle && calendar.view.type === 'timeGridWeek') {
            eventContent += '<div class="fc-title" style="font-size:0.52rem">' + eventTitle + '</div>';
          } else if (eventTitle && calendar.view.type === 'timeGridDay') {
            eventContent += '<div class="fc-title" style="font-size:0.8rem">' + eventTitle + '</div>';
          }



          if (eventDescription && calendar.view.type === 'timeGridDay') {
            eventContent += '<div class="fc-description" style="font-size:0.8rem">' + test + '</div>';
          }

          if (eventLocation && calendar.view.type === 'timeGridWeek') {
            eventContent += '<div class="fc-location" style="font-size:0.52rem">' + eventLocation + '</div>';
          } else if (eventLocation && calendar.view.type === 'timeGridDay') {
            eventContent += '<div class="fc-location" style="font-size:0.8rem">' + eventLocation + '</div>';
          }
          if (eventHour && calendar.view.type === 'timeGridDay') {
            eventContent += '<div class="fc-time" style="font-size:0.8rem">' + eventHour + '</div>';
          }
          if (duration >= 1800000 && duration <= 4500000) {
            if (eventLocation && calendar.view.type === 'timeGridWeek') {
              eventContent = '<div class="fc-description" style="font-size:0.52rem">'+ eventTitle +' - ' + eventLocation +'</div>';
          } else if (eventLocation && calendar.view.type === 'timeGridDay') {
            eventContent = '<div class="fc-description" style="font-size:0.8rem">'+ eventTitle +' - '+ test +' - ' + eventLocation + ' - ' + eventHour + '</div>';
          }}
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
        },

      });
      <?php if (isset($_SESSION['date'])) { ?>
        let sessionDate = <?php echo json_encode($_SESSION['date']); ?>;
        calendar.gotoDate(sessionDate);
      <?php
        unset($_SESSION['date']);
      } ?>
      calendar.render();
      let touchStartX = 0;
      let touchEndX = 0;
      let swipeThreshold = 50; // Seuil pour considérer un geste comme un glissement

      // Événement de toucher initial
      calendarEl.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
      });

      // Événement de fin de toucher
      calendarEl.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].clientX;
        let swipeDistance = touchEndX - touchStartX;

        // Vérifiez si le geste était un glissement vers la gauche (jour suivant)
        if (swipeDistance > swipeThreshold) {
          calendar.prev();
        }
        // Vérifiez si le geste était un glissement vers la droite (jour précédent)
        else if (swipeDistance < -swipeThreshold) {
          calendar.next();
        }
      });
    });
  </script>

<?php
}
?>

</html>