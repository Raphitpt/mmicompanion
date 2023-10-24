<?php
require './../bootstrap.php'; // Incluez le fichier autoload de la bibliothèque Sabre\VObject

// Récupérez les paramètres "start" et "end" de la requête GET
$start = isset($_GET['start']) ? $_GET['start'] : null;
$end = isset($_GET['end']) ? $_GET['end'] : null;

if ($start !== null && $end !== null) {
    // Convertissez les paramètres "start" et "end" en objets DateTime
    $startDateTime = new DateTime($start);
    $endDateTime = new DateTime($end);

    // Chemin du fichier VCS
    $vcsFile = './../other_cal/vcs_combined.vcs';

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
                    
                        // Ajoutez 2 heures à l'heure de début et de fin
                        $eventStart = $eventStart->add(new DateInterval('PT2H'));
                        $eventEnd = $eventEnd->add(new DateInterval('PT2H'));
                    
                        // Accédez à la description
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
                            $startPos = strpos($description, 'Spe:') + 4; // Trouver la position de "Spe:" et ajouter 4 pour passer les deux points et l'espace
                            $endPos = strpos($description, '\\', $startPos); // Trouver la position de la barre oblique après "Spe:"
                            $spePart = substr($description, $startPos, $endPos - $startPos);
                    
                            $formattedEvent = [
                                'title' => $spePart,
                                'start' => $eventStart->format('Y-m-d\TH:i:s'),
                                'end' => $eventEnd->format('Y-m-d\TH:i:s'),
                                'description'=> '',
                                'location' => $location,
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
