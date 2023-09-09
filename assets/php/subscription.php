<?php
require_once '../../bootstrap.php'; // Inclure votre fichier bootstrap.php contenant la connexion à la base de données
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$user = decodeJWT($jwt, $secret_key);
// Récupérer les données de l'abonnement depuis la requête POST
$subscriptionData = json_decode(file_get_contents('php://input'), true);

// Vérifier si les données de l'abonnement sont présentes
if (empty($subscriptionData)) {
    // Répondre avec une erreur indiquant que les données d'abonnement sont manquantes
    $response = array('error' => 'Données d\'abonnement manquantes');
    echo json_encode($response);
    return;
}

// Valider les données de l'abonnement
if (!isset($subscriptionData['endpoint']) || !isset($subscriptionData['keys']) || !isset($subscriptionData['keys']['p256dh']) || !isset($subscriptionData['keys']['auth'])) {
    // Répondre avec une erreur indiquant des données d'abonnement invalides
    $response = array('error' => 'Données d\'abonnement invalides');
    echo json_encode($response);
    return;
}

// Vérifier si l'abonnement existe déjà dans la base de données
$stmt = $dbh->prepare("SELECT * FROM subscriptions WHERE endpoint = :endpoint");
$stmt->bindParam(':endpoint', $subscriptionData['endpoint']);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // L'abonnement existe déjà, vous pouvez répondre avec un message de succès
    $response = array('success' => 'Abonnement déjà enregistré');
    echo json_encode($response);
    return;
}

// L'abonnement n'existe pas encore, vous pouvez l'enregistrer dans la base de données
try {
    // Préparer la requête SQL pour l'insertion de l'abonnement dans la table
    $stmt = $dbh->prepare("INSERT INTO subscriptions (endpoint, p256dh, auth, edu_mail) VALUES (:endpoint, :p256dh, :auth, :edu_mail)");
    $stmt->bindParam(':endpoint', $subscriptionData['endpoint']);
    $stmt->bindParam(':edu_mail', $user['edu_mail']);
    $stmt->bindParam(':p256dh', $subscriptionData['keys']['p256dh']);
    $stmt->bindParam(':auth', $subscriptionData['keys']['auth']);

    // Exécuter la requête SQL
    $stmt->execute();

    // Répondre avec une confirmation de succès
    $response = array('success' => 'Abonnement enregistré avec succès');
    echo json_encode($response);
} catch (PDOException $e) {
    // Répondre avec une erreur de base de données
    $response = array('error' => 'Erreur de base de données : ' . $e->getMessage());
    echo json_encode($response);
}
?>