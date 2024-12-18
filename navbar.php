<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="img/logo.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light border-bottom border-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PROJET SOMMETS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="/projet2024-twa/bienvenue.php">
              <i class="bi bi-house-fill"> Accueil</i>
            </a>
          </li>
          <?php if ($_SESSION['role_name'] === 'editor') { ?>
          <li class="nav-item">
            <a class="nav-link" href="/projet2024-twa/catalog/catalog.php">Catalogue</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/projet2024-twa/affichage_img.php">Galerie</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/projet2024-twa/label/label.php">Sommet</a>
          </li>
          <?php } ?>
        </ul>
        <!-- Bouton "Se déconnecter" à droite -->
        <form class="d-flex" action="/projet2024-twa/logout.php" method="post">
            <button class="btn btn-outline-light btn-custom" type="submit">
                <i class="bi bi-box-arrow-right"></i> Se déconnecter
            </button>
        </form>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-keTAXebTbo6G6Ie4UlwwU9q65sn3NtOva4paEDJVDs/6GZRm7dWXK56vylHcjl6k" crossorigin="anonymous"></script>
</body>

</html>
