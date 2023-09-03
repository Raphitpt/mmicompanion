<!-- Script qui gère l'ajout des points en base de donnée, voir la fonction js -->
<?php
session_start();
require './../bootstrap.php';

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);

if(isset($_POST['points'])){
    $points = $_POST['points'];
    // converti la variable en entiers
    $points = intval($points);
    $sql = "UPDATE users SET score = score + :nombre WHERE id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'nombre' => $points,
        'id_user' => $users['id_user']
    ]);
    exit();
}
?>