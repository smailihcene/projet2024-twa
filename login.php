<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
        <p>Veuillez entrer vos identifiants pour accéder à votre espace</p>
    </header>
    <main>
        <section class="container">
            <form action="auth.php" method="POST">
                <label for="login">Login :</label>
                <input type="text" name="login" required>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>
                <input type="submit" value="Se connecter" class="btn">
            </form>
        </section>
    </main>

</body>
</html>
