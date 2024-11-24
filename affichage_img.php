<?php
session_start(); 
if (!isset($_SESSION['login'])) {
    header("Location: login.php"); 
    exit();
}

require('config/connexion.php');

$fileName = $_FILES['image']['name'];
$image_path = "./images" . $bank_dir . "/" . $fileName;


$query = "SELECT i.id, i.name AS image_path, b.name AS bank_name 
        FROM Image i 
        JOIN Bank b ON i.bankId = b.id";

$query_path = "SELECT b.dir AS bank_dir, i.name AS image_name
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
    <title>Gestion des Images</title>
</head>
<body>
    <h1>Liste des Images</h1>
    <table border="2">
        <tr>
            <th>Banque</th>
            <th>Image</th>
        </tr>

        <!-- Boucle pour afficher les résultats -->
        <?php while ($image = mysqli_fetch_assoc($rep)) { ?>
            <tr>
                <td><?= htmlspecialchars($image['bank_dir']); ?></td>
                <td><img src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" alt="" width="100"></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
