<?php
session_start();
require '../bootstrap.php';
unset($_SESSION['mail_message']);

// On initialise la bibliothèque Firebase JWT pour PHP avec Composer et on y ajoute la clé secrète qui est dans le fichier .env (ne pas push le fichier .env sur GitHub)
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

$secret_key = $_ENV['SECRET_KEY'];

if (isset($_POST['username']) && isset($_POST['password'])) {
    // On vérifie si les champs sont remplis
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        // On récupère les données du formulaire et on les compare avec la base de donnée
        $login = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users WHERE edu_mail = :login";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'login' => $login,
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si le mot de passe est bon on crée le JWT et on le renvoie au client, par mesure de sécurité on ne renvoie pas le mot de passe dans le cookie
        // On utilise la fonction password_verify() pour comparer le mot de passe en clair avec le mot de passe hashé, le MD5 est déconseillé


        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            // On vérifie si le compte est activé par mail
            if ($user && $user['active'] == 0) {
                $response = array('active' => false);
                $response['mailUser'] = $user['edu_mail'];
                $response['idUser'] = $user['id_user'];
                $response['activationCode'] = $user['verification_code_mail'];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
            // Si on veut ajouter un champs dans le cookie il faut l'ajouter dans le tableau ci-dessous puis dans le fichier function.php
            $uuid = Uuid::uuid4();
            $session_id = $uuid->toString();
            $payload = [
                'id_user' => $user['id_user'],
                'pname' => $user['pname'],
                'name' => $user['name'],
                'edu_group' => $user['edu_group'],
                'edu_mail' => $user['edu_mail'],
                'role' => $user['role'],
                'session_id' => $session_id,
            ];
            // La on encode le JWT avec la clé secrète qui est dans le fichier .env, ne pas toucher
            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            // Envoi du JWT au client sous forme de réponse JSON
            // Le cookie s'appelle jwt mais il peut se nommer différemment
            $response = array('jwt' => $jwt);
            header('Content-Type: application/json');
            // le cookie est valable 30 jours mais il peut être valable plus ou moins longtemps
            // Pour plus d'info, voir le detail de la fonction setcookie() sur le site de PHP
            setcookie('jwt', $jwt, time() + (86400 * 260), "/", "", false, true);
            // On renvoie la réponse au client
            $session_sql = "INSERT INTO sessions (user_id, session_id, expires_at, ip_address, info_device) VALUES (:user_id, :session_id, :expires_at, :ip_address, :info_device)";
            $stmt = $dbh->prepare($session_sql);
            $stmt->execute([
                'user_id' => $user['id_user'],
                'session_id' => $session_id,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+260 days')),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'info_device' => $_SERVER["HTTP_USER_AGENT"]
            ]);

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
    <a href="./index.php" class="back_btn">
        <i class="fi fi-br-arrow-alt-right"></i>
    </a>
    <main class="main-login">
        <h1 class="title-login">SE CONNECTER</h1>
        <div style="height:30px"></div>
        <p style="color: red; text-align: center;">Bonjour, suite à l'amélioration de notre système de connexion, vous avez été déconnecté. Nous nous excusons pour cette interruption, mais cela a été fait afin de garantir la sécurité de vos données.</p>
        <div style="height:30px"></div>
        <form method="POST" class="form-login">
            <input type="text" name="username" placeholder="email" id="username" class="input-login" required>
            <div style="height:20px"></div>
            <input type="password" name="password" placeholder="mot de passe" id="password" class="input-login" required>
            <div class="button_forget-login">
                <p><a href="./lost_password.php" class="button_forget-login">Mot de passe oublié ?</a></p>
            </div>
            <div style="height:30px"></div>
            <input type="submit" value="Se connecter" class="button_register">
            <div style="height:15px"></div>
            <?php if (isset($_SESSION['success_mail'])) : ?>
                <div class="success_message-login">
                    <?= $_SESSION['success_mail']; ?>
                </div>
                <?php unset($_SESSION['success_mail']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_mail'])) : ?>
                <div class="error_message-login">
                    <?= $_SESSION['error_mail']; ?>
                </div>
                <?php unset($_SESSION['error_mail']); ?>
            <?php endif; ?>
            <div class="error_message-login"></div>
        </form>
    </main>
    <script>
        document.querySelector('.form-login').addEventListener('submit', function(e) {
            e.preventDefault();

            let username = document.querySelector('#username').value;
            let password = document.querySelector('#password').value;

            // Effectuez une requête AJAX vers le script "login.php" pour obtenir le JWT
            // Assurez-vous d'ajuster l'URL et les paramètres de la requête AJAX selon votre configuration
            let url = window.location.origin + getDynamicPath() + '/pages/login.php';

            function getDynamicPath() {
                let path = window.location.pathname;
                let index = path.indexOf('mmicompanion');

                if (index !== -1) {
                    return path.substring(0, index + 'mmicompanion'.length);
                } else {
                    return path;
                }
            }


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
                        // Afficher le message d'erreur dans la console
                        console.log(response.error);
                        // Afficher le message d'erreur dans la page
                        document.querySelector('.error_message-login').innerHTML = response.error;
                    } else {
                        if (response.active === false) { // Vérification si active est égal à false
                            // Créer un formulaire dynamique
                            let form = document.createElement('form');
                            form.method = 'post';
                            form.action = './mail.php';

                            // Créer des champs cachés pour les données
                            let mailUserInput = document.createElement('input');
                            mailUserInput.type = 'hidden';
                            mailUserInput.name = 'mail_user';
                            mailUserInput.value = response.mailUser;
                            form.appendChild(mailUserInput);

                            let idUserInput = document.createElement('input');
                            idUserInput.type = 'hidden';
                            idUserInput.name = 'id_user';
                            idUserInput.value = response.idUser;
                            form.appendChild(idUserInput);

                            let activationCodeInput = document.createElement('input');
                            activationCodeInput.type = 'hidden';
                            activationCodeInput.name = 'activation_code';
                            activationCodeInput.value = response.activationCode;
                            form.appendChild(activationCodeInput);

                            // Ajouter le formulaire à la page et le soumettre
                            document.body.appendChild(form);
                            form.submit();
                        } else {
                            // Stockage du JWT dans le localStorage
                            // localStorage.setItem('jwt', response.jwt);

                            // Redirection vers la page d'accueil ou autre page sécurisée
                            window.location.href = './home.php';
                        }
                    }
                },
                error: function() {
                    // Gérer les erreurs de connexion ici
                }
            });
        });
    </script>
</body>

</html>