<!-- 
        Page d'accueil quand on arrive sur l'application via le QR-code ou bien en cliquant sur le lien
        On peut s'inscrire(register.php) ou se connecter(login.php)
        On peut aussi accéder à la page d'accueil en cliquant sur le logo en haut à gauche
-->

<?php
session_start();
require "../bootstrap.php";

// si le cookie n'existe pas, on redirige vers la page d'accueil
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./accueil.php');
    exit;
  }
  
// La on récupère le cookie que l'on à crée à la connection, voir login.php et fonction.php
// --------------------
$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // La variable est une variable d'environnement qui est dans le fichier .env
$user = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français mais ne me semble pas fonctionner
// --------------------
// Fin de la récupération du cookie
  
// On récupère les données de l'utilisateur pour le mail
$mail_user = $user['edu_mail'];
$id_user = $user['id_user'];

// SQL INSTRUCTIONS
$sql = "SELECT * FROM users WHERE id_user = :id_user AND edu_mail = :edu_mail";
$stmt_sql = $dbh->prepare($sql);
$stmt_sql->execute([
    'id_user' => $id_user,
    'edu_mail' => $mail_user
]);
$sql = $stmt_sql->fetch(PDO::FETCH_ASSOC);
// dd($sql);



echo head("MMI Companion - Vérification du mail");
?>
<body class="body-mail">
    <main class="main-mail">
        <div class="illustration-mail">
            <img src="../assets/img/verif_mail.svg" alt="Illustration diverse">
        </div>
        <div class="title-mail">
            <h1>Vérifie tes mails</h1>
            <p>Un mail vient d’être envoyé à <span style="font-weight: 600;"><?php echo $mail_user; ?></span> pour vérifier votre adresse et activer votre compte.</p>
        </div>
        <div style="height:20px"></div>
        <div class="trait-mail"></div>
        <div style="height:20px"></div>
        <?php 
        if ($sql['active'] == 0) {?>
            <div class="button-accueil">
                <button role="button" class="button_register" onclick="sendMail('<?php echo $sql['edu_mail']?>','<?php echo $sql['verification_code_mail']?>')">Renvoyer un mail</button>
            </div>
        <?php } 
        else { ?>
            <div class="button-accueil">
                <a role="button" href="./login.php" class="button_register">Se connecter</a>
            </div>
        <?php }
        ?>
        
        
        
    </main>
</body>
<script>
function sendMail(x, y) {
    let encodedEmail = encodeURIComponent(x);
    let encodedCode = encodeURIComponent(y);

    fetch('./send_mail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mail_user=${encodedEmail}&verif_code=${encodedCode}`,
    })
    .then(response => {
        if (response.ok) {
            console.log("Mail request sent successfully");
        } else {
            console.error("Mail request failed");
        }
    })
    .catch(error => {
        console.error("An error occurred:", error);
    });
}
</script>
</html>