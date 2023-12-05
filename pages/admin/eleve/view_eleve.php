<?php
session_start();
require '../../../bootstrap.php';

$user = onConnect($dbh);
if (str_contains($user['role'],'admin') == false && ($user['edu_mail'] != 'raphael.tiphonet@etu.univ-poitiers.fr' || $user['edu_mail'] != 'arnaud.graciet@etu.univ-poitiers.fr' ) ) {
  header('Location: ./../index.php');
  exit;
}

if(isset($_GET['id']) && !empty($_GET['id'])){
  $id_user = $_GET['id'];
  $user_sql = "SELECT * FROM users WHERE id_user = :id_user";
  $stmt = $dbh->prepare($user_sql);
  $stmt->execute([
    'id_user' => $id_user
  ]);
  $user_sql = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($user_sql);
}

?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Interface administration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="./../administration.php">Accueil</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-current="true" aria-expanded="false">
              Élèves
            </a>
            <ul class="dropdown-menu"  aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item active" href="#">Voir un élève</a></li>
              <li><a class="dropdown-item" href="#">Modifier un élève</a></li>
              <li><a class="dropdown-item" href="#">Supprimer un élève</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Voir tous les élèves</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Professeurs
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Voir un professeur</a></li>
              <li><a class="dropdown-item" href="#">Ajouter un professeur</a></li>
              <li><a class="dropdown-item" href="#">Modifer un professeur</a></li>
              <li><a class="dropdown-item" href="#">Supprimer un professeur</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Voir tous les professeurs</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Logs
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Logs PHP</a></li>
              <li><a class="dropdown-item" href="#">Erreurs PHP</a></li>
              <li><a class="dropdown-item" href="#">Erreurs CURL</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Voir l'historique des logs</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <?php 
    if(isset($_GET['id']) && !empty($_GET['id'])){
      echo '<h1>Élève n°'.$user_sql['id_user'].'</h1>';
      echo '<p>Nom : '.$user_sql['name'].'</p>';
      echo '<p>Prénom : '.$user_sql['pname'].'</p>';
      echo '<p>Mail : '.$user_sql['edu_mail'].'</p>';
      echo '<p>Classe : '.$user_sql['edu_group'].'</p>';
      echo '<p>Role : '.$user_sql['role'].'</p>';
      echo '<img src="./../.'.$user_sql['pp_link'].'" alt="avatar" style="width: 25vw; height: auto;">';
    }else{
        echo '<h1>Trouver un élève</h1>';
        echo '<div class="container_input-agenda_add">';
        echo '<i class="fi fi-br-graduation-cap"></i>';
        echo '<input type="text" name="school_subject" class="input_select-agenda_add input-agenda_add" id="search_subject" required>';
        echo '<input type="hidden" name="subject" id="subject" required>';
        echo '</div>';
        }
    ?>


  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>