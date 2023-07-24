<?php
session_start();
require "../bootstrap.php";

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY'];
$users = decodeJWT($jwt, $secret_key);

if (isset($_FILES['profile-picture'])) {
  $uploadedFile = $_FILES['profile-picture'];

  if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
    $fileName = $uploadedFile['name'];
    $fileTempPath = $uploadedFile['tmp_name'];

    // Déplacer le fichier téléchargé vers un emplacement permanent
    $destinationPath = './../uploads/' . $fileName;
    if (move_uploaded_file($fileTempPath, $destinationPath)) {
      $sql = "UPDATE users SET pp_link = :profile_picture WHERE id_user = :id_user";
      $stmt = $dbh->prepare($sql);
      $stmt->execute([
        'profile_picture' => $destinationPath,
        'id_user' => $users['id_user']
      ]);

      // Renvoyer la réponse JSON avec l'URL de la nouvelle image de profil
      $response = [
        'success' => true,
        'profilePictureUrl' => $destinationPath
      ];
      echo json_encode($response);
    } else {
      // Une erreur s'est produite lors du déplacement du fichier
      $response = [
        'success' => false,
        'message' => 'Erreur lors de l\'enregistrement de l\'image de profil.'
      ];
      echo json_encode($response);
    }
  } else {
    // Une erreur s'est produite lors du téléchargement du fichier
    $response = [
      'success' => false,
      'message' => 'Erreur lors du téléchargement de l\'image de profil.'
    ];
    echo json_encode($response);
  }
} else {
  // Aucune image de profil n'a été téléchargée
  $response = [
    'success' => false,
    'message' => 'Aucune image de profil n\'a été téléchargée.'
  ];
  echo json_encode($response);
}
?>