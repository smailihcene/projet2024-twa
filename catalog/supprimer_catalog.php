<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../config/connexion.php');

// Vérifier si un ID est passé pour la suppression
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir en entier pour éviter les injections SQL

    // Supprimer l'élément avec l'ID correspondant
    $query = "DELETE FROM catalog WHERE id = $id";
    if (mysqli_query($con, $query)) {
        // Redirection après succès
        header("Location: catalog.php");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
} else {
    // Aucun ID n'a été fourni
    echo "ID de catalogue non spécifié.";
}
?>
