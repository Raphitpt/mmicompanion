<?php
require '../bootstrap.php';

$user = onConnect($dbh);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'];
    $id_user = $user['id_user'];

    $sql_verify = "SELECT * FROM subscriptions WHERE id_user = :id_user AND token = :token";
    $stmt_verify = $dbh->prepare($sql_verify);
    $stmt_verify->execute([
        'id_user' => $id_user,
        'token' => $token
    ]);
    if ($stmt_verify->rowCount() > 0) {
        echo json_encode(['message' => 'Subscription already exists']);
        exit();
    } else {
        $sql = "INSERT INTO subscriptions (id_user, token) VALUES (:id_user, :token)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'id_user' => $id_user,
            'token' => $token
        ]);
        // si erreur 1062

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['message' => 'Method not allowed']);
}
