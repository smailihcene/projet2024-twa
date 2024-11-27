<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('db/connexion.php');

$mysqli = new mysqli('localhost', 'user', '1234', 'projet2024');
if (!$con) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
    //On vérifie si la connexion a échoué 
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
   $name = $mysqli->real_escape_string($_POST['name'] ?? '');
    $description = $mysqli->real_escape_string($_POST['description'] ?? '');
    $html = $mysqli->real_escape_string($_POST['html'] ?? '');
    $points = $mysqli->real_escape_string($_POST['points'] ?? '');

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

    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Erreur lors de la préparation de la requête : " . $mysqli->error);
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

$mysqli->close();
?>
