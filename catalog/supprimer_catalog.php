<?php
session_start(); // Démarrage de la session
ini_set('display_errors', 1); // Afficher les erreurs
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) { // Vérifier si l'utilisateur est connecté
    header("Location: login.php");
    exit();
}

require('../db/connexion.php'); // Connexion à la base de données

// Vérifier si un ID est passé pour la suppression
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir en entier pour éviter les injections SQL

    // Supprimer les enregistrements liés dans la table catalogimage
    $deleteImagesQuery = "DELETE FROM catalogimage WHERE catalogId = $id";
    if (!mysqli_query($con, $deleteImagesQuery)) {
        echo "Erreur lors de la suppression des images associées : " . mysqli_error($con);
        exit();
    }

    // Supprimer les enregistrements liés dans la table label
    $deleteLabelsQuery = "DELETE FROM label WHERE catalogId = $id";
    if (!mysqli_query($con, $deleteLabelsQuery)) {
        echo "Erreur lors de la suppression des labels associés : " . mysqli_error($con);
        exit();
    }

    // Supprimer l'élément dans la table catalog
    $deleteCatalogQuery = "DELETE FROM catalog WHERE id = $id";
    if (mysqli_query($con, $deleteCatalogQuery)) {
        // Redirection vers la page 'catalog.php' après succès
        header("Location: catalog.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du catalogue : " . mysqli_error($con);
    }
} else {
    // Aucun ID n'a été fourni
    echo "ID de catalogue non spécifié.";
}
?>