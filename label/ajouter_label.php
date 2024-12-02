<?php
session_start(); //démarrage de la session 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//active l'affichage des erreur 

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

// Récupérer les paramètres d'ID de l'image et du catalogue
$image_id = isset($_GET['image_id']) ? $_GET['image_id'] : null;
$catalog_id = isset($_GET['catalog_id']) ? $_GET['catalog_id'] : null;

if (!$image_id || !$catalog_id) {
    die("Erreur : Image ID ou Catalog ID manquant.");
}//on vérifie si les éléments présents 

// Requête pour récupérer les informations de l'image
$query = "SELECT b.dir AS bank_dir, i.name AS image_name, b.name AS bank_name 
          FROM image AS i
          INNER JOIN bank AS b ON i.bankId = b.id
          WHERE i.id = ?";
$stmt = $con->prepare($query); //prépare une requête 
$stmt->bind_param("i", $image_id); //permet de lier le paramètre 'image_id' 
$stmt->execute();  //exécution de la requête 
$result = $stmt->get_result();  
$image = $result->fetch_assoc();
//récupère les résultat de la requête 

if (!$image) { // si l'image est inexistante on affiche un message d'erreur 
    die("Image introuvable.");
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Étiquette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<?php include '../navbar.php'; ?>

<div class="container mt-4">
    <h1>Ajouter une Étiquette pour l'image : <?= htmlspecialchars($image['image_name']); ?></h1>

    <div class="row">
        <div class="col-md-8">
            <canvas id="imageCanvas" width="600" height="400"></canvas>
            <!-- Canvas HTML où l'image sera affichéd et les zones étiquetés dessinées --> 
        </div>
        <div class="col-md-4">
            <form id="labelForm" method="POST" action="enregistrement_label.php">
                <!-- formulaire pour ajouter une étiquette --> 
                <input type="hidden" name="image_id" value="<?= htmlspecialchars($image_id); ?>">
                <input type="hidden" name="catalog_id" value="<?= htmlspecialchars($catalog_id); ?>">

                <div class="mb-3">
                    <label>Nom de l'Étiquette :</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description Courte :</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Description HTML :</label>
                    <textarea name="html" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Coordonnées du Polygone :</label>
                    <input type="hidden" name="points" id="polygonPoints">
                </div>

                <button type="button" id="saveBtn" class="btn btn-success">Sauvegarder</button>
                <a href="../afficher_img_detail.php?id=<?= htmlspecialchars($image_id); ?>" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>
</div>

<!-- partie JS --> 
<script>
    const canvas = document.getElementById('imageCanvas');  // on récupère l'élément canvas dans la page HTML 
    const ctx = canvas.getContext('2d');  //on peut dessiner en 2D 
    const points = [];  //tableau pour stocker les coordonées des points du polygone 
    let isDrawing = false;  
    let img = new Image();

    // Charger l'image sur le canvas
    img.src = '.././images/<?= htmlspecialchars($image['bank_dir']); ?>/<?= htmlspecialchars($image['image_name']); ?>';
    img.onload = () => {
        //lorsque l'image est chargée cette fonction est appelé 
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    };

    // Dessiner des points et des lignes sur le canvas
    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        //calcule les coordonnées x et y 

        points.push({ x, y });
        //ajout des coordonnée dans le tableau 
        drawPolygon();
    });

    function drawPolygon() { //cette fonction permet de dessiner le polynome et les points sur le canvas 
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        if (points.length > 0) {
            ctx.beginPath();
            //demarre un nouveau chemin de dessin 
            ctx.moveTo(points[0].x, points[0].y);
            //positionne le curseur de dessin au premier point du polygone 
            points.forEach((point) => {
                ctx.lineTo(point.x, point.y);
            });//trace une ligne vers chaque point
            ctx.closePath();//fermer le chemin du polygone en reliant le dernier point au prmeier 

            ctx.fillStyle = 'rgba(255, 0, 0, 0.3)'; //la couleur de remplissage un rouge clair transparent 
            ctx.fill(); //remplie le polygone avec cette couleur 

            ctx.strokeStyle = 'red';  //les bordures en rouge 
            ctx.stroke(); //l'appliquer au polygone 

            points.forEach((point) => {
                ctx.beginPath();
                ctx.arc(point.x, point.y, 3, 0, 2 * Math.PI); //ca permet de dessiner un ptit cercle sur les coordonnées du point 
                ctx.fillStyle = 'blue'; //la couleur de remplissage des point en bleu 
                ctx.fill(); //la définir sur les points
            });
        }
    }

    // Sauvegarder les données
    document.getElementById('saveBtn').addEventListener('click', () => {
        const pointsJSON = JSON.stringify(points);
        document.getElementById('polygonPoints').value = pointsJSON;
        document.getElementById('labelForm').submit();
    });
</script>
</body>
</html>
