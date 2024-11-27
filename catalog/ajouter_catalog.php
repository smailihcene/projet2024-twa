<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

// Traitement de l'ajout du catalogue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $userAccoundId = 1; // ID utilisateur (à ajuster selon votre logique)

    if (!empty($name) && !empty($description)) {
        // Insérer le nouveau catalogue
        $query = "INSERT INTO catalog (userAccoundId, name, description) VALUES ('$userAccoundId', '$name', '$description')";
        if (mysqli_query($con, $query)) {
            $catalogId = mysqli_insert_id($con); 

            if (!empty($_POST['images']) && is_array($_POST['images'])) {
                foreach ($_POST['images'] as $imageId => $position) {
                    $imageId = intval($imageId);
                    $position = intval($position);

                    $queryInsertImage = "INSERT INTO CatalogImage (catalogId, imageId, position) VALUES ('$catalogId', '$imageId', '$position')";
                    mysqli_query($con, $queryInsertImage);
                }
            }

            header("Location: catalog.php");
            exit();
        } else {
            echo "Erreur lors de l'ajout : " . mysqli_error($con);
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

// Charger les images disponibles pour l'affichage
$query_images = "SELECT i.id AS image_id, b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name
                 FROM image AS i 
                 INNER JOIN bank AS b ON i.bankId = b.id;";
$images = mysqli_query($con, $query_images);
if (!$images) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Ajouter un Catalogue</title>

</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Ajouter un Catalogue</h1>

        <!-- Formulaire pour le catalogue -->
        <form action="ajouter_catalog.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <!-- Section pour la sélection des images -->
            <h2 class="mt-5 mb-3">Sélectionner des Images</h2>
            <div class="row">
                <?php while ($image = mysqli_fetch_assoc($images)) { ?>
                    <div class="col-md-3 text-center mb-3">
                        <img 
                            src="<?= "../images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" 
                            alt="Image" 
                            class="img-thumbnail selectable-image" 
                            data-id="<?= $image['image_id']; ?>">
                        <input type="hidden" name="images[<?= $image['image_id']; ?>]" class="image-order" disabled>
                        <p class="position-label"></p>
                    </div>
                <?php } ?>
            </div>

            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="catalog.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
