<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

require('../db/connexion.php');

// Vérifier si un ID est passé pour la suppression
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir en entier pour éviter les injections SQL

    // Supprimer l'élément avec l'ID correspondant
    $query = "DELETE FROM label WHERE id = $id";
    if (mysqli_query($con, $query)) {
        // Redirection après succès
        header("Location: label.php"); // Redirige vers la liste des étiquettes
        exit();
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
} else {
    // Aucun ID n'a été fourni
    echo "ID de l'étiquette non spécifié.";
}
?>
