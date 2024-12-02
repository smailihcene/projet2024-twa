<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//affichage des erreurs pour faciliter le débogage 

session_start(); //démarrage de la session 
if (!isset($_SESSION['login'])) {  //si l'utilisateur est connecté 
    header("Location: login.php"); //redirection vers la page connexion si l'utilisateur n'est pas connecté
    exit();
}

require('../db/connexion.php');

// Requête pour récupérer les données nécessaires (étiquettes + chemins des images)
$query = "SELECT l.id, l.name, l.description, l.points, l.html, 
                 b.dir AS bank_dir, i.name AS image_name
          FROM label AS l
          INNER JOIN image AS i ON l.imageId = i.id
          INNER JOIN bank AS b ON i.bankId = b.id";

$rep = mysqli_query($con, $query); //exécution de la requête 
if (!$rep) {
    die("Erreur lors de la récupération des étiquettes : " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des étiquettes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Style CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include '../navbar.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Liste des étiquettes</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Points</th>
            <th>HTML</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($label = mysqli_fetch_assoc($rep)) { ?>
            <tr>
                <!-- Colonne d'image -->
                <td>
                    <img src="<?= "../images/" . htmlspecialchars($label['bank_dir']) . "/" . htmlspecialchars($label['image_name']); ?>"
                         alt="<?= htmlspecialchars($label['name']); ?>"
                         style="width: 100px; height: auto;"
                         class="img-thumbnail">
                </td>
                <!-- Nom -->
                <td><?= htmlspecialchars($label['name']); ?></td>
                <!-- Description -->
                <td><?= htmlspecialchars($label['description']); ?></td>
                <!-- Points -->
                <td><?= htmlspecialchars($label['points']); ?></td>
                <!-- HTML -->
                <td><?= htmlspecialchars($label['html']); ?></td>
                <!-- Actions -->
                <td>
                    <!-- Modifier -->
                    <a href="editer_label.php?id=<?= $label['id']; ?>" class="btn btn-custom btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Supprimer -->
                    <a href="supprimer_label.php?id=<?= $label['id']; ?>" class="btn btn-custom btn-sm"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette étiquette ?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
