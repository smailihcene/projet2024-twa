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
$query_path = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name,
 i.id AS image_id, c.name AS catalog_name, ci.catalogId AS catalog_id
            FROM image AS i
            INNER JOIN bank AS b ON i.bankId = b.id
            INNER JOIN CatalogImage AS ci ON ci.imageId = i.id
            INNER JOIN Catalog AS c ON ci.catalogId = c.id;";

$rep = mysqli_query($con, $query_path);
if (!$rep) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Liste des Images</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nom Catalogue</th>
                    <th>Nom de la Banque</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($image = mysqli_fetch_assoc($rep)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($image['catalog_name']); ?></td>
                        <td><?= htmlspecialchars($image['bank_name']); ?></td>
                        <td>
                            <a href="afficher_img_detail.php?id=<?=($image['image_id'])?>;" class="">
                                <img src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" alt="" class="img-thumbnail" width="100">
                            </a>
                        </td>
                        <td>
                            <button 
                                class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#labelModal" 
                                data-image-src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" 
                                data-image-id="<?= htmlspecialchars($image['image_id']); ?>"
                                data-catalog-id="<?= htmlspecialchars($image['catalog_id']); ?>">Etiquette 
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="labelModal" tabindex="-1" aria-labelledby="labelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalLabel">Outil d'Étiquetage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <canvas id="imageCanvas" width="600" height="400"></canvas>
                    <form id="labelForm" method="POST" action="enregistrement_label.php">
                        <label>Nom de l'Étiquette :</label>
                        <input type="text" name="name" required><br>

                        <label>Description Courte :</label>
                        <textarea name="description" required></textarea><br>

                        <label>Description HTML :</label>
                        <textarea name="html"></textarea><br>

                        <!-- Coordonnées du polygone -->
                        <input type="hidden" name="points" id="polygonPoints">
                        <input type="hidden" name="image_id" id="imageId">
                        <input type="hidden" name="catalog_id" id="catalogId">

                        <button type="button" id="saveBtn" class="btn btn-success">Sauvegarder</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('imageCanvas');
        const ctx = canvas.getContext('2d');
        const points = [];
        let isDrawing = false;
        let img = new Image();

        // Préparer le canvas avec une image lorsque le modal est ouvert
        const labelModal = document.getElementById('labelModal');
        labelModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Le bouton qui a déclenché l'ouverture
            const imageSrc = button.getAttribute('data-image-src');
            const imageId = button.getAttribute('data-image-id');
            const catalogId = button.getAttribute('data-catalog-id'); // Récupérer l'ID du catalogue
        
            // Charger l'image sur le canvas
            img.src = imageSrc;
            img.onload = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            };
        
            // Réinitialiser les points
            points.length = 0;
        
            // Ajouter les IDs dans le formulaire
            document.getElementById('imageId').value = imageId;
            document.getElementById('catalogId').value = catalogId; // Ajouter l'ID du catalogue dans le formulaire
        });

        // Dessiner des points et des lignes sur le canvas
        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            points.push({ x, y });
            drawPolygon();
        });

        function drawPolygon() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            if (points.length > 0) {
                ctx.beginPath();
                ctx.moveTo(points[0].x, points[0].y);
                points.forEach((point, index) => {
                    ctx.lineTo(point.x, point.y);
                });
                ctx.closePath();
                ctx.strokeStyle = 'red';
                ctx.stroke();

                // Dessiner les points
                points.forEach((point) => {
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, 3, 0, 2 * Math.PI);
                    ctx.fillStyle = 'blue';
                    ctx.fill();
                });
            }
        }

        // Sauvegarder les données
        document.getElementById('saveBtn').addEventListener('click', () => {
            // Pour tester 
            // console.log("Image ID: ", document.getElementById('imageId').value);
            // console.log("Catalog ID: ", document.getElementById('catalog_id_ci'));
            // Convertir les points en JSON
            const pointsJSON = JSON.stringify(points);
            document.getElementById('polygonPoints').value = pointsJSON;

            // Soumettre le formulaire
            document.getElementById('labelForm').submit();
        });
    </script>
</body>
</html>
