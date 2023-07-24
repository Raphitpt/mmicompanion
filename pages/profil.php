<?php
session_start();
require "../bootstrap.php";


if (!isset($_COOKIE['jwt'])) {
    header('Location: ./accueil.php');
    exit;
}

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY']; // Remplacez par votre clé secrète
$users = decodeJWT($jwt, $secret_key);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français


$pp_original = "SELECT pp_link FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $users['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['password'])) {
    $password = strip_tags($_POST['password']);
    $old_password = strip_tags($_POST['old_password']);
    $confirm_password = strip_tags($_POST['confirm_password']);
    if ($password != $confirm_password) {
        echo "Les mots de passe ne correspondent pas!";
        exit;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = :password WHERE id_user = :id_user AND password = :old_password";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'password' => $password,
        'old_password' => $old_password,
        'id_user' => $users['id_user']
    ]);
    exit;
}

echo head("Profil");
?>
<body class="body-agenda">

    <header>
        <div class="content_header">
            <div class="content_title-header">
                <div class="burger-header" id="burger-header">
                    <i class="fi fi-br-bars-sort"></i>
                </div>
                <div style="width:20px"></div>
                <h1>Compte</h1>
            </div>
        </div>

        <div class="burger_content-header" id="burger_content-header">
            <div style="height:60px"></div>
            <div class="burger_content_title-header">
                <img src="./../assets/img/mmicompanion.svg" alt="">
                <h1>MMI Companion</h1>
            </div>
            <div class="burger_content_content-header">
                <div class="burger_content_trait_header"></div>
                <a href="./index.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-home"></i>
                        <p>Vue d'ensemble</p>
                    </div>

                </a>
                <a href="./agenda.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-calendar"></i>
                        <p>Agenda</p>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./messages.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-comment-alt"></i>
                        <p>Messages</p>
                    </div>
                </a>
                <a href="./mail.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-envelope"></i>
                        <p>Boite mail</p>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./sante.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-doctor"></i>
                        <p>Mon bien être</p>
                    </div>
                </a>
                <a href="./profil.php">
                    <div class="burger_content_link-header">
                        <i class="fi fi-br-user"></i>
                        <p>Mon profil</p>
                        <div class="select_link-header"></div>
                    </div>
                </a>
                <div class="burger_content_trait_header"></div>
                <a href="./logout.php">
                    <div class="burger_content_link-header logout">
                        <i class="fi fi-br-delete-user logout"></i>
                        <p>Se déconnecter</p>
                    </div>
                </a>
            </div>
        </div>
    </header>
    <main class="main-profil">
        <div class="profile-picture-wrapper">
            <img id="preview" class="profile-picture" src="<?php echo $pp_original['pp_link'] ?>" alt="Photo de profil">
            <input id="profile-picture-input" class="profile-picture-input" type="file" name="profile-picture">
        </div>
        <div class="profile_form">
            <label for="name">Prénom</label>
            <input type="text" name="name" id="name" value="<?php echo ucfirst($users['pname']) ?>" disabled>
            <label for="mail">Email</label>
            <input type="text" name="mail" id="mail" value="<?php echo $users['edu_mail'] ?>" disabled>
            <form method="POST">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="old_password" placeholder="Ancien mot de passe" required>
            <input type="password" name="password" id="password" placeholder="Nouveau mot de passe" required>
            <input type="password" name="password" id="confirm_password" placeholder="Confirmer le nouveau mot de passe" required>
            <input type="submit" value="Modifier le mot de passe">
            </form>
            <a role="button" href="./logout.php" class="button_logout">Se déconnecter</a>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js" integrity="sha512-MgYeYFj8R3S6rvZHiJ1xA9cM/VDGcT4eRRFQwGA7qDP7NHbnWKNmAm28z0LVjOuUqjD0T9JxpDMdVqsZOSHaSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      let input = document.getElementById('profile-picture-input');

      input.addEventListener('change', (event) => {
        let file = event.target.files[0];
        
        new Compressor(file, {
          quality: 0.6, // Réglez la qualité souhaitée ici (0.1 - 1)
          maxWidth: 400, // Définissez la largeur maximale souhaitée ici
          maxHeight: 400, // Définissez la hauteur maximale souhaitée ici
          success(result) {
            let formData = new FormData();
            formData.append('profile-picture', result, result.name);

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-profile-picture.php', true);

            xhr.onload = () => {
              if (xhr.status === 200) {
                // Le téléchargement a réussi, mettez à jour l'image de profil si nécessaire
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                  let preview = document.getElementById('preview');
                  preview.src = response.profilePictureUrl;
                }
              } else {
                // Une erreur s'est produite lors du téléchargement
                console.error('Erreur lors de l\'envoi de l\'image');
              }
            };

            xhr.send(formData);
          },
          error(err) {
            // Gérer les erreurs de compression ici
            console.error('Erreur lors de la compression de l\'image : ', err.message);
          },
        });
      });
    });
    </script>
</body>

</html>