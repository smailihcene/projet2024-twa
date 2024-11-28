<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');

// Vérifier si l'ID est fourni dans l'URL
if (isset($_GET['id'])) {
    $image_id = intval($_GET['id']); // Sécuriser l'entrée

    // Requête SQL pour récupérer les informations de l'image correspondant à l'ID
    $query = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name,
                     i.id AS image_id, c.name AS catalog_name
              FROM image AS i
              INNER JOIN bank AS b ON i.bankId = b.id
              INNER JOIN CatalogImage AS ci ON ci.imageId = i.id
              INNER JOIN Catalog AS c ON ci.catalogId = c.id
              WHERE i.id = $image_id;";

    $result = mysqli_query($con, $query);

    // Vérifier si un résultat a été trouvé
    if ($result && $image = mysqli_fetch_assoc($result)) {
        // Stocker les informations de l'image pour les utiliser dans le HTML
    } else {
        die("Image non trouvée ou erreur dans la requête.");
    }

    // Requête SQL pour récupérer les labels associés à l'image
    $query_labels = "SELECT * FROM label WHERE imageId = $image_id";
    $result_labels = mysqli_query($con, $query_labels);

    if (!$result_labels) {
        die("Erreur lors de la récupération des labels : " . mysqli_error($con));
    }
} else {
    die("Aucun ID d'image fourni.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
        }
        .image-container {
            flex: 1;
        }
        .labels-container {
            flex: 2;
            margin-left: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<!-- Affichage de la carte avec les détails de l'image -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <!-- Colonne pour l'image -->
            <div class="image-container">
                <img 
                    src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" 
                    alt="Image de la banque <?= htmlspecialchars($image['bank_name']); ?>" 
                    class="img-fluid img-thumbnail">
            </div>
        </div>
        <div class="col-md-8">
            <!-- Colonne pour les labels -->
            <div class="labels-container">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($image['bank_name']); ?></h5>
                    <p class="card-text">Catalogue : <?= htmlspecialchars($image['catalog_name']); ?></p>
                    
                    <!-- Affichage des labels associés -->
                    <?php if (mysqli_num_rows($result_labels) > 0): ?>
                        <h6>Labels associés :</h6>
                        <ul class="list-group">
                            <?php while ($label = mysqli_fetch_assoc($result_labels)): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($label['name']); ?> :</strong> <?= htmlspecialchars($label['description']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun label associé à cette image.</p>
                    <?php endif; ?>

                    <a href="affichage_img.php" class="btn btn-primary">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
