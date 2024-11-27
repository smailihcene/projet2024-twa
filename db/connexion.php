<?php
// Informations de connexion à la base de données
$dbConn = array(
    "user" => "user",  // Votre utilisateur
    "pass" => "1234",       // Mot de passe, laissé vide ici
    "name" => "projet2024",    // Nom de la base de données
    "host" => "localhost" // Hôte, souvent localhost pour les serveurs locaux
);

$con = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['name']);

if (!$con) {  //quand la connexion à la base de donnée
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
    //Quand ça échoue la fonction die renvoie ce message et grâce à "mysqli_connect_error()" on a l'affichage de l'erreur spécifié 
}
