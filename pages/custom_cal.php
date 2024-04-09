<?php
require './../bootstrap.php'; // Incluez le fichier autoload de la bibliothèque Sabre\VObject

// Récupérez les paramètres "start" et "end" de la requête GET
$start = isset($_GET['start']) ? $_GET['start'] : null;
$end = isset($_GET['end']) ? $_GET['end'] : null;

date_default_timezone_set('Europe/Paris');

if ($start !== null && $end !== null) {
    // Convertissez les paramètres "start" et "end" en objets DateTime
    $startDateTime = new DateTime($start);
    $endDateTime = new DateTime($end);

    // Chemin du fichier VCS
    $vcsFile = './../other_cal/vcs_combined.vcs';
    function findColorCode($matiereName)
    {
        $matieres = [
            "ALLEMAND1" => "#0097CF", // Orange
            "ANGLAIS2" => "#00CF61", // Vert clair
            "AOPS2" => "#A2D100", // Bleu violet
            "AUTRE" => "#1989E1", // Cyan
            "CALCMATHS" => "#A261FF", // Magenta
            "COM-EXP1" => "#F3A0FF", // Jaune
            "ESPAGNOL1" => "#55A696", // Rose
            "INFO1" => "#557AA6", // Cyan clair
            "PORTFOLS1" => "#A69A55", // Vert
            "PPP1" => "#A6556C", // Orange
            "PROJET" => "#97A655", // Vert citron
            "Vide" => "#8555A6", // Gris foncé
            "ECO-GENE" => "#E7B9D4", // Orange
            "GEO-ECH" => "#B9DEE7", // Vert clair
            "Int-Droit" => "#978A29", // Bleu violet
            "SAE-1-1" => "#E7B9D0", // Cyan clair
            "T-Routier" => "#5AA544", // Magenta
            "ENT-PFL" => "#D0CD0D", // Jaune
            "LOG-GL" => "#630DD0", // Vert clair
            "SAE-1-2" => "#6FCE9C", // Vert citron
            "AP-Proj" => "#CEB66F", // Orange
            "CO-GENE" => "#CE6F87", // Vert clair
            "ORSE" => "#0097CF", // Bleu violet
            "SAE-1-3" => "#00CF61", // Cyan clair
            "ECO-TRANSP" => "#5AA544",
            "CCT" => "#1989E1",
        ];
        $search_term = $matiereName;
        foreach ($matieres as $matiere => $colorCode) {
            if (stripos($matiere, $search_term) !== false) {
                return $colorCode;
            }
        }
        return "#000000"; // Couleur par défaut
    };
    function findProf($profName)
    {
        $prof = [
            "GARRI002" => "GARRIGOU",
            "PERRI001" => "PERRIERE",
            "PERER001" => "PEREIRA",
            "GARDE001" => "GARDERE",
            "KAHN001" => "KHAN",
            "RABUS001" => "RABUSSIER",
            "DUNET001" => "DUNET",
            "BLANC001" => "BLANC",
            "ROYGI001" => "ROY",
            "SARRA002" => "SARRAUTE",
            "BENEJ001" => "BENEJAT",
            "LELONG01" => "LELONG",
            "CRONI001" => "CRONIER",
            "GOREC001" => "GORECKI",
            "GONZA001" => "GONZALVEZ",
            "RAVON001" => "RAVON",
            "KLAJA" => "KLAJA",
            "BARIO001" => "BARIOULET",
            "ZERGU001" => "ZERGUINI",
            "DIAKI001" => "DIAKITE"
        ];
        $search_term = $profName;
        foreach ($prof as $profCode => $profName) {
            if (stripos($profCode, $search_term) !== false) {
                return $profName;
            }
        }
        return "";
    };
    if (file_exists($vcsFile)) {
        $vcsContent = file_get_contents($vcsFile);

        if ($vcsContent !== false) {
            // Essayez de parser le contenu VCS en utilisant Sabre\VObject
            try {
                $vcalendar = Sabre\VObject\Reader::read($vcsContent);

                if ($vcalendar->VEVENT) {
                    // Convertissez les événements en un tableau JSON
                    $events = [];
                    foreach ($vcalendar->VEVENT as $event) {
                        $eventStart = $event->DTSTART->getDateTime();
                        $eventEnd = $event->DTEND->getDateTime();
                        var_dump($eventStart);
                        var_dump($eventEnd);
                        // Ajoutez 2 heures à l'heure de début et de fin
                        $eventStart = $eventStart->add(new DateInterval('PT1H'));
                        $eventEnd = $eventEnd->add(new DateInterval('PT1H'));

                        // Gestion du changement d'heure (heure d'été / heure d'hiver)
                        if (date('I', $eventStart->getTimestamp())) {
                            $eventStart->modify('+1 hour');
                            $eventEnd->modify('+1 hour');
                        }

                        $descriptionObject = $event->DESCRIPTION;

                        // Vérifiez si l'événement est dans la plage de dates spécifiée
                        if ($eventStart >= $startDateTime && $eventEnd <= $endDateTime) {
                            // Accédez aux objets FlatText pour title et location
                            $titleObject = $event->SUMMARY;
                            $locationObject = $event->LOCATION;

                            // Obtenez les valeurs à partir des objets FlatText
                            $title = $titleObject->getValue();
                            $location = $locationObject->getValue();

                            // Extraire la partie de la description entre les ":" après "Spe:" et "\"
                            $description = $descriptionObject->getValue();

                            $startPos = stripos($description, 'Spe:') + 4; // Utilisez stripos pour rechercher insensible à la casse
                            $endPos = strpos($description, '\\', $startPos); // Trouver la position de la barre oblique après "Spe:"
                            $spePart = substr($description, $startPos, $endPos - $startPos);

                            $startProfPos = stripos($description, 'Prof:') + 5; // Utilisez stripos pour rechercher insensible à la casse
                            $endProfPos = strpos($description, ' Spe:', $startProfPos); // Trouver la position de la barre oblique après "Prof:"
                            $profPart = substr($description, $startProfPos, $endProfPos - $startProfPos);

                            $descriptionParts = explode("/", $title);
                            $firstPart = trim($descriptionParts[0]); // Obtenez la première partie et supprimez les espaces éventuels

                            $colorCode = findColorCode($firstPart);
                            $prof = findProf($profPart);


                            $formattedEvent = [
                                'title' => $spePart,
                                'start' => $eventStart->format('Y-m-d\TH:i:s'),
                                'end' => $eventEnd->format('Y-m-d\TH:i:s'),
                                'description' => $prof,
                                'location' => $location,
                                'color' => $colorCode,
                            ];
                            $events[] = $formattedEvent;
                        }
                    }

                    echo json_encode($events);
                } else {
                    // Aucun événement trouvé
                    echo json_encode(['error' => 'Aucun événement trouvé dans la plage de dates spécifiée.']);
                }
            } catch (Exception $e) {
                // Gérez les erreurs lors de la lecture du fichier VCS
                echo json_encode(['error' => 'Erreur lors de la lecture du fichier VCS.']);
            }
        } else {
            // Gérez les erreurs de récupération du contenu VCS
            echo json_encode(['error' => 'Impossible de récupérer le contenu du fichier VCS.']);
        }
    } else {
        // Gérez l'absence du fichier VCS
        echo json_encode(['error' => 'Le fichier VCS n\'existe pas.']);
    }
} else {
    // Gérez l'absence des paramètres "start" et "end"
    echo json_encode(['error' => 'Les paramètres "start" et "end" sont manquants dans la requête GET.']);
}
