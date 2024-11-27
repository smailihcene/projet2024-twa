<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Connexion</h1>
        <p>Veuillez entrer vos identifiants pour accéder à votre espace</p>
    </header>

    <!-- Main Content -->
    <main>
        <section class="container">
            <form action="auth.php" method="POST">
                <div class="mb-3">
                    <label for="login" class="form-label">Login :</label>
                    <input type="text" name="login" id="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <input type="submit" value="Se connecter" class="btn btn-custom w-100">
            </form>
        </section>
    </main>

</body>
</html>
