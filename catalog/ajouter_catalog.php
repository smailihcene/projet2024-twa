<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}


require('../config/connexion.php');

// Traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $userAccoundId = 1;
    
    
    // Vérifier que les champs ne sont pas vides
    if (!empty($name) && !empty($description)) {
        // Préparer la requête d'ajout avec userAccountId
        $query = "INSERT INTO catalog (userAccoundId, name, description) VALUES ('$userAccoundId', '$name', '$description')";
        
        if (mysqli_query($con, $query)) {
            // Redirection après succès
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
    <title>Ajouter un Catalogue</title>
</head>
<body>
    <header>
        <h1>Ajouter un Catalogue</h1>
    </header>
    <main>
        <form action="ajouter_catalog.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
            <br>
            <button type="submit">Ajouter</button>
        </form>
    </main>
</body>
</html>

<?php mysqli_close($con); ?>
