<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('db/connexion.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
    $name = $con->real_escape_string($_POST['name'] ?? '');
    $description = $con->real_escape_string($_POST['description'] ?? '');
    $html = $con->real_escape_string($_POST['html'] ?? '');
    $points = $con->real_escape_string($_POST['points'] ?? '');
    $catalogId = $con->real_escape_string($_POST['points'] ?? '');


    // Vérification des champs obligatoires
    if (empty($name) || empty($description) || empty($points)) {
        die("Erreur : Tous les champs requis ne sont pas remplis !");
    }

    // Ajouter des valeurs pour catalogId et imageId (par défaut)
    $catalogId = 16;
    $imageId = 111;

    // Préparer la requête d'insertion
    $query = "INSERT INTO Label (catalogId, imageId, name, description, points, html) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);

    if (!$stmt) {
        die("Erreur lors de la préparation de la requête : " . $con->error);
    }

    // Lier les paramètres à la requête
    $stmt->bind_param("iissss", $catalogId, $imageId, $name, $description, $points, $html);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Étiquette enregistrée avec succès !";
        echo "<a href='afficher_label.php'>Voir les étiquettes</a>";
    } else {
        die("Erreur lors de l'exécution de la requête : " . $stmt->error . " | Données : " . json_encode($_POST));
    }

    $stmt->close();
} else {
    echo "Méthode HTTP non autorisée.";
}

$con->close();
?>
