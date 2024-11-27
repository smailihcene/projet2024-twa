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
    <title>Gestion des Images</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head> 
<body> 
    <?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Liste des Images</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom de la Banque</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($image = mysqli_fetch_assoc($rep)) { ?>
                <tr>
                    <td><?= htmlspecialchars($image['bank_name']); ?></td>
                    <td>
                        <img src="<?= "./images/" . htmlspecialchars($image['bank_dir']) . "/" . htmlspecialchars($image['image_name']); ?>" alt="" class="img-thumbnail" width="100">
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body> 
</html> 