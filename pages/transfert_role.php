<?php
session_start();
require "../bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_user']) && !empty($_POST['passage_role'])) {
    $id_user = $_POST['id_user'];
    $user_choisi = $_POST['passage_role'];
    $role_next = 'chef';
    $role_old = 'eleve';
    var_dump($user_choisi);
    // Utilisez une transaction pour vous assurer que les deux mises à jour sont atomiques
    try {
        $dbh->beginTransaction();
        
        // Mettez à jour le rôle du membre choisi
        $sql_update_user_choisi = "UPDATE users SET role=:role_next WHERE id_user=:id_user_choisi";
        $stmt_update_user_choisi = $dbh->prepare($sql_update_user_choisi);
        $stmt_update_user_choisi->execute([
            'role_next' => $role_next,
            'id_user_choisi' => $user_choisi
        ]);
        
        // Mettez à jour le rôle de l'utilisateur actuel
        $sql_update_user = "UPDATE users SET role=:role_old WHERE id_user=:id_user";
        $stmt_update_user = $dbh->prepare($sql_update_user);
        $stmt_update_user->execute([
            'role_old' => $role_old,
            'id_user' => $id_user
        ]);
        
        // Si les deux mises à jour réussissent, validez la transaction
        $dbh->commit();
        
        // Redirigez vers la page de profil
        header('Location: ./logout.php');
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, annulez la transaction
        $dbh->rollBack();
        $_SESSION['erreur_role'] = "Une erreur est survenue lors du changement de rôle.";
        header('Location: ./profil.php'); // Redirigez vers la page de profil avec un message d'erreur
        exit();
    }
}
?>
