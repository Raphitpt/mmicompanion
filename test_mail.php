<?php

require 'vendor/autoload.php';

use SMTPValidateEmail\Validator as SmtpEmailValidator;

$email     = 'raphael.tiphonet@etu.univ-poitiers.fr';
$sender    = 'no-reply@mmi-companion.fr';
$validator = new SmtpEmailValidator($email, $sender);

// If debug mode is turned on, logged data is printed as it happens:
// $validator->debug = true;
$results   = $validator->validate();

dd($results);
