<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');

// Requête SQL pour récupérer les données
$query_path = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name, 
                i.id AS image_id, c.name AS catalog_name, ci.catalogId AS catalog_id
                FROM image AS i
                INNER JOIN bank AS b ON i.bankId = b.id
                LEFT JOIN CatalogImage AS ci ON ci.imageId = i.id
                LEFT JOIN Catalog AS c ON ci.catalogId = c.id
                ORDER BY b.name";

$rep = mysqli_query($con, $query_path);
if (!$rep) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}

// Organiser les données par bank_name
$banks = [];
while ($row = mysqli_fetch_assoc($rep)) {
    $image_id = $row['image_id'];
    if (!isset($banks[$row['bank_name']][$image_id])) {
        $banks[$row['bank_name']][$image_id] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Liste des Images par Banque</h1>

    <?php if (empty($banks)) { ?>
        <p class="text-center">Aucune image trouvée.</p>
    <?php } else { ?>
        <?php foreach ($banks as $bank_name => $images) { ?>
            <div class="bank-section">
                <div class="bank-header">
                    <h2 class="h5"><?= htmlspecialchars($bank_name); ?></h2>
                </div>
                <div class="row mt-3">
                    <?php foreach ($images as $image) { ?>
                        <div class="col-md-3 col-sm-4 col-6 text-center mb-4">
                            <div class="card">
                                <!-- Lien vers le détail de l'image -->
                                <a href="afficher_img_detail.php?id=<?=($image['image_id'])?>;">
                                    <img src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>"
                                         alt="<?= htmlspecialchars($image['image_name']); ?>"
                                         class="img-thumbnail card-img-top border border-primary border-2">
                                </a>
                                <!-- Détails sous l'image -->
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($image['image_name']); ?></p>

                                    <?php if (empty($image['catalog_id'])) { ?>
                                        <!-- Si l'image n'a pas de catalogue, afficher le bouton pour en créer un -->
                                        <a href="/projet2024-twa/catalog/ajouter_catalog.php"
                                           class="btn btn-warning btn-sm">
                                            Créer un Catalogue
                                        </a>
                                    <?php } else { ?>
                                        <!-- Si l'image a un catalogue, afficher le bouton d'ajout d'étiquette -->
                                        <a href="label/ajouter_label.php?image_id=<?= htmlspecialchars($image['image_id']); ?>&catalog_id=<?= htmlspecialchars($image['catalog_id']); ?>"
                                           class="btn btn-custom btn-sm">
                                            Ajouter Étiquette
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
</body>
</html>
