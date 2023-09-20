<?php

require 'bootstrap.php';

$ve = new hbattat\VerifyEmail('raphael.tiphonet@etu.univ-poitiers.fr', 'no-reply@mmi-companion.fr');

var_dump($ve->verify());
print_r($ve->get_errors());
print_r($ve->get_debug());