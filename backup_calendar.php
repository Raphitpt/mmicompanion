<?php
// Définissez un tableau associatif avec les liens iCal pour chaque groupe
$ical_links = [
    "BUT1-TP1" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21314&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT1-TP2" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21315&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT1-TP3" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=21470&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT1-TP4" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24826&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT2-TP1" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24827&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT2-TP2" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24834&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT2-TP3" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24835&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT2-TP4" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24836&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT3-TP1" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2465&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT3-TP2" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2454&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT3-TP3" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2452&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
    "BUT3-TP4" => 'https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=2451&projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01',
];

// Vérifiez si $group existe dans le tableau $ical_links
if (array_key_exists($group, $ical_links)) {
    $calendar_link = $ical_links[$group];
    $backupFileName = 'ical_backup_' . $group . '_' . date('Y-m-d_H-i-s') . '.ics';
    
    // Répertoire de sauvegarde
    $backupDir = './backup_cal/';

    // Téléchargez le fichier iCal et effectuez la sauvegarde
    $icalData = file_get_contents($calendar_link);
    
    if ($icalData !== false) {
        // Sauvegarder le fichier iCal
        file_put_contents($backupDir . $backupFileName, $icalData);
        echo "Sauvegarde réussie à " . date('Y-m-d H:i:s') . "\n";
    } else {
        echo "Erreur lors du téléchargement du fichier iCal\n";
    }
} else {
    echo "Groupe non reconnu : $group\n";
}
?>
