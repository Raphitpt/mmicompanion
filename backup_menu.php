<?php
function downloadMenuFile($menu_url, $destination_path)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $menu_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $menu_html = curl_exec($ch);

    if ($menu_html === false) {
        $error_message = 'Erreur lors du téléchargement du fichier: ' . curl_error($ch);
        curl_close($ch);
        trigger_error($error_message, E_USER_ERROR);
        return;
    }

    curl_close($ch);

    // Sauvegarde le contenu téléchargé dans un fichier.
    $write_result = file_put_contents($destination_path, $menu_html);

    if ($write_result === false) {
        $error_message = 'Erreur lors de l\'écriture dans le fichier: ' . error_get_last()['message'];
        trigger_error($error_message, E_USER_ERROR);
    }
}

// Spécifiez le chemin complet du fichier de destination en utilisant __DIR__.
$menu_url = 'https://www.crous-poitiers.fr/restaurant/r-u-crousty/';
$destination_path = __DIR__ . '/backup_cal/menu.html';

// Appelez la fonction avec les paramètres.
downloadMenuFile($menu_url, $destination_path);
?>
