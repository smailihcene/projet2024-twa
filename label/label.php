<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');
$query = "SELECT * FROM label";

$rep = mysqli_query($con, $query);
if (!$rep) {
    die("Erreur lors de la récupération des images : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des étiquettes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Liste des étiquettes</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>cataloId</th>
                    <th>imageId</th>
                    <th>name</th>
                    <th>description</th>
                    <th>points</th>
                    <th>html</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($label = mysqli_fetch_assoc($rep)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($label['id']); ?></td>
                        <td><?= htmlspecialchars($label['catalogId']); ?></td>
                        <td><?= htmlspecialchars($label['imageId']); ?></td>
                        <td><?= htmlspecialchars($label['name']); ?></td>
                        <td><?= htmlspecialchars($label['description']); ?></td>
                        <td><?= htmlspecialchars($label['points']); ?></td>
                        <td><?= htmlspecialchars($label['html']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
