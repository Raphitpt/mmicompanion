<?php
session_start();
require '../bootstrap.php';

$nextCours = nextCours('BUT2-TP3');

// si submit
if (isset($_POST['submit'])) {
    sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "cmqgfxf7Df_aJvJEVc2XB3:APA91bHoEOb8ucJfBURLDtMX9RI4Zwajab0Cf_NpUFxHQMD-bnhNA5BeV7q9Ko8FDctzED69YwkX49ofUinel-VRuPut5v8MyM-GXp8IZ9IT2_ixWcfeS5HdSqiU38yH3G32O2UxB1FY");
    
}
echo sendNotification("Vous avez un cours dans 10 minutes !", "10 minutes", "BUT2-TP2");
echo getMenu();
echo head('Accueil');
?>
<main>
<div>
    <h1>Accueil</h1>
    <p>Bienvenue sur l'application de gestion des ressources de l'IUT de Lens.</p>
    <p>Vous pouvez consulter les ressources disponibles dans le menu de gauche.</p>
    <p>Vous pouvez également consulter les prochains cours dans le tableau ci-dessous.</p>
    <table>
        <thead>
            <tr>
                <th>Intitulé</th>
                <th>Enseignant</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Salle</th>
                <th>Temps restant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $nextCours['summary'] ?></td>
                <td><?= $nextCours['description'] ?></td>
                <td id="tmstpCours"><?= $nextCours['dtstart_tz'] ?></td>
                <td><?= $nextCours['debut'] ?> - <?= $nextCours['fin'] ?></td>
                <td><?= $nextCours['location'] ?></td>
                <td id="tempsBefore">0</td>
            </tr>
        </tbody>
    </table>
    <form>
        <button type="submit">send notification</button>
    </form>
</div>
<div id="demoControls">
      <label for="badgeVal">Badge value</label>
      <input type="number" id="badgeVal" placeholder="<number>" min="0" value="42" disabled />
      <br>
      <button id="butSet" type="button" disabled>
        Set
      </button>
      <button id="butClear" type="button" disabled>
        Clear
      </button>
    </div>
</main>
</body>
<script>
    let otVersion = false;
const butSet = document.getElementById('butSet');
const butClear = document.getElementById('butClear');
const inputBadgeVal = document.getElementById('badgeVal');


// Check if the API is supported.
if ('setExperimentalAppBadge' in navigator) {
  isSupported('v2')
}

// Check if the previous API surface is supported.
if ('ExperimentalBadge' in window) {
  isSupported('v1');
}

// Check if the previous API surface is supported.
if ('setAppBadge' in navigator) {
  isSupported('v3');
}

// Update the UI to indicate whether the API is supported.
function isSupported(kind) {
  console.log('supported', kind);
  const divNotSupported = document.getElementById('notSupported');
  divNotSupported.classList.toggle('hidden', true);
  butSet.removeAttribute('disabled');
  butClear.removeAttribute('disabled');
  inputBadgeVal.removeAttribute('disabled');  
  
  butMakeXHR.removeAttribute('disabled');
}

// Click event handler for Set button.
butSet.addEventListener('click', () => {
  const val = parseInt(inputBadgeVal.value, 10);
  if (isNaN(val)) {
    setBadge();
    return;
  }
  setBadge(val);
});

// Click event handler for Clear button.
butClear.addEventListener('click', () => {
  clearBadge();
});

// Wrapper to support first and second origin trial
// See https://web.dev/badging-api/ for details.
function setBadge(...args) {
  if (navigator.setAppBadge) {
    navigator.setAppBadge(...args);
  } else if (navigator.setExperimentalAppBadge) {
    navigator.setExperimentalAppBadge(...args);
  } else if (window.ExperimentalBadge) {
    window.ExperimentalBadge.set(...args);
  }
}

// Wrapper to support first and second origin trial
// See https://web.dev/badging-api/ for details.
function clearBadge() {
  if (navigator.clearAppBadge) {
    navigator.clearAppBadge();
  } else if (navigator.clearExperimentalAppBadge) {
    navigator.clearExperimentalAppBadge();
  } else if (window.ExperimentalBadge) {
    window.ExperimentalBadge.clear();
  }
}
</script>
<script>
const tmstpCours = document.getElementById('tmstpCours').innerHTML;
const tempsBefore = document.getElementById('tempsBefore');
function tempsRestant(x){
    y = x.replace(/(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})/, '$1-$2-$3T$4:$5:$6Z');
    let now = new Date();
    let dateCours = new Date(y);
    let diff = dateCours - now;
    let diffSec = diff / 1000;
    let diffMin = diffSec / 60;
    let diffHeure = diffMin / 60;
    let diffJour = diffHeure / 24;
    tempsBefore.innerHTML = "";
    tempsBefore.innerHTML = Math.floor(diffJour) + ' jours ' + Math.floor(diffHeure % 24) + ' heures ' + Math.floor(diffMin % 60) + ' minutes ' + Math.floor(diffSec % 60) + ' secondes';
}
setInterval(function () {
    tempsRestant(tmstpCours);
}, 1000);
</script>