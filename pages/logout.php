<!-- Script pour se deconneter avec plusieurs sécurités -->
<?php 
session_start();
require '../bootstrap.php';

$user = onConnect($dbh);

if (isset($_COOKIE['jwt'])) {
    $session_sql = "DELETE FROM sessions WHERE session_id = :session_id";
    $stmt = $dbh->prepare($session_sql);
    $stmt->execute([
        'session_id' => $user['session_id'],
    ]);
    unset($_COOKIE['jwt']);
    setcookie('jwt', '', time() - 3600, '/'); // empty value and old timestamp
}
?>

<script>
    // Supprimer le JWT du localStorage
    localStorage.removeItem('jwt');

    window.location.href = './index.php';
</script>