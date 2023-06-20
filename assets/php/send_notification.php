<?php
require '../../bootstrap.php'; // Inclure la bibliothèque JWT si nécessaire

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Assuming you already have a valid $dbh connection to your database

$query = "SELECT * FROM subscriptions";
$stmt = $dbh->query($query);
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$auth = array(
    'VAPID' => array(
        'subject' => 'https://github.com/Minishlink/web-push-php-example/',
        'publicKey' => $_ENV['VAPID_PUBLIC_KEY'],
        'privateKey' => $_ENV['VAPID_PRIVATE_KEY'],
    ),
);

$webPush = new WebPush($auth);

foreach ($subscriptions as $subscriptionData) {
    $subscription = Subscription::create([
        'endpoint' => $subscriptionData['endpoint'],
        'publicKey' => $subscriptionData['p256dh'],
        'authToken' => $subscriptionData['auth'],
        // You can add additional properties if needed, e.g., 'contentEncoding', 'expirationTime', etc.
    ]);

    $report = $webPush->sendOneNotification($subscription, "Hello! 👋");

    $endpoint = $report->getRequest()->getUri()->__toString();

    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.\n";
    } else {
        echo "[x] Message failed to send for subscription {$endpoint}: {$report->getReason()}\n";
        // Handle the failure, remove the subscription from your server, etc.
    }
}
?>