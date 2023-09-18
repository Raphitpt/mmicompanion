<?php
require '../bootstrap.php';

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; 
$user = decodeJWT($jwt, $secret_key);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $endpoint = $data['endpoint'];
    $auth = $data['keys']['auth'];
    $p256dh = $data['keys']['p256dh'];
    $id_user = $user['id_user'];

    $sql = "INSERT INTO subscriptions (id_user, endpoint, auth, p256dh) VALUES (:id_user, :endpoint, :auth, :p256dh)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'id_user' => $id_user,
        'endpoint' => $endpoint,
        'auth' => $auth,
        'p256dh' => $p256dh
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['message' => 'Method not allowed']);
}
?>