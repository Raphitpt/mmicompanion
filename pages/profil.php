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

// Recupération du lien de la photo de profil en base de donnée, en local ça ne fonctionnera pas, il faut quel soit en ligne, sauf si l'ajout de la photo et en local
$pp_original = "SELECT pp_link, score FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $users['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);

echo head("MMI Companion - Profil");

?>
<body class="body-all">

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

        <?php generateBurgerMenuContent() ?>
    </header>
    
    <main class="main-profil">
        <div class="profil_picture-profil">
            <div class="edit_profil_picture-img" id="edit_profil_picture">
                <i class="fi fi-br-pencil"></i>
            </div>
            <img id="preview" class="profil_picture-img" src="<?php echo $pp_original['pp_link'] ?>" alt="Photo de profil">
            <input id="profil_picture-input" class="profil_picture-input" type="file" name="profil-picture">
        </div>
        <div class="profil_form-profil">
            <div class="profil_form-disabled">
                <div class="profil_form-input_disabled">
                    <label for="name">Prénom</label>
                    <input type="text" name="name" id="name" value="<?php echo ucfirst($users['pname']) ?>" disabled>
                </div>
                <div class="profil_form-input_disabled">
                    <label for="mail">Email</label>
                    <input type="text" name="mail" id="mail" value="<?php echo $users['edu_mail'] ?>" disabled>
                </div>
            </div>

            <div class="trait-profil"></div>
            
            <form method="POST" class="profil_form-password" action="./update_password.php">
                <div class="profil_form-input_password">
                    <label for="password">Modifier mon mot de passe :</label>
                    <input type="password" name="old_password" id="old_password" placeholder="Ancien mot de passe" required>
                    <input type="password" name="password" id="password" placeholder="Nouveau mot de passe" required>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer le nouveau mot de passe" required>
                </div>
                <input type="submit" value="Modifier">
                <!-- On vérifie si le contenu du message est un succès ou une erreur afin d'adapter la couleur du message en CSS -->
                <?php 
                if (!empty($_SESSION['success_password'])) {?>
                    <div class="success_password-profil">
                        <?php echo $_SESSION['success_password']; 
                        // On vide la variable de session pour pas laisser le message lors du prochain chargement de page
                        $_SESSION['success_password']=""; 
                        ?>
                    </div>  
                <?php } ?>
                <?php if (!empty($_SESSION['error_password'])) {?>
                    <div class="error_password-profil">
                        <?php echo $_SESSION['error_password']; 
                        // On vide la variable de session pour pas laisser le message lors du prochain chargement de page
                        $_SESSION['error_password']=""; 
                        ?>
                    </div>  
                <?php } ?>
                      
            </form>

            <div class="trait-profil"></div>

            <div class="transmit_role-profil">
                <h1>Transmettre son rôle à un autre étudiant</h1>
                <form class="form_transmit_role-profil">
                    <select name="" id="">
                        <?php 
                        foreach ($variable as $key => $value) {
                            # code...
                        }
                        ?>
                    </select>
                    <input type="submit" value="valider">
                </form>
            </div>

            <div class="trait-profil"></div>

            <a role="button" href="./logout.php" class="profil_form-button_logout">Se déconnecter</a>
        </div>
        <div style="height:30px"></div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js" integrity="sha512-MgYeYFj8R3S6rvZHiJ1xA9cM/VDGcT4eRRFQwGA7qDP7NHbnWKNmAm28z0LVjOuUqjD0T9JxpDMdVqsZOSHaSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/menu-navigation.js"></script>
    <script>

    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_profil-header');
    select_background_profil.classList.add('select_link-header');
        
    document.addEventListener('DOMContentLoaded', () => {
      let input = document.querySelector('#profil_picture-input');
      let editButton = document.querySelector('#edit_profil_picture');


      // Ajoutez un gestionnaire d'événements clic à l'élément editButton
        editButton.addEventListener('click', () => {
            // Déclenchez le clic sur le champ de fichier lorsque l'élément editButton est cliqué
            input.click();
        });

      input.addEventListener('change', (event) => {
        let file = event.target.files[0];
        // On compresse la photo en js en utilisant la bibliothèque CompressorJS, parce que bon, on a pas la fibre non plus
        new Compressor(file, {
          quality: 0.6, // Réglez la qualité souhaitée ici (0.1 - 1)
          maxWidth: 400, // Définissez la largeur maximale souhaitée ici
          maxHeight: 400, // Définissez la hauteur maximale souhaitée ici
          success(result) {
            let formData = new FormData();
            formData.append('profil-picture', result, result.name);

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-profil-picture.php', true);

            xhr.onload = () => {
              if (xhr.status === 200) {
                // Le téléchargement a réussi, mettez à jour l'image de profil si nécessaire
                let response = JSON.parse(xhr.responseText);
                if (response.success) {
                  let preview = document.querySelector('#preview');
                  preview.src = response.profilPictureUrl;
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