<?php
require '../../bootstrap.php'; // Inclure la bibliothèque JWT si nécessaire

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function sendNotification($dbh, $message, $body, $group) {
    // Assuming you already have a valid $dbh connection to your database
    if ($group != null) {
        $query = "SELECT s.* FROM subscriptions s
                  INNER JOIN users u ON s.edu_mail = u.edu_mail
                  WHERE u.edu_group = :group";
        $stmt = $dbh->prepare($query);
        $stmt->execute(['group' => $group]);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $query = "SELECT * FROM subscriptions";
        $stmt = $dbh->query($query);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    $auth = array(
        'VAPID' => array(
            'subject' => 'mailto:rtiphonet@gmail.com',
            'publicKey' => $_ENV['VAPID_PUBLIC_KEY'],
            'privateKey' => $_ENV['VAPID_PRIVATE_KEY'],
        ),
    );

    $webPush = new WebPush($auth);

    foreach ($subscriptions as $subscriptionData) {
        $subscription = Subscription::create([
            'endpoint' => $subscriptionData['endpoint'],
            'keys' => [
                'p256dh' => $subscriptionData['p256dh'],
                'auth' => $subscriptionData['auth'],
            ],
            // You can add additional properties if needed, e.g., 'contentEncoding', 'expirationTime', etc.
        ]);

        $payload = json_encode([
            'body' => $body,
            'title' => $message
        ]);

        $webPush->queueNotification($subscription, $payload);
    }

    $webPush->flush();

    foreach ($webPush->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();

        if ($report->isSuccess()) {
            echo "[v] Le message à bien été envoyer à {$endpoint}.\n";
        } else {
            echo "[x] Le message n'a pas réussi à être envoyer {$endpoint}: {$report->getReason()}\n";
            // Handle the failure, remove the subscription from your server, etc.
        }
    }
}
?>