<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');

// Traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $userAccoundId = 1;

    if (!empty($name) && !empty($description)) {
        $query = "INSERT INTO catalog (userAccoundId, name, description) VALUES ('$userAccoundId', '$name', '$description')";

        if (mysqli_query($con, $query)) {
            header("Location: catalog.php");
            exit();
        } else {
            echo "Erreur lors de l'ajout : " . mysqli_error($con);
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Ajouter un Catalogue</title>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Ajouter un Catalogue</h1>
        <form action="ajouter_catalog.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="catalog.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($con); ?>
