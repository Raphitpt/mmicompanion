<?php
require './../bootstrap.php';
$user = onConnect($dbh);

$menuDataByDay = getMenu();

foreach ($menuDataByDay as $day => $menuData) {
    echo '<h2>' . $day . '</h2>';
    echo '<ul>';
    foreach ($menuData as $menu) {
        echo '<li>' . $menu['name'] . '</li>';
    }
    echo '</ul>';
}

?>

