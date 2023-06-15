<?php
/*
    Fichier : /Helpers/functions.php
 */

/**
 * Retourne le contenu HTML du bloc d'en tête d'une page.
 * Deux CSS sont automatiquement intégré :
 *   - pico.css
 *   - custom.css
 *
 * @param string title le titre de la page.
 * @return string
 */
function head(string $title = ''): string
{
    return  <<<HTML_HEAD
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="manifest" href="../manifest.webmanifest" />
  <title>$title</title>
  <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
</head>


HTML_HEAD;
}


/**
 * Retourne vrai si la méthode d'appel est GET.
 */

function isGetMethod(): bool
{
    return  ($_SERVER['REQUEST_METHOD'] === 'GET') ;
}

/**
 * Retourne vrai si la méthode d'appel est POST.
 */
function isPostMethod(): bool
{
    return  ($_SERVER['REQUEST_METHOD'] === 'POST') ;
}

function calendar($group = ''){
    $calendar_link = '';
    if($group == "BUT1-TP1"){
        $calendar_link ='https://upplanning.appli.univ-poitiers.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=24838&projectId=13&calType=ical&nbWeeks=15';
    }
    if($group == "BUT1-TP2"){
        $calendar_link ='';
    }
    if($group == "BUT1-TP3"){
        $calendar_link ='';
    }
    if($group == "BUT1-TP4"){
        $calendar_link ='';
    }
    if($group == "BUT2-TP1"){
        $calendar_link ='';
    }
    if($group == "BUT2-TP2"){
        $calendar_link ='';
    }
    if($group == "BUT2-TP3"){
        $calendar_link ='';
    }
    if($group == "BUT2-TP4"){
        $calendar_link ='';
    }
    if($group == "BUT3-TP1"){
        $calendar_link ='';
    }
    if($group == "BUT3-TP2"){
        $calendar_link ='';
    }
    if($group == "BUT3-TP3"){
        $calendar_link ='';
    }
    if($group == "BUT3-TP4"){
        $calendar_link ='';
    }
    return $calendar_link;
}
