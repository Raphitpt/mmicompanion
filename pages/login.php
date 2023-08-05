<?php
session_start();
require '../bootstrap.php';

use Firebase\JWT\JWT;

$secret_key = $_ENV['SECRET_KEY'];

if (isset($_POST['username']) && isset($_POST['password'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $login = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE pname = :login OR edu_mail = :login";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'login' => $login,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);

            $payload = [
                'id_user' => $user['id_user'],
                'pname' => $user['pname'],
                'name' => $user['name'],
                'edu_group' => $user['edu_group'],
                'edu_mail' => $user['edu_mail'],
                'role' => $user['role'],
            ];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            // Envoi du JWT au client sous forme de réponse JSON
            $response = array('jwt' => $jwt);
            header('Content-Type: application/json');
            setcookie('jwt', $jwt, time() + (86400 * 30), "/", "", false, true);
            echo json_encode($response);
            exit();
        } else {
            $response = array('error' => 'Identifiant ou mot de passe incorrect');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        $response = array('error' => 'Veuillez remplir tous les champs');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

echo head("MMI Companion | Connexion");
?>

<body class="body-login">
    <a href="./accueil.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>
    <main class="main-login">
        <h1 class="title-login">SE CONNECTER</h1>
        <div style="height:30px"></div>
        <form method="POST" class="form-login">
            <input type="text" name="username" placeholder="prénom ou email" id="username" class="input-login" required>
            <div style="height:20px"></div>
            <input type="password" name="password" placeholder="mot de passe" id="password" class="input-login"
                required>
            <div class="button_forget-login">
                <a href="./forgot.php" class="button_forget-login">Mot de passe oublié ?</a>
            </div>
            <div style="height:30px"></div>
            <input type="submit" value="Se connecter" class="button_register">
            <div style="height:15px"></div>
            <div class="error_message-login"></div>
        </form>
    </main>
    <script>
        document.querySelector('.form-login').addEventListener('submit', function (e) {
            e.preventDefault();

            let username = document.querySelector('#username').value;
            let password = document.querySelector('#password').value;

            // Effectuez une requête AJAX vers le script "login.php" pour obtenir le JWT
            // Assurez-vous d'ajuster l'URL et les paramètres de la requête AJAX selon votre configuration
            let url = window.location.origin + '/mmicompanion/pages/login.php';

            // Exemple d'utilisation de la bibliothèque jQuery pour la requête AJAX
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    username: username,
                    password: password
                },
                success: function (response) {
                    if (response.error) {
                        // Afficher le message d'erreur dans la console
                        console.log(response.error);
                        // Afficher le message d'erreur dans la page
                        document.querySelector('.error_message-login').innerHTML = response.error;
                    } else {
                        // Stockage du JWT dans le localStorage
                        localStorage.setItem('jwt', response.jwt);

                        // Redirection vers la page d'accueil ou autre page sécurisée
                        window.location.href = './index.php';
                    }
                },
                error: function () {
                    // Gérer les erreurs de connexion ici
                }
            });
        });
    </script>
</body>

</html>