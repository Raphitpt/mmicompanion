<?php

/* Fichier : /config/config.prod.php */

// le nom de la base
define('APP_DB_NAME', $_ENV['DB_NAME']);

// le nom de la machine qui héberge le serveur MYSQL
define('APP_DB_HOST', $_ENV['DB_HOST']);

// le nom de l'utilisateur MYSQL
define('APP_DB_USER', $_ENV['DB_USER']);

// le mot de passe de l'utilisateur MYSQL
define('APP_DB_PASSWORD', $_ENV['DB_PASSWORD']);