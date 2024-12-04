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
           LEFT JOIN CatalogImage AS ci ON ci.imageId = i.id
           LEFT JOIN Catalog AS c ON ci.catalogId = c.id
           WHERE i.id = $image_id";


    $result = mysqli_query($con, $query);

    // Vérifier si un résultat a été trouvé
    if ($result && $image = mysqli_fetch_assoc($result)) {
        // Stocker les informations de l'image pour les utiliser dans le HTML
    } else {
        die("Image non trouvée ou erreur dans la requête.");
    }

    // Requête SQL pour récupérer les labels associés à l'image
    $query_labels = "SELECT name, description, points, html FROM label WHERE imageId = $image_id";
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
            'html' => $label['html'],
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
        .labels-container {
            flex: 2;
            margin-left: 20px;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<!-- Affichage de la carte avec les détails de l'image -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Colonne pour l'image -->
            <div class="image-container">
                <img
                        src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>"
                        alt="Image de la banque <?= htmlspecialchars($image['bank_name']); ?>"
                        class=""
                        id="image">
                <!-- Canevas pour dessiner les points -->
                <canvas id="pointsCanvas"></canvas>
            </div>
        </div>
        <!-- Colonne pour les labels -->
        <div class="col-md-4">
            <div class="labels-container">
                <div class="card-body">
                    <h5 class="card-title">Banque : <?= htmlspecialchars($image['bank_name']); ?></h5>
                    <p class="card-text">Catalogue : <?= htmlspecialchars($image['catalog_name']); ?></p>

                    <!-- Affichage des labels associés -->
                    <?php if (!empty($labels)): ?>
                        <h6><u>Labels associés :</u></h6>
                        <p class="text-danger">Cliquez sur le polygone de votre choix pour afficher ses détails.</p>
                        <ul class="list-group">
                            <?php foreach ($labels as $label): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($label['name']); ?> :</strong>
                                    <?= htmlspecialchars($label['description']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun label associé à cette image.</p>
                    <?php endif; ?>

                    <div id="label-info" class="mt-4">
                        <!-- Informations sur le label sélectionné -->
                    </div>

                    <a href="/projet2024-twa/bienvenue.php" class="btn btn-custom btn-sm"">Retour</a>
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

        let imgWidth, imgHeight;
        let hoveredPolygonIndex = -1;

        function initializeCanvas() {
            canvas.width = image.offsetWidth;
            canvas.height = image.offsetHeight;
            imgWidth = image.naturalWidth;
            imgHeight = image.naturalHeight;
            drawPolygons();
        }

        const labels = <?= json_encode($labels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;

        function drawPolygons() {
            const scaleX = canvas.width / imgWidth;
            const scaleY = canvas.height / imgHeight;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            labels.forEach((label, index) => {
                if (Array.isArray(label.points) && label.points.length > 2) {
                    ctx.beginPath();
                    ctx.moveTo(label.points[0].x * scaleX, label.points[0].y * scaleY);

                    label.points.forEach(point => {
                        ctx.lineTo(point.x * scaleX, point.y * scaleY);
                    });

                    ctx.closePath();
                    ctx.fillStyle = (index === hoveredPolygonIndex) ? "rgba(128, 128, 128, 0.4)" : "rgba(255, 0, 0, 0.4)";
                    ctx.fill();
                    ctx.strokeStyle = "red";
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    label.polygonPath = label.points.map(point => ({
                        x: point.x * scaleX,
                        y: point.y * scaleY
                    }));
                }
            });
        }

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

        canvas.addEventListener("mousemove", function (event) {
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;

            let isOverPolygon = false;
            let newHoveredIndex = -1;

            labels.forEach((label, index) => {
                if (label.polygonPath && isPointInPolygon({ x: mouseX, y: mouseY }, label.polygonPath)) {
                    isOverPolygon = true;
                    newHoveredIndex = index;
                }
            });

            if (newHoveredIndex !== hoveredPolygonIndex) {
                hoveredPolygonIndex = newHoveredIndex;
                drawPolygons();
            }

            canvas.style.cursor = isOverPolygon ? "pointer" : "default";
        });

        canvas.addEventListener("click", function (event) {
            const mouseX = event.offsetX;
            const mouseY = event.offsetY;

            labels.forEach(label => {
                if (label.polygonPath && isPointInPolygon({ x: mouseX, y: mouseY }, label.polygonPath)) {
                    // Afficher les informations du label dans la colonne de droite
                    const labelInfoDiv = document.getElementById("label-info");
                    labelInfoDiv.innerHTML = `
                    <h5><strong>Nom du label :</strong> ${label.name}</h5>
                    <p><strong>Description :</strong> ${label.description}</p>
                    <p><strong>HTML :</strong> ${label.html}</p>
                `;
                }
            });
        });

        image.onload = initializeCanvas;
    });
</script>
</body>
</html>
