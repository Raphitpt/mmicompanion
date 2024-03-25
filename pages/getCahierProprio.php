<?php
session_start();
require './../bootstrap.php';

$user = onConnect($dbh);

// --------------------
// Fin de la récupération du cookie
$user_sql = userSQL($dbh, $user);




