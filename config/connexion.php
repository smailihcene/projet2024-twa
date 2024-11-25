<?php
// Informations de connexion à la base de données
$dbConn = array(
    // $nom_serveur = "localhost";
    // $utilisateur = "user";
    // $mot_de_passe = "1234";
    // $nom_base_donnees = "projet2024";

    "user" => "user",  // Votre utilisateur
    "pass" => "1234",       // Mot de passe, laissé vide ici
    "name" => "projet2024",    // Nom de la base de données
    "host" => "localhost" // Hôte, souvent localhost pour les serveurs locaux
);

$con = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['name']);

if (!$con) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
}
