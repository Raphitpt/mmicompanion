<?php
session_start();
require '../bootstrap.php';


if (isset($_POST['username']) && isset($_POST['password'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $login = $_POST['username'];
        $password = md5($_POST['password']);
        $sql = "SELECT * FROM  users WHERE username = :login AND password = :password";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'login' => $login,
            'password' => $password
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            header('Location: ./index.php');
            exit();
        } else {
            $message = "Identifiant ou mot de passe incorrect";
        }
    } else {
        $message = "Veuillez remplir tous les champs";
    }
}


echo head("login");
?>
<style>
body {
        background-color: #f1f1f1;
    }
    form {
        background-color: #ffffff;
        width: 300px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #f1f1f1;
    }
    input[type=text], input[type=password] {
        width: 100%;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: 1px solid #f1f1f1;
    }
    input[type=submit] {
        background-color: #4CAF50;
        color: #ffffff;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    input[type=submit]:hover {
        opacity: 0.8;
    }
    span {
        color: red;
    }
    label {
        color: #999;
        text-shadow: 0 1px 0 #fff;
        font-size: 14px;
        font-weight: bold;
    }
    select {
        width: 100%;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: 1px solid #f1f1f1;
    }
    a {
        background-color: #4CAF50;
        color: #ffffff;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        text-decoration: none;
    }
</style>
<body>
    <form method="POST">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <input type="submit" value="Login" class="btn btn-primary">
        <a href="register.php" class="btn btn-secondary">Register</a>
        <a href="forgot.php" class="btn btn-secondary">Forgot password</a>
    </form>
</body>