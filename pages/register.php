<?php
session_start();
require '../bootstrap.php';

// Création de la variable pour afficher les messages d'erreurs quand l'utilisateur clique sur "Créer un compte"
$error_message = "";
$_SESSION['error_message'] = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['pname']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['name']) && isset($_POST['edu_mail'])) {
       if (filter_var($_POST['edu_mail'], FILTER_VALIDATE_EMAIL) === false) {
        $error_message = "L'email n'est pas valide.";
        exit();
        }

        $pname = trim(strip_tags($_POST['pname']));
        $name = trim(strip_tags($_POST['name']));
        $password = strip_tags($_POST['password']);
        $edu_mail = trim(strtolower(strip_tags($_POST['edu_mail'])));
        $edu_group = "undefined";
        $confirm_password = strip_tags($_POST['confirm_password']);

        $ve = new hbattat\VerifyEmail($edu_mail, 'no-reply@mmi-companion.fr');

        if ($ve->verify() === false) {
            $error_message = "L'email n'est pas valide.";
            header('Location: ./register.php?error_message='.$error_message.'');
            exit();
        }

        // Vérifier si l'utilisateur existe déjà dans la base de données sinon créer son compte
        $sql_check = "SELECT * FROM users WHERE edu_mail = :edu_mail";
        $stmt = $dbh->prepare($sql_check);
        $stmt->execute([
            ':edu_mail' => $edu_mail,
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            $error_message = "L'utilisateur existe déjà.";
        } else if ($password != $confirm_password){
            $error_message = "Les mots de passe ne correspondent pas.";
            $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        } else{
            // On hash le mot de passe pour plus de sécurité, le MD5 est déconseillé, on laisse l'agorithme par défaut, ça évite les failles de sécurité
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            // On génère un code d'activation pour l'utilisateur
            $activation_code = generate_activation_code();

            $pp_profile = 'https://ui-avatars.com/api/?background=56b8d6&color=004a5a&bold=true&name='.$pname.'+'.$name.'&rounded=true&size=128';

            if(str_contains($edu_mail, "@univ-poitiers.fr")){
                $sql_register = "INSERT INTO users (pname, name, password, edu_mail, edu_group, verification_code_mail, pp_link, role) VALUES (:pname, :name, :pass, :edu_mail, :edu_group, :activation_code, :pp_link, :role)";
                $stmt = $dbh->prepare($sql_register);
                $stmt->execute([
                    ':pname' => $pname,
                    ':name' => $name,
                    ':pass' => $hash_password,
                    ':edu_mail' => $edu_mail,
                    ':edu_group' => $edu_group,
                    ':activation_code' => $activation_code,
                    ':pp_link' => $pp_profile,
                    'role' => 'prof'
                ]);
            } else {
                $sql_register = "INSERT INTO users (pname, name, password, edu_mail, edu_group, verification_code_mail, pp_link) VALUES (:pname, :name, :pass, :edu_mail, :edu_group, :activation_code, :pp_link)";
                $stmt = $dbh->prepare($sql_register);
                $stmt->execute([
                    ':pname' => $pname,
                    ':name' => $name,
                    ':pass' => $hash_password,
                    ':edu_mail' => $edu_mail,
                    ':edu_group' => $edu_group,
                    ':activation_code' => $activation_code,
                    ':pp_link' => $pp_profile
                ]);
            }
            $data = array(
                'mail_user' => $edu_mail,
                'activation_code' => $activation_code,
                'pname' => $pname
            );
            $_SESSION['post_data'] = $data;
            header('Location: ./mail.php');
            exit();
        }

    }  else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}

echo head('MMI Companion | Register');

?>

<body class="body-login">
    <a href="./index.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>
    <main class="main-login">
        <h1 class="title-login">CRÉER UN COMPTE</h1>
        <div style="height:30px"></div>
        <form action="" method="post" class="form-login">
            <div class="form-register">
                <input type="text" name="pname" placeholder="prénom" class="input-login" required>
                <div style="height:20px"></div>
                <input type="text" name="name" placeholder="nom" class="input-login" required>
                <div style="height:20px"></div>
                <input type="text" name="edu_mail" placeholder="adresse mail étudiante" pattern="^(.+@etu\.univ-poitiers\.fr|.+@univ-poitiers\.fr)$" class="input-login" required>
                <div style="height:20px"></div>
                <input type="password" name="password" placeholder="mot de passe" class="input-login" required>
                <div style="height:20px"></div>
                <input type="password" name="confirm_password" placeholder="confirmer mot de passe" class="input-login" required>
                <div class="trait_register"></div>
                <input type="submit" value="Créer mon compte" class="button_register">
                <div style="height:15px"></div>
                <?php if(!empty($_SESSION['error_message'])) { ?>
                    <div class="error_message-login"><?php echo $_SESSION['error_message']; ?></div>
                <?php
                unset($_SESSION['error_message']);
            } ?>
            </div>
        </form>
    </main>


</body>


</html>