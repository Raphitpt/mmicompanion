<!-- 
        Page d'accueil quand on arrive sur l'application via le QR-code ou bien en cliquant sur le lien
        On peut s'inscrire(register.php) ou se connecter(login.php)
        On peut aussi accéder à la page d'accueil en cliquant sur le logo en haut à gauche
-->

<?php
session_start();
require "../bootstrap.php";



if (isset($_SESSION['post_data'])) {
    // Récupérer les données depuis la session
    $mail_user = $_SESSION['post_data']['mail_user'];
    $activation_code = $_SESSION['post_data']['activation_code'];
    send_activation_email($mail_user, $activation_code);

} else if(isset($_POST['mail_user']) && isset($_POST['id_user']) && isset($_POST['activation_code'])) {
    // Récupérer les données depuis le formulaire
    $mail_user = $_POST['mail_user'];
    $activation_code = $_POST['activation_code'];
    send_activation_email($mail_user, $activation_code);
}

// SQL INSTRUCTIONS
$sql = "SELECT * FROM users WHERE edu_mail = :edu_mail";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    'edu_mail' => $mail_user,
]);
$sql_code = $stmt->fetch(PDO::FETCH_ASSOC);
// dd($sql_code);




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
        if ($sql_code['active'] == 0) {?>
            <div class="button-accueil">
                <button role="button" class="button_register" onclick="sendMail('<?php echo $sql_code['edu_mail']?>','<?php echo $sql_code['verification_code_mail']?>')">Renvoyer un mail</button>
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