<?php
// Définissez un tableau associatif avec les liens iCal pour chaque groupe
$ical_links = [
    "HMEH" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=44956",
    "MBA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=10376",
    "SBA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=4299",
    "CBA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=35076",
    "BCA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&code=36705",
    "YCAL" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01code=42712",
    "BCH" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=9996",
    "CCO" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=16308",
    "JBB" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=35615",
    "FDA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=8605",
    "EDO" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=32193",
    "SHE" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=39354",
    "EGL" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=37783",
    "DGA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=2211",
    "SDE" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=10322",
    "OGI" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=41085",
    "CGOU" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=9460",
    "JLH" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=20322",
    "YHE" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=21310",
    "CFO" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=38945",
    "FLT" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=2217",
    "MBUI" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=12946",
    "CVAL" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=12909",
    "SSCU" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=10377",
    "MCOMB" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=45444",
    "HSUL" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=45435",
    "JCHA" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=45752",
    "MPOY" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=45761",
    "JBRU" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=46131",
    "FCAU" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=99999992",
    "AHAU" => "https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=14&calType=ical&firstDate=2000-01-01&lastDate=2038-01-01&&code=99999995"
];


$backupDir = './backup_cal/';

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
