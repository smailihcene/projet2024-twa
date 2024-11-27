<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM catalog WHERE id = $id";
    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Catalogue introuvable.");
    }

    $catalog = mysqli_fetch_assoc($result);
} else {
    header("Location: catalog.php");
    exit();
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);

    $updateQuery = "UPDATE catalog SET name = '$name', description = '$description' WHERE id = $id";
    if (mysqli_query($con, $updateQuery)) {
        header("Location: catalog.php");
        exit();
    } else {
        die("Erreur lors de la mise à jour : " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Modifier un Catalogue</title>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Modifier le Catalogue</h1>
        <form action="editer_catalog.php?id=<?= htmlspecialchars($catalog['id']); ?>" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($catalog['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($catalog['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="catalog.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($con); ?>
