<?php
session_start();
session_destroy();
?>

<script>
    // Supprimer le JWT du localStorage
    localStorage.removeItem('jwt');

    // Redirection vers la page de déconnexion ou autre page appropriée
    window.location.href = './login.php';
</script>