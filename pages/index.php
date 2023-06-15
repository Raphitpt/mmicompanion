<?php
session_start();
require '../bootstrap.php';

if (!isset($_SESSION['user'])) {
  header('Location: ./login.php');
  exit();
}

$cal_link = calendar($_SESSION['user']['edu_group']);

echo head('Index');
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
        <h1>Bienvenue <?php var_dump($_SESSION['user']); ?></h1>
        <a href="./logout.php">Logout</a>
      </div>
    </div>
  </div>
  <div id="calendar"></div>
</body>

<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/common@5.11.5/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/ical.js@1.5.0/build/ical.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const url1 = 'https://corsproxy.io/?' + encodeURIComponent('<?php echo $cal_link; ?>');
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'fr',
      initialView: "timeGridDay",
      headerToolbar: {
        left: "prev, today",
        center: "title",
        right: "next",
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