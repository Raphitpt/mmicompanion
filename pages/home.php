<?php
session_start();
require '../bootstrap.php';

$nextCours = nextCours('BUT2-TP3');

var_dump($nextCours);