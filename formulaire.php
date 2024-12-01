<?php
// Connexion à la base de données
require('db/connexion.php');
$mysqli = new mysqli('localhost', 'user', '1234', 'projet2024');

// Récupérer les rôles depuis la table userrole
$query = "SELECT id, name FROM userrole";
$result = $mysqli->query($query);  //exécuter la requête et récupérer les résultats 


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" >
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <h2>Formulaire d'inscription</h2> <!--titre de la page --> 
    <section class="container2">

        <form action="inscription.php" method="post"> <!-- les données du formulaire serint envoyé au fichier 'inscription.php' et la méthode post permet d'envoyer les données de ménière sécurisé --> 
            <!-- Champ pour le prénom  -->
            <label for="firstname">Prénom :</label>  
            <input type="text" id="firstname" name="firstname" required><br>
            <!-- required rend le champ obligatoire --> 

            <!-- Champ pour le nom -->
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" required><br>

            <!-- Champ pour l'adresse mail -->
            <label for="email">Adresse email :</label>
            <input type="email" id="email" name="email" required><br>

            <!-- Champ pour le nom d'utilisateur -->
            <label for="login">Nom d'utilisateur :</label>
            <input type="text" id="login" name="login" required><br>

            <!-- Champ pour le mot de base -->
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>

            <!-- Champ pour choisir le rôle -->
            <label for="userRoleId">Rôle :</label>
            <select id="userRoleId" name="userRoleId" required>
    <option value="">-- Sélectionnez un rôle --</option>
    <option value="1">editor</option>
        <option value="2">non-editor</option>
</select><br>

            <button type="submit">S'inscrire</button> 
            <!-- le submit signifie que lorsque l'utilisateur clique sur ce bouton le formulaire sera envoyé au serveur -->
        </form>
    </section>
    
</body>
</html>