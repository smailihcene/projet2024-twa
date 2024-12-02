<?php
session_start(); //démrrage de la session 

if (!isset($_SESSION['login'])) {  //vérifie si l'utilisateur est connecté 
    header("Location: login.php"); //sinon redirection vers la page de connexion
    exit(); //arret de l'execution du script 
}

require('../db/connexion.php');  //connexion à la bd 

if (isset($_GET['id'])) { 
    $id = intval($_GET['id']); //on convertit l'ID en entier 
    $query = "SELECT * FROM catalog WHERE id = $id";  //requête sql pour récuperer les détails du catalogue 
    $result = mysqli_query($con, $query);  //execution de la requête 

    if (!$result || mysqli_num_rows($result) == 0) {   //vérifie si aucun résultat n'est trouvé 
        die("Catalogue introuvable."); //message d'erreur 
    }

    $catalog = mysqli_fetch_assoc($result);  //on récupère les données du catalogue sous forme de tableau 
} else {
    header("Location: catalog.php");  //redirection vers la page 'catalog.php'
    exit();  //arret de l'éxectution 
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']); //sécurise l'entrée utilisateur pour le champ nom 
    $description = mysqli_real_escape_string($con, $_POST['description']); //sécurise l'entrée utilisateur pour le champ description 

    //une requête pour mettre à jour le catalogue avce les nouvelles données 
    $updateQuery = "UPDATE catalog SET name = '$name', description = '$description' WHERE id = $id";
    if (mysqli_query($con, $updateQuery)) {  //execution de la requête 
        header("Location: catalog.php"); //redirecton vers la page catalog.php 
        exit();
    } else {//message d'erreur 
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>  <!-- inclusion de la navbar --> 

    <div class="container mt-4">
        <h1 class="mb-4">Modifier le Catalogue</h1>
        <form action="editer_catalog.php?id=<?= htmlspecialchars($catalog['id']); ?>" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nom :</label> <!-- Lable pour le nom -->
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($catalog['name']); ?>" required>
                <!-- zone de texte pré remplie -->
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label> <!-- label pour la description -->
                <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($catalog['description']); ?></textarea>
                <!-- zone de texte pré remplie -->
            </div>
            <button type="submit" class="btn btn-custom btn-sm">Mettre à jour</button> <!-- bouton pour mettre à jour  -->
            <a href="catalog.php" class="btn btn-custom btn-sm">Annuler</a> <!-- bouton annuler qui mène vers la page catalog.php -->
        </form>
    </div>
</body>
</html>

<?php mysqli_close($con); ?> <!--fermeture de la connexion à la base de donnée -->
