<?php
// Démarrer la session
session_start();

// Détruire la session pour déconnecter l'utilisateur
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
    <header>
        <h1>Déconnexion réussie</h1>
    </header>
    <main>
        <section class="container">
            <p>Vous avez été déconnecté avec succès.</p>
            <p>Vous allez être redirigé vers la page d'accueil dans quelques secondes.</p>
            <p>Si la redirection ne fonctionne pas, <a href="index.php">cliquez ici</a>.</p>
        </section>
    </main>
 
</body>
</html>
