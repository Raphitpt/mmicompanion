<?php
session_start();
require '../bootstrap.php';

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) && isset($_GET['activation_code'])){
    $activation_code = $_GET['activation_code'];
    $edu_mail = $_GET['email'];
    $sql_code = "SELECT verification_code_mail FROM users WHERE edu_mail = :edu_mail";
    $stmt_code = $dbh->prepare($sql_code);
    $stmt_code->execute([
        'edu_mail' => $edu_mail,
    ]);
    $user = $stmt_code->fetch(PDO::FETCH_ASSOC);
    if($user['verification_code_mail'] == $activation_code) { 

?>

<body class="body-login">
    <main class="main-login">
        <form method="POST">
            <input type="hidden" name="mail_user" value="<?php echo $edu_mail; ?>">
            <input type="password" name="password" class="input-login"  placeholder="nouveau mot de passe">
            <input type="password" name="password_confirm" class="input-login"  placeholder="confirmer le mot de passe">
            <input type="submit" name="submit" class="button_register" value="valider">
        </form>
    </main>
</body>
        
<?php }

if(isset($_POST['SUBMIT'])){
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    if($password == $password_confirm){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE edu_mail = :edu_mail";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'password' => $password,
            'edu_mail' => $edu_mail
        ]);
        header('Location: ./login.php');
        exit();
    }
    else{
        header('Location: ./reset_password.php');
        $_SESSION['erreur'] = "Les mot de passe ne correspondent pas !";
        exit();
    }
}
}
else{
    header('Location: ./accueil.php');
    exit();
}