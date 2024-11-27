<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outil d'Étiquetage</title>
    <style>
        canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Outil d'Étiquetage</h1>
    <canvas id="imageCanvas" width="600" height="400"></canvas>
    <form id="labelForm" method="POST" action="saveLabel.php">
        <label>Nom de l'Étiquette :</label>
        <input type="text" name="name" required><br>

        <label>Description Courte :</label>
        <textarea name="description" required></textarea><br>

        <label>Description HTML :</label>
        <textarea name="html"></textarea><br>

        <!-- Coordonnées du polygone -->
        <input type="hidden" name="points" id="polygonPoints">

        <button type="button" id="saveBtn">Sauvegarder</button>
    </form>

    <script>
        const canvas = document.getElementById('imageCanvas');
        const ctx = canvas.getContext('2d');
        const points = [];
        let isDrawing = false;

        // Charger une image sur le canvas
        const img = new Image();
        img.src = '../images/.'; // Chemin de l'image à annoter
        img.onload = () => {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };

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
            // Convertir les points en JSON
            const pointsJSON = JSON.stringify(points);
            document.getElementById('polygonPoints').value = pointsJSON;

            // Soumettre le formulaire
            document.getElementById('labelForm').submit();
        });
    </script>
</body>
</html>
