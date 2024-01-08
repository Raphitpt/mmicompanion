<?php
function downloadMenuFile($menu_url, $destination_path)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $menu_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $menu_html = curl_exec($ch);
    curl_close($ch);

    // Sauvegarde le contenu téléchargé dans un fichier.
    file_put_contents($destination_path, $menu_html);
}

// Exemple d'utilisation
$menu_url = 'https://www.crous-poitiers.fr/restaurant/r-u-crousty/';
$destination_path = './backup_cal/menu.html';
downloadMenuFile($menu_url, $destination_path);
?>