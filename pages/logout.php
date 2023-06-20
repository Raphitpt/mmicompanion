<?php 
session_start();
if (isset($_COOKIE['jwt'])) {
    unset($_COOKIE['jwt']);
    setcookie('jwt', '', time() - 3600, '/'); // empty value and old timestamp
}
?>

<script>
    // Supprimer le JWT du localStorage
    localStorage.removeItem('jwt');

    window.location.href = './login.php';
</script>