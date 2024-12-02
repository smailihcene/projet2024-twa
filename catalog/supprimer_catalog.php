<?php
session_start(); //demarrage de la session 
ini_set('display_errors', 1);  //permet l'affichage des erreurs pour le débogage 
ini_set('display_startup_errors', 1);  //permet d'activer les erreurs dès le démarrage 
error_reporting(E_ALL);  //définit le niveau de l'erreur

if (!isset($_SESSION['login'])) { //on vérifie si l'utilisateur est connecté 
    header("Location: login.php");
    exit();
}

require('../db/connexion.php'); //connexion à la db 

// Vérifier si un ID est passé pour la suppression
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir en entier pour éviter les injections SQL

    // Supprimer l'élément avec l'ID correspondant
    $query = "DELETE FROM catalog WHERE id = $id";  //une requêt pour SQL pour supprimer le catalogue avec l'ID 
    if (mysqli_query($con, $query)) { //execution de la requête 
        // Redirection vers la page 'catalog.php' après succès
        header("Location: catalog.php");
        exit();
    } else { //si on rencontre une erreur on a ce message 
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
} else {
    // Aucun ID n'a été fourni
    echo "ID de catalogue non spécifié.";
}
?>
