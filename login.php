<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Couleurs personnalisées */
        body {
            background-color: #ecf0f1; /* Couleur de fond bleu clair */
        }
        header {
            background-color: #3498db; /* Couleur du header */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #3498db; /* Couleur des boutons */
            color: white;
        }
        .btn-custom:hover {
            background-color: #2980b9; /* Couleur du bouton au survol */
            color: white;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
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
