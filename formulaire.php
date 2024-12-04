<?php
// Connexion à la base de données
require('db/connexion.php');
$mysqli = new mysqli('localhost', 'user', '1234', 'PROJET2024');

// Récupérer les rôles depuis la table userrole
$query = "SELECT id, name FROM userrole";
$result = $mysqli->query($query);  // Exécuter la requête et récupérer les résultats
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Lien vers le fichier CSS pour appliquer le thème -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Ajout de Bootstrap pour une meilleure mise en page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-custom">
<header class="text-center my-5">
    <h2 class="text-primary">Formulaire d'inscription</h2> <!-- Titre de la page -->
</header>
<div class="d-flex justify-content-center align-items-center">

    <div class="container2">
        <form action="inscription.php" method="post"> <!-- Formulaire d'inscription -->
            <!-- Champ pour le prénom -->
            <div class="mb-3">
                <label for="firstname" class="form-label">Prénom :</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>
            </div>

            <!-- Champ pour le nom -->
            <div class="mb-3">
                <label for="lastname" class="form-label">Nom :</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>
            </div>

            <!-- Champ pour l'adresse email -->
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Champ pour le nom d'utilisateur -->
            <div class="mb-3">
                <label for="login" class="form-label">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" class="form-control" required>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <!-- Champ pour choisir le rôle -->
            <div class="mb-3">
                <label for="userRoleId" class="form-label">Rôle :</label>
                <select id="userRoleId" name="userRoleId" class="form-select" required>
                    <option value="">-- Sélectionnez un rôle --</option>
                    <option value="1">editor</option>
                    <option value="2">non-editor</option>
                </select>
            </div>

            <!-- Bouton de soumission -->
            <div class="text-center">
                <button type="submit" class="btn btn-custom">S'inscrire</button>
            </div>
        </form>
    </div>
</div>

<!-- Inclusion du script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
