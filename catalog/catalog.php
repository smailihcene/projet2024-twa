<?php
session_start(); //on démarre la session
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php'); ///connexion à la bd
$rep = mysqli_query($con, "SELECT * FROM catalog");  //on execute une requête sql pour récupérer les données de la table catalog
if (!$rep) { //si la requête échoue 
    die("Erreur lors de la récupération des catalogues : " . mysqli_error($con));  //message d'erreur 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>
    <!-- Bootstrap pour css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap pour JS --> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "../navbar.php"; ?>  <!-- on inclut la bare de navigation --> 

    <div class="container mt-4">
        <h1>Catalogue</h1>
        <?php if ($_SESSION['role_name'] === 'editor') { ?>
            <a class="btn btn-success mb-3" href="ajouter_catalog.php">
            <!-- bouton pour ajouter un catalogue -->     
                <i class="bi bi-plus-circle"></i> Ajouter un Catalogue
            <!-- icon et texte pour le bouton --> 
            </a> 
        <?php } ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <?php if ($_SESSION['role_name'] === 'editor') { ?> <!-- ajoute une colonne supplémentaire pour les ctions si l'utilisateurs est un éditeur --> 
                        <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($catalog = mysqli_fetch_assoc($rep)) { ?>
                    <tr>
                        <td>
                           <?= htmlspecialchars($catalog['name']); ?>
                        </td>
                        <td><?= htmlspecialchars($catalog['description']); ?></td>
                        <?php if ($_SESSION['role_name'] === 'editor') { ?>  <!-- afficher les actions que pour l'éditeur --> 
                            <td>
                                <a href="editer_catalog.php?id=<?= $catalog['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <!-- lien vers la page de modification --> 
                                <a href="supprimer_catalog.php?id=<?= $catalog['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce catalogue ?')">Supprimer</a>
                                <!-- lien vers la page de suppression --> 
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
