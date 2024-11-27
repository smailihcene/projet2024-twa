<?php
// Connexion à la base de données
$mysqli = new mysqli('localhost', 'user', '1234', 'projet2024');

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérifier si un id d'étiquette a été passé
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Requête pour récupérer les informations de l'étiquette
    $query = "SELECT Label.*, Image.path AS imagePath
              FROM Label
              LEFT JOIN Image ON Label.imageId = Image.id
              WHERE Label.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $label = $result->fetch_assoc();

    if ($label) {
        // Extraire les points du polygone et les convertir en tableau PHP
        $points = json_decode($label['points'], true);
        $imagePath = $label['imagePath'];
    } else {
        echo "Étiquette non trouvée.";
        exit;
    }
} else {
    echo "Aucune étiquette spécifiée.";
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher l'étiquette</title>
</head>
<body>
    <h2><?= htmlspecialchars($label['name']); ?></h2>
    <p>Description : <?= htmlspecialchars($label['description']); ?></p>
    <p>HTML : <?= htmlspecialchars($label['html']); ?></p>
    <p>Points (coordonnées du polygone) :</p>
    <pre><?= htmlspecialchars($label['points']); ?></pre>

    <canvas id="canvas"></canvas>
    
    <script>
        // Chargement de l'image
        const image = new Image();
       image.src = '<?= $imagePath; ?>'; // Path de l'image récupérée depuis la DB

        // Lorsque l'image est chargée, dessiner le polygone
        image.onload = function() {
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            
            // Trouver les limites du polygone pour ajuster la taille du canvas
            let minX = Math.min(...<?= json_encode($points); ?>.map(p => p.x));
            let maxX = Math.max(...<?= json_encode($points); ?>.map(p => p.x));
            let minY = Math.min(...<?= json_encode($points); ?>.map(p => p.y));
            let maxY = Math.max(...<?= json_encode($points); ?>.map(p => p.y));

            canvas.width = maxX - minX;
            canvas.height = maxY - minY;
            
            // Créer un chemin du polygone
            ctx.beginPath();
            ctx.moveTo(<?= json_encode($points[0]); ?>.x - minX, <?= json_encode($points[0]); ?>.y - minY);
            <?= json_encode($points); ?>.forEach((point, index) => {
                if (index > 0) {
                    ctx.lineTo(point.x - minX, point.y - minY);
                }
            });
            ctx.closePath();
            
            // Appliquer un clip pour dessiner uniquement la zone du polygone
            ctx.clip();
            
            // Dessiner l'image sur le canvas dans les limites du polygone
            ctx.drawImage(image, 0, 0);
        }
    </script>
</body>
</html>
