<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

// Traitement de l'ajout du catalogue
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //vérifie si la méthode de reuqête est POST 
    $name = mysqli_real_escape_string($con, $_POST['name']); //sécurise l'entrée pour le champ 'name'
    $description = mysqli_real_escape_string($con, $_POST['description']); //sécurise l'entrée pour le champ 'description'
    $userAccoundId = 1; // ID utilisateur (à ajuster selon votre logique)

    if (!empty($name) && !empty($description)) { //on vérifie si les champs ne sont pas vide 
        // requête pour insérer le nouveau catalogue
        $query = "INSERT INTO catalog (userAccoundId, name, description) VALUES ('$userAccoundId', '$name', '$description')";
        if (mysqli_query($con, $query)) {  //execution de la requête 
            $catalogId = mysqli_insert_id($con); 

            if (!empty($_POST['images']) && is_array($_POST['images'])) {  //on vérfie que l'image est bien selectionnée 
                foreach ($_POST['images'] as $imageId => $position) {  //on parcoure les images  
                    $imageId = intval($imageId); //on vérifie que l'ID de l'image est un entier  
                    $position = intval($position);  //on vérifie que la position de l'image est un entier 

                    //donc on insère les images avce leur posiiton dan sla table 'CatalogImage'
                    $queryInsertImage = "INSERT INTO CatalogImage (catalogId, imageId, position) VALUES ('$catalogId', '$imageId', '$position')";
                    mysqli_query($con, $queryInsertImage);  //execution de la requête 
                }
            }

            header("Location: catalog.php");  //après le succès redirection vers la page 'catalog.php'
            exit(); //puis on arrete l'exécution du script 
        } else { //message d'erreur en cas de problème 
            echo "Erreur lors de l'ajout : " . mysqli_error($con);
        }
    } else {  //si il ya des champs qui sont vides 
        echo "Veuillez remplir tous les champs.";
    }
}

// Charger les images disponibles pour l'affichage
$query_images = "SELECT i.id AS image_id, b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name
                 FROM image AS i 
                 INNER JOIN bank AS b ON i.bankId = b.id;"; //on récupère toutes les images disponibles dans la base de donnée 
$images = mysqli_query($con, $query_images);  //exécution de la requête 
if (!$images) { //message d'erreur s'il ya un problème lors de  la récupération de l'image 
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
    <?php include '../navbar.php'; ?>  <!-- on ajoute la navbar -->

    <div class="container mt-4">
        <h1 class="mb-4">Ajouter un Catalogue</h1>

        <!-- Formulaire pour le catalogue -->
        <form action="ajouter_catalog.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom :</label>  <!-- label pour le nom -->
                <input type="text" class="form-control" id="name" name="name" required> <!--champ pour la saisie pour le nom -->
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label> <!--Label pour la descirption -->
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea> <!-- champe pour la saisie de la description -->
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
                        <input type="hidden" name="images[<?= $image['image_id']; ?>]" class="image-order" disabled>  <!-- champ pour la position de l'image qui est caché-->
                        <p class="position-label"></p>  <!--étiquette pour afficher la position de l'image -->
                    </div>
                <?php } ?>
            </div>

            <button type="submit" class="btn btn-success">Ajouter</button>  <!-- boutonpour soumettre le formulaire -->
            <a href="catalog.php" class="btn btn-secondary">Annuler</a>  <!-- lien vers la page catalog.php quand on veut annuler -->
        </form>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- inclusion du jQuery -->
    <script src="../js/script.js"></script>  <!-- lien vers un script JS -->
</body>
</html>
