<?php
session_start();
require '../bootstrap.php';

$nextCours = nextCours('BUT2-TP3');

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
</div>
</main>
</body>
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