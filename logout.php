<?php
// Démarrer la session
session_start();

// Détruire la session pour déconnecter l'utilisateur
session_unset(); //supprime les varibales de la session mais conserve la session 
session_destroy(); //ça détrit completement la session et permet de déconnecter l'utilisateur 

?>

<!DOCTYPE html>
<html lang="fr">  <!-- le contenue principal de cette page en français -->
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="url=index.php">
</head>
<body>
    <header>
        <h1>Déconnexion réussie</h1>  <!-- lee titre principal de la page --> 
    </header>
    <main>
        <section class="container">
            <p>Vous avez été déconnecté avec succès.</p>
            <p>Pour se rediriger vers la page d'accueil, <a href="index.php">cliquez ici</a>.</p>
            <!-- lien menant à la d'acceuil--> 
            
        </section>
    </main>
 
</body>
</html>
