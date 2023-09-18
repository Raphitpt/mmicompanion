<?php
require '../bootstrap.php';

// Lisez la clé publique à partir du fichier
$vapidPublicKey = $_ENV['VAPID_PUBLIC_KEY'];

// Répondez au client avec la clé publique VAPID
header('Content-Type: application/json');
echo json_encode(['key' => $vapidPublicKey]);