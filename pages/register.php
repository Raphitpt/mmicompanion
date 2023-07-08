<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pname']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['name']) && isset($_POST['edu_mail']) && isset($_POST['edu_group1']) && isset($_POST['edu_group2'])) {
    if (filter_var($_POST['edu_mail'], FILTER_VALIDATE_EMAIL) === false) {
        echo 'Email is not valid!';
        exit;
    }

    $pname = strip_tags($_POST['pname']);
    $name = strip_tags($_POST['name']);
    $password = strip_tags($_POST['password']);
    $edu_mail = strip_tags($_POST['edu_mail']);
    $edu_group = strip_tags($_POST['edu_group1']) . "-" . strip_tags($_POST['edu_group2']);
    $confirm_password = strip_tags($_POST['confirm_password']);

    if ($password != $confirm_password) {
        echo "Les mots de passe ne correspondent pas!";
        exit;
    }
    // check if email or edu_num or username already exist in database
    $sql_check = "SELECT * FROM users WHERE edu_mail = :edu_mail";
    $stmt = $dbh->prepare($sql_check);
    $stmt->execute([
        ':edu_mail' => $edu_mail,
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo "L'utilisateurs existe déjà!";
        exit;
    }


    $sql_register = "INSERT INTO users (pname, name, password, edu_mail, edu_group) VALUES (:pname, :name, :pass, :edu_mail, :edu_group)";
    $stmt = $dbh->prepare($sql_register);
    $stmt->execute([
        ':pname' => $pname,
        ':name' => $name,
        ':pass' => md5($password),
        ':edu_number' => $edu_number,
        ':edu_mail' => $edu_mail,
        ':edu_group' => $edu_group,
    ]);


    echo 'User created!';
    exit;
}
echo head('Register');
?>

<body class="body-login">
    <a href="./accueil.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>
    <main class="main-login">
        <h1 class="title-login">CRÉER UN COMPTE</h1>
        <div style="height:30px"></div>
        <form action="" method="post" class="form-login">
            <input type="text" name="pname" placeholder="prénom" class="input-login" required>
            <div style="height:20px"></div>
            <input type="text" name="name" placeholder="nom" class="input-login" required>
            <div style="height:20px"></div>
            <input type="password" name="password" placeholder="mot de passe" class="input-login" required>
            <div style="height:20px"></div>
            <input type="password" name="confirm_password" placeholder="confirmer mot de passe" class="input-login"
                required>
            <div style="height:20px"></div>
            <input type="text" name="edu_mail" placeholder="adresse mail étudiante" pattern=".+@etu\.univ-poitiers\.fr"
                class="input-login" required>
            <div class="trait_register"></div>
            <div class="label_edu-register">
                <label for="edu_group">Choisissez votre classe :</label>
            </div>
            
            <div style="height:10px"></div>
            <select name="edu_group1" class="input-login">
                <option value="BUT1">BUT1</option>
                <option value="BUT2">BUT2</option>
                <option value="BUT3">BUT3</option>
            </select>
            <div style="height:20px"></div>
            <select name="edu_group2" class="input-login">
                <option value="TP1">TP1</option>
                <option value="TP2">TP2</option>
                <option value="TP3">TP3</option>
                <option value="TP4">TP4</option>

            </select>
            <div style="height:30px"></div>
            <input type="submit" value="Créer mon compte" class="button_register">
        </form>
    </main>
</body>

</html>