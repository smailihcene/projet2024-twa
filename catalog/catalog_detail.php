<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

// Récupérer l'ID du catalogue depuis l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du catalogue manquant.");
}

$catalog_id = intval($_GET['id']); // Assurez-vous que l'ID est un entier

// Vérifier que le catalogue existe
$query_catalog = "SELECT * FROM catalog WHERE id = $catalog_id";
$result_catalog = mysqli_query($con, $query_catalog);

if (!$result_catalog || mysqli_num_rows($result_catalog) == 0) {
    die("Catalogue introuvable.");
}

$catalog = mysqli_fetch_assoc($result_catalog);
$catalog_name = htmlspecialchars($catalog['name']);
$catalog_description = htmlspecialchars($catalog['description']);

// Récupérer les images associées à ce catalogue
$query_images = "
    SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name, 
           i.id AS image_id
    FROM image AS i
    INNER JOIN bank AS b ON i.bankId = b.id
    INNER JOIN CatalogImage AS ci ON ci.imageId = i.id
    WHERE ci.catalogId = $catalog_id
";

$rep = mysqli_query($con, $query_images);
if (!$rep) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Catalogue : <?= $catalog_name ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1>Détails du Catalogue : <?= $catalog_name ?></h1>
        <p><strong>Description :</strong> <?= $catalog_description ?></p>

        <?php if (mysqli_num_rows($rep) > 0): ?>
            <h2 class="mt-4">Images associées</h2>
            <div class="row">
                <?php while ($image = mysqli_fetch_assoc($rep)) { ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?= "../images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" 
                                 class="card-img-top" 
                                 alt="Image">
                            <div class="card-body">
                                <p class="card-text"><strong>Banque :</strong> <?= htmlspecialchars($image['bank_name']); ?></p>
                                <!-- <a href="../afficher_img_detail.php?id=<?= htmlspecialchars($image['image_id']); ?>" class="btn btn-info btn-sm">Voir détails</a> -->
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <p>Aucune image trouvée pour ce catalogue.</p>
        <?php endif; ?>
    </div>
</body>
</html>
