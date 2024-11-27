<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('db/connexion.php');
$query_path = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name
               FROM image AS i 
               INNER JOIN bank AS b ON i.bankId = b.id;";
$rep = mysqli_query($con, $query_path);
if (!$rep) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue d'images</title>
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .gallery img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .gallery img.selected {
            border-color: blue;
        }
        .selected-gallery {
            margin-top: 20px;
        }
        .selected-gallery img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h1>Catalogue d'images</h1>
    <div class="gallery" id="gallery"></div>

    <button id="viewSelection">Voir la sélection</button>

    <h2>Images sélectionnées :</h2>
    <div class="selected-gallery" id="selectedGallery"></div>

    <script>
        const images = [
            'images/image1.jpg',
            'images/feuilles.jpg',
            'images/image2.jpg'
            //'images/image3.jpg'
        ]; // Liste des images

        const gallery = document.getElementById('gallery');
        const selectedGallery = document.getElementById('selectedGallery');
        const selectedImages = new Set();

        // Afficher les images dans la galerie principale
        images.forEach(image => {
            const img = document.createElement('img');
            img.src = image;
            img.alt = 'Image';
            img.addEventListener('click', () => toggleSelection(img, image));
            gallery.appendChild(img);
        });

        // Ajouter ou retirer une image de la sélection
        function toggleSelection(img, image) {
            if (selectedImages.has(image)) {
                selectedImages.delete(image);
                img.classList.remove('selected');
            } else {
                selectedImages.add(image);
                img.classList.add('selected');
            }
        }

        // Afficher les images sélectionnées
        document.getElementById('viewSelection').addEventListener('click', () => {
            selectedGallery.innerHTML = ''; // Nettoyer la galerie sélectionnée
            if (selectedImages.size > 0) {
                selectedImages.forEach(image => {
                    const img = document.createElement('img');
                    img.src = image;
                    img.alt = 'Image sélectionnée';
                    selectedGallery.appendChild(img);
                });
            } else {
                alert('Aucune image sélectionnée.');
            }
        });
    </script>
</body>
</html>
