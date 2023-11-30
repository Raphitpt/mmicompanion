<?php
session_start();
require '../../bootstrap.php';

$user = onConnect($dbh);
if ($user['role'] != 'admin' && ($user['edu_mail'] != 'raphael.tiphonet@etu.univ-poitiers.fr' || $user['edu_mail'] != 'arnaud.graciet@etu.univ-poitiers.fr' ) ) {
  header('Location: ./../index.php');
  exit;
}

$eleve_list = "SELECT * FROM users WHERE role LIKE :role OR role LIKE :admin";
$stmt = $dbh->prepare($eleve_list);
$stmt->execute([
  'role' => '%eleve%',
  'admin' => '%admin%'
]);
$eleve_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$prof_list = "SELECT * FROM personnels LEFT JOIN users ON personnels.nom = users.name AND personnels.pnom = users.pname";
$stmt = $dbh->prepare($prof_list);
$stmt->execute();
$prof_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($eleve_list);
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Interface administration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Accueil</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Élèves
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Voir un élève</a></li>
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
    <h1>Accueil de l'interface d'administration</h1>
    <div>
      <h2>Liste des élèves</h2>
      <table class="table" data-toggle="table" data-search="true" data-auto-refresh="true" data-pagination="true">
        <thead style="background-color: #AAAAAA !important;">
          <tr>
            <th scope="col">#</th>
            <th scope="col" data-sortable="true">Prénom</th>
            <th scope="col" data-sortable="true">Nom</th>
            <th scope="col" data-sortable="true">Mail</th>
            <th scope="col" data-sortable="true">Groupe</th>
            <th scope="col">Role</th>
            <th scope="col">Points</th>
            <th scope="col" data-sortable="true">Compte actif</th>
            <th scope="col" data-sortable="true">Dernière connection</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($eleve_list as $eleve) {
            echo '<tr>';
            echo '<th scope="row">' . $eleve['id_user'] . '</th>';
            echo '<td>' . $eleve['pname'] . '</td>';
            echo '<td>' . $eleve['name'] . '</td>';
            echo '<td>' . $eleve['edu_mail'] . '</td>';
            echo '<td>' . $eleve['edu_group'] . '</td>';
            echo '<td>' . $eleve['role'] . '</td>';
            echo '<td>' . $eleve['score'] . '</td>';
            echo '<td>' . $eleve['active'] . '</td>';
            echo '<td>' . $eleve['last_connection'] . '</td>';
            echo '<td><a href="#">Voir</a> <a href="#">Modifier</a> <a href="#">Supprimer</a></td>';
            echo '</tr>';
          }
          
          ?>
        </tbody>
      </table>
    </div>
    <div>
      <h2>Liste du personnel</h2>
      <table class="table" data-toggle="table" data-search="true" data-auto-refresh="true" data-pagination="true">
        <thead style="background-color: #AAAAAA !important;">
          <tr>
            <th scope="col">#</th>
            <th scope="col" data-sortable="true">Prénom</th>
            <th scope="col" data-sortable="true">Nom</th>
            <th scope="col" data-sortable="true">Mail</th>
            <th scope="col" data-sortable="true">Trigramme</th>
            <th scope="col">Lien EDT</th>
            <th scope="col" data-sortable="true">Compte actif</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($prof_list as $prof) {
            echo '<tr>';
            echo '<th scope="row">' . $prof['id_pers'] . '</th>';
            echo '<td>' . $prof['pnom'] . '</td>';
            echo '<td>' . $prof['nom'] . '</td>';
            if ($prof['edu_mail'] == null) {
              echo '<td>Non renseigné</td>';
            } else {
              echo '<td>' . $prof['edu_mail'] . '</td>';
            }
            echo '<td>' . $prof['trigramme'] . '</td>';
            echo '<td>' . substr($prof['edt_link'], 0, 30) . '...</td>';
            if ($prof['active'] == 1) {
              echo '<td>Oui</td>';
            } else {
              echo '<td>Non</td>';
            }
            echo '<td><a href="#">Voir</a> <a href="#">Modifier</a> <a href="#">Supprimer</a></td>';
            echo '</tr>';
          }
          
          ?>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
</body>

</html>