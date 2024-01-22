<?php
require('bootstrap.php');

// Définissez un tableau associatif avec les liens iCal pour chaque groupe
$sql_prof = "SELECT trigramme, edt_link FROM personnels";
$stmt_prof = $dbh->prepare($sql_prof);
$stmt_prof->execute();
$profs_data = $stmt_prof->fetchAll(PDO::FETCH_ASSOC);

$ical_links = [];

foreach ($profs_data as $prof) {
    $ical_links[$prof['trigramme']] = $prof['edt_link'];
}

$backupDir = __DIR__ . '/backup_cal/';

foreach ($ical_links as $group => $calendar_link) {
    $icalData = file_get_contents($calendar_link);

    if ($icalData !== false) {
        $backupFileName = $group . '.ics';
        file_put_contents($backupDir . $backupFileName, $icalData);
        echo "Sauvegarde réussie pour le groupe $group à " . date('Y-m-d H:i:s') . "\n";
    } else {
        echo "Erreur lors du téléchargement du fichier iCal pour le groupe $group\n";
    }
}
