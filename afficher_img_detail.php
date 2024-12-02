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
    $query_labels = "SELECT name, description, points FROM label WHERE imageId = $image_id";
    $result_labels = mysqli_query($con, $query_labels);

    if (!$result_labels) {
        die("Erreur lors de la récupération des labels : " . mysqli_error($con));
    }

    $labels = [];
    while ($label = mysqli_fetch_assoc($result_labels)) {
        $decoded_points = json_decode(stripslashes($label['points']), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erreur de décodage JSON pour les points : " . json_last_error_msg());
            error_log("Valeur brute : " . $label['points']);
            $decoded_points = [];
        }
        $labels[] = [
            'name' => $label['name'],
            'description' => $label['description'],
            'points' => $decoded_points,
        ];
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
            <div class="image-container position-relative">
                <img
                        src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>"
                        alt="Image de la banque <?= htmlspecialchars($image['bank_name']); ?>"
                        class="img-fluid img-thumbnail"
                        id="image">

                <!-- Canevas pour dessiner les points -->
                <canvas id="pointsCanvas" class="position-absolute top-0 start-0"></canvas>
            </div>
        </div>
        <!-- Colonne pour les labels -->
        <div class="col-md-8">
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
                                    <strong><?= htmlspecialchars($label['name']); ?>
                                        :</strong> <?= htmlspecialchars($label['description']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun label associé à cette image.</p>
                    <?php endif; ?>

                    <div id="label-info" class="mt-4">
                        <!-- Informations sur le label sélectionné -->
                    </div>

                    <a href="/projet2024-twa/bienvenue.php" class="btn btn-primary">Retour</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const image = document.getElementById("image");
        const canvas = document.getElementById("pointsCanvas");
        const ctx = canvas.getContext("2d");

        // Variables pour les dimensions naturelles de l'image
        let imgWidth, imgHeight;

        // Fonction pour initialiser et dessiner les polygones après le chargement de l'image
        function initializeCanvas() {
            // Ajuster la taille du canvas pour qu'elle corresponde à celle de l'image
            canvas.width = image.offsetWidth;
            canvas.height = image.offsetHeight;

            // Récupérer les dimensions naturelles de l'image
            imgWidth = image.naturalWidth;
            imgHeight = image.naturalHeight;

            // Dessiner les polygones après le redimensionnement
            drawPolygons();
        }

        // Données des labels récupérées depuis PHP
        const labels = <?= json_encode($labels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;

        // Fonction pour dessiner les polygones à l'échelle correcte
        function drawPolygons() {
            const scaleX = canvas.width / imgWidth;
            const scaleY = canvas.height / imgHeight;

            labels.forEach(label => {
                if (Array.isArray(label.points) && label.points.length > 2) {
                    ctx.beginPath();

                    // Appliquer la mise à l'échelle pour chaque point du polygone
                    ctx.moveTo(label.points[0].x * scaleX, label.points[0].y * scaleY);

                    label.points.forEach(point => {
                        ctx.lineTo(point.x * scaleX, point.y * scaleY);
                    });

                    ctx.closePath();
                    ctx.fillStyle = "rgba(255, 0, 0, 0.4)";
                    ctx.fill();
                    ctx.strokeStyle = "red";
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    // Ajouter un attribut de label pour chaque polygone dessiné
                    label.polygonPath = label.points.map(point => ({
                        x: point.x * scaleX,
                        y: point.y * scaleY
                    }));
                } else {
                    console.warn(`Points invalides ou insuffisants pour le label : ${label.name}`);
                }
            });
        }

        // Fonction pour vérifier si un point est à l'intérieur du polygone
        function isPointInPolygon(point, polygon) {
            let isInside = false;
            let x = point.x, y = point.y;
            let polygonLength = polygon.length;
            let j = polygonLength - 1;

            for (let i = 0; i < polygonLength; i++) {
                let xi = polygon[i].x, yi = polygon[i].y;
                let xj = polygon[j].x, yj = polygon[j].y;

                let intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                if (intersect) isInside = !isInside;

                j = i;
            }
            return isInside;
        }

        // Gestion de l'événement de clic sur le canevas
        canvas.addEventListener("click", function (event) {
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;

            labels.forEach(label => {
                if (label.polygonPath && isPointInPolygon({ x: mouseX, y: mouseY }, label.polygonPath)) {
                    // Afficher les informations du label dans la colonne de droite
                    const labelInfoDiv = document.getElementById("label-info");
                    labelInfoDiv.innerHTML = `
                <h5>${label.name}</h5>
                <p><strong>Description :</strong> ${label.description}</p>
            `;
                }
            });
        });

        // Changer le curseur en "pointer" quand la souris survole un polygone
        canvas.addEventListener("mousemove", function (event) {
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;
            let isOverPolygon = false;

            labels.forEach(label => {
                if (label.polygonPath && isPointInPolygon({ x: mouseX, y: mouseY }, label.polygonPath)) {
                    isOverPolygon = true;
                }
            });

            // Si la souris survole un polygone, changer le curseur
            canvas.style.cursor = isOverPolygon ? "pointer" : "default";
        });

        // Initialisation après le chargement de l'image
        image.onload = initializeCanvas;

        // Si l'image est déjà chargée (au cas où l'événement onload se produirait trop tard)
        if (image.complete) {
            initializeCanvas();
        } else {
            image.onload = initializeCanvas;
        }
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
