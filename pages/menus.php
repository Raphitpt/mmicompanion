<?php
require './../bootstrap.php';
$user = onConnect($dbh);
$menu_url = 'https://www.crous-poitiers.fr/restaurant/r-u-crousty/'; // URL du menu

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $menu_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$menu_html = curl_exec($ch);
curl_close($ch);

// Créez un DOMDocument et chargez le contenu HTML de la page en désactivant la vérification DTD.
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($menu_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_use_internal_errors(false);

// Utilisez XPath pour extraire les sections avec la classe "menu".
$xpath = new DOMXPath($dom);
$menus = $xpath->query("//div[contains(@class, 'menu')]");
// Créez un tableau pour stocker les données des menus par jour.
$menuDataByDay = [];

// Parcourez les sections de menu.
foreach ($menus as $menu) {
    // Créez un tableau pour stocker les données d'un menu.
    $menuInfo = [];

    // Utilisez XPath pour extraire les informations spécifiques du menu.
    $date = $xpath->query(".//time[@class='menu_date_title']", $menu)->item(0)->textContent;
    $menuInfo['Date'] = trim($date);

    // Créez un tableau pour stocker les plats du menu.
    $menuInfo['Foods'] = [];

    // Utilisez XPath pour extraire les plats du menu.
    $foods = $xpath->query(".//ul[contains(@class, 'meal_foodies')]//li", $menu);

    // Parcourez les éléments de la liste des plats.
    foreach ($foods as $food) {
        $menuInfo['Foods'][] = trim($food->textContent);
    }
    unset($menuInfo['Foods'][0]);
    unset($menuInfo['Foods'][1]);
    unset($menuInfo['Foods'][2]);


    // Utilisez la date comme clé pour stocker les données par date.
    $menuDataByDay[$menuInfo['Date']][] = $menuInfo;
}

$html = "";
foreach ($menuDataByDay as $date => $menuInfo) {
    if ($date == array_key_first($menuDataByDay)) {
        $html .= "<div class='meal active'>";
    } else {
        $html .= "<div class='meal'>";
    }
    $html .= "<h2>$date</h2>";
    foreach ($menuInfo as $menu) {
        $html .= "<ul>";
        foreach ($menu['Foods'] as $food) {
            $html .= "<li>$food</li>";
        }
        $html .= "</ul></div>";
    }
}
echo json_encode($html);

