<?php
require('bootstrap.php');

// Définissez un tableau associatif avec les liens iCal pour chaque groupe
$sql_but = "SELECT * FROM mmi_but";
$stmt_but = $dbh->prepare($sql_but);
$stmt_but->execute();
$but_data = $stmt_but->fetchAll(PDO::FETCH_ASSOC);

$backupDir = __DIR__ . '/backup_cal/';

foreach ($but_data as $but) {
    $group = $but['but_nom'];
    $edt_link_s1 = $but['edt_link_s1'];
    $edt_link_s2 = $but['edt_link_s2'];

    // Téléchargez le contenu des liens EDT
    $icalDataS1 = file_get_contents($edt_link_s1);
    $icalDataS2 = file_get_contents($edt_link_s2);

    // Vérifiez si le téléchargement a réussi
    if ($icalDataS1 !== false && $icalDataS2 !== false) {
        // Supprimez les en-têtes et pieds du deuxième fichier iCal
        $icalDataS1 = preg_replace('/END:VCALENDAR/', '', $icalDataS1);
        $icalDataS2 = preg_replace('/BEGIN:VCALENDAR[\s\S]+?CALSCALE:GREGORIAN/', '', $icalDataS2);

        // Combinez les données iCal
        $combinedData = $icalDataS1 . $icalDataS2;

        // Ajouter le commentaire "prof absent" aux événements concernés
        $combinedData = addProfAbsentComment($combinedData);

        // Enregistrez les données combinées et mises à jour dans un fichier
        $backupFileName = $group . '.ics';
        file_put_contents($backupDir . $backupFileName, $combinedData);

        echo "Sauvegarde réussie pour le groupe $group à " . date('Y-m-d H:i:s') . "\n";
    } else {
        echo "Erreur lors du téléchargement du fichier iCal pour le groupe $group\n";
    }
}

/**
 * Ajoute le commentaire "prof absent" aux événements de Carole Couegnas entre le 27 mai 2024 et le 7 juin 2024.
 *
 * @param string $icalData Les données iCal combinées.
 * @return string Les données iCal mises à jour.
 */
function addProfAbsentComment($icalData)
{
    $events = explode("BEGIN:VEVENT", $icalData);
    $updatedEvents = [];
    $start_date = strtotime('2024-05-27');
    $end_date = strtotime('2024-06-07');

    foreach ($events as $event) {
        if (strpos($event, "COUEGNAS CAROLE") !== false) {
            // Extraire la date de début de l'événement
            preg_match('/DTSTART:(\d+T\d+Z)/', $event, $matches);
            if ($matches) {
                $eventDate = strtotime($matches[1]);
                if ($eventDate >= $start_date && $eventDate <= $end_date) {
                    // Ajouter le commentaire "prof absent" avant END:VEVENT
                    $event = str_replace("END:VEVENT", "ORGANIZER:Prof absent\nEND:VEVENT", $event);
                }
            }
        }
        $updatedEvents[] = $event;
    }

    // Recombine les événements mis à jour
    return implode("BEGIN:VEVENT", $updatedEvents);
}
