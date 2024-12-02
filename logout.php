<?php
// Démarrer la session
session_start();

// Détruire la session pour déconnecter l'utilisateur
session_unset(); // Supprime les variables de session mais conserve la session
session_destroy(); // Détruit complètement la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <!-- Application du style CSS avec les couleurs du thème -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Redirection vers index.php après 3 secondes -->
    <meta http-equiv="refresh" content="3; url=index.php">
</head>
<body class="bg-custom">
<section class="container3">
    <header>
        <h1 class="text-center text-primary">Déconnexion réussie</h1> <!-- Titre principal avec une couleur appropriée -->
    </header>
    <main>
        <section class="container text-center">
            <p class="text-success">Vous avez été déconnecté avec succès.</p>
            <p>Vous allez être redirigé vers la page d'accueil. Si cela ne se produit pas, <a href="index.php" class="btn btn-custom">cliquez ici</a> pour revenir à l'accueil.</p>
            <!-- Lien stylisé pour redirection -->
        </section>
    </main>
</section>
</body>
</html>
