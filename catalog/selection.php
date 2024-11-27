<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');

// Récupérer les catalogues existants
$query_catalogues = "SELECT id, name FROM catalog";
$catalogues = mysqli_query($con, $query_catalogues);
if (!$catalogues) {
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));
}

// Récupérer les images disponibles
$query_images = "SELECT i.id AS image_id, b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name
                 FROM image AS i 
                 INNER JOIN bank AS b ON i.bankId = b.id";
$images = mysqli_query($con, $query_images);
if (!$images) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}

// Traitement de l'ajout des images au catalogue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catalogId = $_POST['catalog_id']; // Le catalogue sélectionné
    $selected_images = $_POST['images']; // Les images sélectionnées

    if (!empty($catalogId) && !empty($selected_images)) {
        foreach ($selected_images as $imageId => $position) {
            $imageId = intval($imageId); // Assurez-vous que l'imageId est un entier
            $position = intval($position); // Assurez-vous que la position est un entier

            // Insertion de la relation dans la table CatalogImage
            $query_insert = "INSERT INTO CatalogImage (catalogId, imageId, position) VALUES ('$catalogId', '$imageId', '$position')";
            
            if (!mysqli_query($con, $query_insert)) {
                die("Erreur lors de l'ajout de l'image au catalogue : " . mysqli_error($con));
            }
        }

        // Si tout se passe bien, rediriger vers la page de gestion des catalogues
        header("Location: catalog.php");
        exit();
    } else {
        echo "Veuillez sélectionner un catalogue et des images.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Associer des Images à un Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Associer des Images à un Catalogue</h1>

        <!-- Formulaire de sélection du catalogue et des images -->
        <form action="associer_images.php" method="POST">
            <!-- Sélectionner un catalogue -->
            <div class="mb-3">
                <label for="catalog_id" class="form-label">Choisir un Catalogue :</label>
                <select class="form-control" id="catalog_id" name="catalog_id" required>
                    <option value="">Sélectionner un catalogue</option>
                    <?php while ($catalog = mysqli_fetch_assoc($catalogues)) { ?>
                        <option value="<?= $catalog['id']; ?>"><?= htmlspecialchars($catalog['name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Section pour la sélection des images -->
            <h2 class="mt-5 mb-3">Sélectionner des Images</h2>
            <div class="row">
                <?php while ($image = mysqli_fetch_assoc($images)) { ?>
                    <div class="col-md-3 text-center mb-3">
                        <!-- Case à cocher pour sélectionner l'image -->
                        <input type="checkbox" name="images[<?= $image['image_id']; ?>]" value="<?= $image['image_id']; ?>" id="image_<?= $image['image_id']; ?>">
                        <label for="image_<?= $image['image_id']; ?>">
                            <img 
                                src="<?= "../images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" 
                                alt="Image" 
                                class="img-thumbnail selectable-image" 
                                width="100"
                            >
                        </label>
                        <p class="position-label"><?= htmlspecialchars($image['image_name']); ?></p>
                        <!-- Champ de position pour chaque image -->
                        <input type="number" name="positions[<?= $image['image_id']; ?>]" class="form-control" placeholder="Position" min="1" required>
                    </div>
                <?php } ?>
            </div>

            <button type="submit" class="btn btn-success">Associer les Images</button>
            <a href="catalog.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
