<?php
session_start();
require '../bootstrap.php';

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
    if($user['verification_code_pass'] == $activation_code && $activation_code != null && $edu_mail != null && $activation_code != "" && $activation_code != 0) { 

?>

<body class="body-login">
    <main class="main-login">
        <form method="POST" action="reset_pass.php">
            <input type="hidden" name="mail_user" value="<?php echo $edu_mail; ?>">
            <input type="password" name="password" class="input-login"  placeholder="nouveau mot de passe">
            <input type="password" name="password_confirm" class="input-login"  placeholder="confirmer le mot de passe">
            <input type="submit" name="submit" class="button_register" value="valider">
        </form>
    </main>
</body>
        
<?php } else {
    header('Location: ./login.php');
    exit();
}; }

?>