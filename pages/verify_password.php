<?php
session_start();
require '../bootstrap.php';

echo head("MMI Companion | Reset mot de passe");

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) && isset($_GET['activation_code'])){
    $activation_code = $_GET['activation_code'];
    $edu_mail = $_GET['email'];
    // var_dump($activation_code);
    $sql_code = "SELECT verification_code_pass FROM users WHERE edu_mail = :edu_mail";
    $stmt_code = $dbh->prepare($sql_code);
    $stmt_code->execute([
        'edu_mail' => $edu_mail,
    ]);
    $user = $stmt_code->fetch(PDO::FETCH_ASSOC);
    if($user['verification_code_pass'] == $activation_code && $activation_code != null && $edu_mail != null && $activation_code != "" && $activation_code != 0 || isset($_SESSION['erreur_password'])) { 

?>

<body class="body-login">
    <a href="./login.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>  
    <main class="main-login">
        <h1 class="title-login">Nouveau mot de passe</h1>
        <div style="height:30px"></div>
        <form method="POST" class="form-login" action="reset_pass.php">
            <input type="hidden" name="mail_user" value="<?php echo $edu_mail; ?>">
            <input type="hidden" name="activation_code" value="<?php echo $activation_code; ?>">
            <input type="password" name="password" class="input-login"  placeholder="nouveau mot de passe" required>
            <div style="height:20px"></div>
            <input type="password" name="password_confirm" class="input-login"  placeholder="confirmer le mot de passe" required>
            <div style="height:30px"></div>
            <?php if(isset($_SESSION['erreur_password'])) { ?>
                <div class="error_message-login"><?php echo $_SESSION['erreur_password']; ?></div>
                <div style="height:15px"></div>
            <?php } ?>
            <input type="submit" name="submit" class="button_register" value="valider">
        </form>
    </main>
</body>

</html>
        
<?php 
} else {
    header('Location: ./login.php');
    exit();
}; 

}

?>