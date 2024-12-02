<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../db/connexion.php');

$mysqli = new mysqli('localhost', 'user', '1234', 'PROJET2024');
if (!$con) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
}

// Vérifier si le formulaire a été soumis via une reuqêtte HTPP POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
    $name = $con->real_escape_string($_POST['name'] ?? '');  //nomde l'étiquette 
    $description = $con->real_escape_string($_POST['description'] ?? ''); //description de l'étiquette 
    $html = $con->real_escape_string($_POST['html'] ?? ''); //html de l'étiquette 
    $points = $con->real_escape_string($_POST['points'] ?? '');  //les coordonnées de points du polygone 
    $catalogId = $con->real_escape_string($_POST['catalog_id'] ?? ''); // Récupérer l'ID du catalogue
    $imageId = $con->real_escape_string($_POST['image_id'] ?? ''); //id de l'image 

    // Vérification des champs obligatoires
    if (empty($name) || empty($description) || empty($points)) {
        die("Erreur : Tous les champs requis ne sont pas remplis !");
    }

    // Préparer la requête d'insertion
    $query = "INSERT INTO Label (catalogId, imageId, name, description, points, html) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);  //préparer la requête

    if (!$stmt) {  // si la préparation échoue message d'erreur 
        die("Erreur lors de la préparation de la requête : " . $con->error);
    }

    // Lier les paramètres à la requête
    $stmt->bind_param("iissss", $catalogId, $imageId, $name, $description, $points, $html);
    //"iissss" indique les types des paramètres 

    // Exécuter la requête
    if ($stmt->execute()) {
        // Rediriger vers la page détail de l'image associée à l'étiquette
        header("Location: ../afficher_img_detail.php?id=" . $imageId);
        exit();
    } else {
        die("Erreur lors de la mise à jour : " . $stmt->error . " | Données : " . json_encode($_POST));
    }

    $stmt->close(); //fermer la requetes 
} else {
    echo "Méthode HTTP non autorisée.";
}

$con->close(); //fermer la connexion à la bd 
?>
