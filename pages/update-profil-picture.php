<?php
session_start();
require "../bootstrap.php";

$user = onConnect($dbh);

if (isset($_FILES['profil-picture'])) {
  $uploadedFile = $_FILES['profil-picture'];

  if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
    $fileName = $uploadedFile['name'];
    $fileTempPath = $uploadedFile['tmp_name'];
    $filename_name = pathinfo( $fileName, PATHINFO_FILENAME );
    $filename_extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $suffix = uniqid();
    $filename_modif = $filename_name . '_' . $suffix . '.' .$filename_extension;

    if (!in_array($uploadedFile['type'], ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
      header('Location: ./profil.php');
      exit();
    }
    
    $destinationPath = './../uploads/' . $filename_modif;

    if (move_uploaded_file($fileTempPath, $destinationPath)) {
      $sql = "UPDATE users SET pp_link = :profil_picture WHERE id_user = :id_user";
      $stmt = $dbh->prepare($sql);
      $stmt->execute([
        'profil_picture' => $destinationPath,
        'id_user' => $user['id_user']
      ]);

      // Renvoyer la réponse JSON avec l'URL de la nouvelle image de profil
      $response = [
        'success' => true,
        'profilPictureUrl' => $destinationPath
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