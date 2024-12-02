<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');
include '../navbar.php';

// Vérifier si un ID est passé pour la modification
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  //conversion en entier 
    //requetes pour récupérer les données de l'étiquette 
    $query = "SELECT * FROM label WHERE id = $id";
    $result = mysqli_query($con, $query);

    //vérification sile résultat est valide et non vide 
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Étiquette introuvable."); //message d'arret et arret d'exécution 
    }

    $label = mysqli_fetch_assoc($result);  //extraction des donénes sous forme de tableau associatif
} else {
    header("Location: label.php");
    exit();
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //récupérer et échapper les données envpyé via le formulaire 
    $catalogId = mysqli_real_escape_string($con, $_POST['catalogId']);
    $imageId = mysqli_real_escape_string($con, $_POST['imageId']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $points = mysqli_real_escape_string($con, $_POST['points']);
    $html = mysqli_real_escape_string($con, $_POST['html']);

    //requ^te pour mettre à jour l'étiquette 
    $updateQuery = "UPDATE label SET catalogId = '$catalogId', imageId = '$imageId', name = '$name', description = '$description', points = '$points', html = '$html' WHERE id = $id";
    
    //exécution de la requette et redirection ou gestion d'erreur 
    if (mysqli_query($con, $updateQuery)) {
        header("Location: label.php");
        exit();
    } else {
        //affciher une erreur en cas de problème avec la requête 
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
    <title>Modifier l'Étiquette</title>
</head>
<body>
    

    <div class="container mt-4">
        <h1 class="mb-4">Modifier l'Étiquette</h1>
        <form action="editer_label.php?id=<?= htmlspecialchars($label['id']); ?>" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label hidden for="catalogId" class="form-label">ID du Catalogue :</label>
                <input hidden type="text" class="form-control" id="catalogId" name="catalogId" value="<?= htmlspecialchars($label['catalogId']); ?>" required>
            </div>
            <div class="mb-3">
                <label hidden for="imageId" class="form-label">ID de l'Image :</label>
                <input hidden type="text" class="form-control" id="imageId" name="imageId" value="<?= htmlspecialchars($label['imageId']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nom de l'Étiquette :</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($label['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($label['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="points" class="form-label">Points (format JSON) :</label>
                <textarea class="form-control" id="points" name="points" rows="2"><?= htmlspecialchars($label['points']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="html" class="form-label">HTML :</label>
                <textarea class="form-control" id="html" name="html" rows="4"><?= htmlspecialchars($label['html']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="label.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>

<?php mysqli_close($con); ?>
