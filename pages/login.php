<?php
session_start();
require '../bootstrap.php';

use Firebase\JWT\JWT;

$secret_key = $_ENV['SECRET_KEY'];

if (isset($_POST['username']) && isset($_POST['password'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
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

            $payload = [
                'user' => $user['username'],
                'edu_group' => $user['edu_group'],
                'edu_number' => $user['edu_number'],
                'edu_mail' => $user['edu_mail'],
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

echo head("login");
?>

<body class="login">
    <a href="./accueil.php" class="back_btn"><img src="../assets/img/arrow.svg" alt="Retour"></a>
    <main>
        <h1 class="login_title">SE CONNECTER</h1>
    <form method="POST" class="login_form">
        <input type="text" name="username" placeholder="email ou pseudo" id="username" class="login_inpt"required>
        <input type="password" name="password" placeholder="mot de passe" id="password" class="login_inpt" required>
        <input type="submit" value="Se connecter" class="register_btn">
        <a href="forgot.php" class="login_btn">Mot de passe oublié ?</a>
    </form>
    </main>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            let username = document.getElementById('username').value;
            let password = document.getElementById('password').value;

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
                success: function(response) {
                    if (response.error) {
                        // Afficher le message d'erreur dans le formulaire
                        console.log(response.error);
                    } else {
                        // Stockage du JWT dans le localStorage
                        localStorage.setItem('jwt', response.jwt);

                        // Redirection vers la page d'accueil ou autre page sécurisée
                        window.location.href = './index.php';
                    }
                },
                error: function() {
                    // Gérer les erreurs de connexion ici
                }
            });
        });
    </script>
</body>