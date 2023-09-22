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
$events = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $event = [
        'id' => $row['id_event'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'description' => $row['description'],
        'location' => $row['location'],
        'color' => $row['color'],
    ];
    array_push($events, $event);
}

echo json_encode($events);

