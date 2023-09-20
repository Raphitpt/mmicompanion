<?php
session_start();
include './../bootstrap.php';

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY'];
$user = decodeJWT($jwt, $secret_key);

$sql_events = "SELECT * FROM calendar_event WHERE id_user = :user_id";
$stmt = $dbh->prepare($sql_events);
$stmt->bindParam(':user_id', $user['id_user']);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$eventList = [];
foreach ($events as $event) {
    $eventList[] = [
        'start' => $event['start'],
        'end' => $event['end'],
        'title' => $event['title'],
        'description' => $event['description'],
        'location' => $event['location'],
        'id' => $event['id_event']
    ];
}

echo json_encode($eventList);

