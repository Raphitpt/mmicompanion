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

if (isset($_POST['profile-picture'])) {
        // Récupérer les informations du fichier téléchargé
    $uploadedFile = $_FILES['profile-picture'];
    $fileName = $uploadedFile['name'];
    $fileTempPath = $uploadedFile['tmp_name'];

    // Déplacer le fichier téléchargé vers un emplacement permanent
    $destinationPath = 'uploads/' . $fileName;
    move_uploaded_file($fileTempPath, $destinationPath);

    $sql = "UPDATE users SET pp_link = :profile_picture WHERE id_user = :id_user";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'profile_picture' => $destinationPath,
        'id_user' => $users['id_user']
    ]);
    header('Location: ./profil.php');
    exit();
}

$pp_original = "SELECT pp_link FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $users['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);

echo head("Profil");
?>
  <style>
    .profile-picture-wrapper {
      width: 150px;
      height: 150px;
      position: relative;
      overflow: hidden;
      border-radius: 50%;
    }

    .profile-picture {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-picture-input {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }
  </style>
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
            </div>
        </div>
    </header>
    <main class="main-profil">
    <form action="" method="post" enctype="multipart/form-data">
    <div class="profile-picture-wrapper">
      <img id="preview" class="profile-picture" src="<?php echo $pp_original ?>" alt="Photo de profil">
      <input id="profile-picture-input" class="profile-picture-input" type="file" name="profile-picture" onchange="updatePreview(event)">
    </div>
    <input type="submit" value="Télécharger">
  </form>

  </div>

    </main>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>
        function updatePreview(event) {
      let input = event.target;
      let reader = new FileReader();

      reader.onload = function() {
        let preview = document.getElementById('preview');
        preview.src = reader.result;
      };

      reader.readAsDataURL(input.files[0]);
    }
    </script>
</body>
</html>
