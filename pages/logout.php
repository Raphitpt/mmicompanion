<?php
session_start();
 // Inclure la bibliothèque JWT si nécessaire
?>

<script>
    // Supprimer le JWT du localStorage
    localStorage.removeItem('jwt');
    <?php session_destroy(); ?>
    // Redirection vers la page de déconnexion ou autre page appropriée
    window.location.href = './login.php';
</script>