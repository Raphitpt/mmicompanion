<?php
require '../../bootstrap.php'; // Inclure la bibliothèque JWT si nécessaire

// Récupérer le JWT envoyé dans la requête AJAX
$jwt = $_POST['jwt'];
$secret_key = $_ENV['SECRET_KEY'];
try {
    // Vérifier la validité du JWT en le décodant avec la clé secrète
    $decoded = \Firebase\JWT\JWT::decode($jwt, $secret_key, array('HS256'));

    // Le JWT est valide, vous pouvez renvoyer une réponse avec succès
    echo json_encode(array('valid' => true));
} catch (Exception $e) {
    // Le JWT est invalide ou a expiré, renvoyer une réponse d'erreur
    echo json_encode(array('valid' => false));
}