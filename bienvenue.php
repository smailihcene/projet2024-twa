<?php
session_start(); //on démarre une session

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');

// Récupérer les images du catalogue
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Bienvenue</title>
    <style>
        .image-container {
            text-align: center;
            margin-top: 20px;
        }
        .image-display {
            width: 500px;
            height: 400px;
            object-fit: contain; /* Ajuste l'image sans déformation */
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <div class="text-center">
            <h1>Bienvenue, <?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?> !</h1>
        </div>

        <hr>

        <?php if ($_SESSION['role_name'] === 'non-editor') { ?>
            <!-- Affichage des images -->
        <div class="image-container">
            <?php if (empty($images)): ?>
                <p class="text-danger">Aucune image n'est disponible.</p>
            <?php else: ?>
                <img id="catalogImage" class="image-display" 
                     src="./images/<?= htmlspecialchars($images[0]['bank_dir'] . '/' . $images[0]['image_name']); ?>" 
                     alt="Image du catalogue">
                <p id="imageInfo" class="mt-3">
                    Banque : <strong><?= htmlspecialchars($images[0]['bank_name']); ?></strong>
                </p>
                <button id="prevButton" class="btn btn-secondary" disabled>Précédent</button>
                <button id="nextButton" class="btn btn-primary">Suivant</button>
            <?php endif; ?>
        </div>
        <?php } ?>

    </div>

    <script>
        // Images récupérées depuis le PHP
        const images = <?= json_encode($images); ?>;
        let currentIndex = 0;

        // Récupération des éléments HTML
        const catalogImage = document.getElementById('catalogImage');
        const imageInfo = document.getElementById('imageInfo');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');

        // Fonction pour mettre à jour l'image
        function updateImage(index) {
            const image = images[index];
            catalogImage.src = `./images/${image.bank_dir}/${image.image_name}`;
            imageInfo.innerHTML = `Banque : <strong>${image.bank_name}</strong>`;
            prevButton.disabled = index === 0;
            nextButton.disabled = index === images.length - 1;
        }

        // Gestion des clics sur les boutons
        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateImage(currentIndex);
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentIndex < images.length - 1) {
                currentIndex++;
                updateImage(currentIndex);
            }
        });
    </script>
</body>

</html>
