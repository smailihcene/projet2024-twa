<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');

// Récupérer les images
$query_images = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name 
                 FROM image AS i 
                 INNER JOIN bank AS b ON i.bankId = b.id";
$result_images = mysqli_query($con, $query_images);

if (!$result_images) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}

$images = [];
while ($row = mysqli_fetch_assoc($result_images)) {
    $images[] = [
        'bank_dir' => $row['bank_dir'],
        'image_name' => $row['image_name'],
        'bank_name' => $row['bank_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie d'Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Galerie d'images</h1>
        
        <?php if (empty($images)): ?>
            <p class="text-danger text-center">Aucune image n'est disponible.</p>
        <?php else: ?>
            <!-- Galerie avec navigation -->
            <div class="d-flex justify-content-center align-items-center">
                <button id="prevButton" class="btn btn-primary me-3">❮</button>
                <div class="image-container">
                    <img id="catalogImage" class="image-display" 
                         src="./images/<?= htmlspecialchars($images[0]['bank_dir'] . '/' . $images[0]['image_name']); ?>" 
                         alt="<?= htmlspecialchars($images[0]['image_name']); ?>">
                </div>
                <button id="nextButton" class="btn btn-primary ms-3">❯</button>
            </div>
            <p id="imageInfo" class="mt-3 text-center">
                Banque : <strong><?= htmlspecialchars($images[0]['bank_name']); ?></strong>
            </p>
        <?php endif; ?>
    </div>

    <script>
        // Récupérer les données des images depuis le PHP
        const images = JSON.parse('<?= json_encode($images); ?>');
        let currentIndex = 0;

        // Sélectionner les éléments HTML
        const catalogImage = document.getElementById("catalogImage");
        const imageInfo = document.getElementById("imageInfo");
        const prevButton = document.getElementById("prevButton");
        const nextButton = document.getElementById("nextButton");

        // Fonction pour mettre à jour l'image
        function updateImage(index) {
            const image = images[index];
            catalogImage.src = `./images/${image.bank_dir}/${image.image_name}`;
            imageInfo.innerHTML = `Banque : <strong>${image.bank_name}</strong>`;
        }

        // Gestion du bouton "Précédent"
        prevButton.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length; // Boucle vers la dernière image
            updateImage(currentIndex);
        });

        // Gestion du bouton "Suivant"
        nextButton.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % images.length; // Boucle vers la première image
            updateImage(currentIndex);
        });
    </script>
</body>

</html>
