<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Rend la page responsive; elle s'adapte automatiquement aux différentes tailles d'écran -->
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Lien pour le style personnalisé -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lien Bootstrap -->
</head>
<body>
<!-- Header -->
<header class="bg-custom text-center py-3">
    <h1>Connexion</h1>
    <p>Veuillez entrer vos identifiants pour accéder à votre espace</p>
</header>

<!-- Main Content -->
<main>
    <section class="container">
        <!-- Utilisation de la classe container de Bootstrap -->
        <form action="auth.php" method="POST">
            <!-- Les données saisies sont envoyées à auth.php avec POST -->
            <div class="mb-3">
                <label for="login" class="form-label">Login :</label>
                <!-- Etiquette pour le champ "login" -->
                <input type="text" name="login" id="login" class="form-control" required>
                <!-- Champ de texte pour le login -->
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <!-- Etiquette pour le champ "password" -->
                <input type="password" name="password" id="password" class="form-control" required>
                <!-- Champ de texte pour le mot de passe -->
            </div>
            <div class="text-center mt-3">
                <input type="submit" value="Se connecter " class="btn btn-custom ">
            </div>
        </form>
    </section>
</main>
</body>
</html>
