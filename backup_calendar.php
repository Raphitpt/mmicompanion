<?php
require ('./bootstrap.php');

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

        // Enregistrez les données combinées dans un fichier
        $backupFileName = $group . '.ics';
        file_put_contents($backupDir . $backupFileName, $combinedData);

        echo "Sauvegarde réussie pour le groupe $group à " . date('Y-m-d H:i:s') . "\n";
    } else {
        echo "Erreur lors du téléchargement du fichier iCal pour le groupe $group\n";
    }
}
?>