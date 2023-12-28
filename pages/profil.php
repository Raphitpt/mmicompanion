<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);
setlocale(LC_TIME, 'fr_FR.UTF-8'); // Définit la locale en français

// Recupération du lien de la photo de profil en base de donnée, en local ça ne fonctionnera pas, il faut quel soit en ligne, sauf si l'ajout de la photo et en local
$pp_original = "SELECT pp_link, score FROM users WHERE id_user = :id_user";
$stmt_pp_original = $dbh->prepare($pp_original);
$stmt_pp_original->execute([
    'id_user' => $user['id_user']
]);
$pp_original = $stmt_pp_original->fetch(PDO::FETCH_ASSOC);

$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);



if (str_contains($user_sql['role'], "chef") || str_contains($user_sql['role'], "admin")) {
    $sql_list = "SELECT pname, name, id_user FROM users WHERE edu_group = :edu_group AND role = 'eleve' ORDER BY name ASC";
    $stmt = $dbh->prepare($sql_list);
    $stmt->execute([
        'edu_group' => $user_sql['edu_group']
    ]);
    $list_eleve = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
echo head("MMI Companion | Profil");

?>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" integrity="sha512-zxBiDORGDEAYDdKLuYU9X/JaJo/DPzE42UubfBw9yg8Qvb2YRRIQ8v4KsGHOx2H1/+sdSXyXxLXv5r7tHc9ygg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

<body class="body-all">

    <?php generateBurgerMenuContent($user_sql['role'], 'Profil') ?>

    <main class="main-profil">
        <div class="profil_picture-profil">
            <div class="edit_profil_picture-img" id="edit_profil_picture">
                <i class="fi fi-br-pencil"></i>
            </div>
            <img id="preview" class="profil_picture-img" src="<?php echo $pp_original['pp_link'] ?>" alt="Photo de profil">
            <input id="profil_picture-input" class="profil_picture-input" type="file" name="profil-picture">
        </div>

        <div id="push-permission" class="button_notifications-profil"></div>
        <div style="height:25px"></div>
        <div class="profil_form-profil">
            <div class="profil_form-disabled">
                <div class="profil_form-input_disabled">
                    <label for="name">Prénom</label>
                    <input type="text" name="name" id="name" value="<?php echo ucfirst($user['pname']) ?>" disabled>
                </div>
                <div class="profil_form-input_disabled">
                    <label for="mail">Email</label>
                    <input type="text" name="mail" id="mail" value="<?php echo $user['edu_mail'] ?>" disabled>
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
                if (!empty($_SESSION['success_password'])) { ?>
                    <div class="success_password-profil">
                        <?php echo $_SESSION['success_password'];
                        // On vide la variable de session pour pas laisser le message lors du prochain chargement de page
                        $_SESSION['success_password'] = "";
                        ?>
                    </div>
                <?php } ?>
                <?php if (!empty($_SESSION['error_password'])) { ?>
                    <div class="error_password-profil">
                        <?php echo $_SESSION['error_password'];
                        // On vide la variable de session pour pas laisser le message lors du prochain chargement de page
                        $_SESSION['error_password'] = "";
                        ?>
                    </div>
                <?php } ?>

            </form>
            <?php if (str_contains($user_sql['role'], "chef")) { ?>
                <div class="trait-profil"></div>

                <div class="transmit_role-profil">
                    <h1>Transmettre son rôle à un autre étudiant</h1>
                    <form class="form_transmit_role-profil" method="POST" action="./transfert_role.php">
                        <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                        <select name="passage_role" id="passage_role">
                            <?php
                            foreach ($list_eleve as $list_eleves) { ?>
                                <option value='<?php echo $list_eleves['id_user']; ?>'><?php echo $list_eleves['pname']; ?> <?php echo $list_eleves['name']; ?></option>
                            <?php } ?>

                        </select>
                        <input type="submit" value="Valider" id="validate_change_role">
                    </form>
                </div>
            <?php } ?>
            <?php if (str_contains($user_sql['role'], "admin")){ ?>
            <div class="trait-profil"></div>
            <div class="profil_theme-profil">
                <label for="theme">Choix du thème : </label>
                <div class="profil_theme-switch">
                    <select name="theme" id="SelectTheme">
                        <option value="light">Clair</option>
                        <option value="dark">Sombre</option>
                    </select>


                </div>
                <div class="profil_themes-festifs">
                    <input type="checkbox" id="switchTheme" name="theme" checked disabled/>
                    <p class="label" for="switch">Thèmes festifs</p>
                </div>
            </div>
            <?php } ?>
            <div class="trait-profil"></div>

            <a role="button" href="./logout.php" class="profil_form-button_logout">Se déconnecter</a>
            <p class="profil_form-score">Version 0.9 - Alpha</p>
            <a href="https://mmi-companion.fr/cgu.html" class="profil_cgu">conditions d'utilisation</a>
        </div>
        <div style="height:30px"></div>
        <div id="snow-container"></div>
    </main>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js" integrity="sha512-MgYeYFj8R3S6rvZHiJ1xA9cM/VDGcT4eRRFQwGA7qDP7NHbnWKNmAm28z0LVjOuUqjD0T9JxpDMdVqsZOSHaSA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../assets/js/menu-navigation.js?v=1.1"></script>
     
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="../assets/js/app.js"></script>
    <?php
    if (str_contains($user_sql['role'], 'chef')) { ?>
        <script>
            let form_transmit_role = document.querySelector('.form_transmit_role-profil');
            form_transmit_role.addEventListener("submit", function(event) {
                if (!confirm("Êtes-vous sûr de vouloir transmettre votre rôle à un autre étudiant ? Cela entraînera une déconnexion de votre compte.")) {
                    event.preventDefault(); // Empêche la soumission du formulaire si l'utilisateur clique sur "Annuler"
                }
            });
        </script>
    <?php } ?>
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