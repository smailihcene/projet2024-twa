<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');
$rep = mysqli_query($con, "SELECT * FROM catalog");
if (!$rep) {
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "../navbar.php"; ?>

    <div class="container mt-4">
        <h1>Catalogue</h1>
        <?php if ($_SESSION['role_name'] === 'editor') { ?>
            <a class="btn btn-success mb-3" href="ajouter_catalog.php">
                <i class="bi bi-plus-circle"></i> Ajouter un Catalogue
            </a>
        <?php } ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <?php if ($_SESSION['role_name'] === 'editor') { ?>
                        <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($catalog = mysqli_fetch_assoc($rep)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($catalog['id']); ?></td>
                        <td><?= htmlspecialchars($catalog['name']); ?></td>
                        <td><?= htmlspecialchars($catalog['description']); ?></td>
                        <?php if ($_SESSION['role_name'] === 'editor') { ?>
                            <td>
                                <a href="editer_catalog.php?id=<?= $catalog['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="supprimer_catalog.php?id=<?= $catalog['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce catalogue ?')">Supprimer</a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
